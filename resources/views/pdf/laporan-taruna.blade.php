<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dokumen Taruna TRB</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: sans-serif;
            font-size: 9px;
            color: #1a1a1a;
            background: #fff;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #1a1a7a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-text { margin-left: 10px; }
        .header-text h1 {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a7a;
        }
        .header-text p {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        .meta {
            font-size: 8px;
            color: #666;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        thead tr {
            background-color: #1a1a7a;
            color: #ffffff;
        }
        thead th {
            padding: 5px 4px;
            text-align: center;
            font-size: 8px;
            font-weight: 600;
            border: 1px solid #1a1a7a;
        }
        tbody tr:nth-child(even) {
            background-color: #f4f4fb;
        }
        tbody td {
            padding: 4px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 8px;
        }
        .text-center { text-align: center; }
        .text-left   { text-align: left; }

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 600;
            text-align: center;
        }
        .badge-success { background: #d4edda; color: #1a7a3a; }
        .badge-danger  { background: #f8d7da; color: #cc1a1a; }
        .badge-warning { background: #fff3cd; color: #9e8b00; }
        .badge-gray    { background: #e9ecef; color: #555;    }

        .footer {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 7px;
            color: #999;
            text-align: center;
        }

        .summary {
            background: #f4f4fb;
            border: 1px solid #c5c5ed;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 8px;
            color: #1a1a7a;
        }
        .summary span { font-weight: 700; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-text">
            <h1>TRB-HUB &mdash; Laporan Rekap Dokumen Taruna</h1>
            <p>Politeknik Maritim AMI Makassar &bull; Sistem Manajemen Dokumen TRB</p>
        </div>
    </div>

    <div class="meta">
        Digenerate pada: <strong>{{ $generatedAt }}</strong>
        &bull; Total Taruna: <strong>{{ $registrations->count() }}</strong>
        &bull; Jenis Dokumen Aktif: <strong>{{ $documentTypes->count() }}</strong>
    </div>

    @php
        $totalApproved = 0;
        $totalPending  = 0;
        $totalFailed   = 0;
        foreach ($registrations as $reg) {
            foreach ($reg->documents as $doc) {
                if ($doc->admin_verification_status === 'approved') $totalApproved++;
                elseif ($doc->admin_verification_status === 'rejected') $totalFailed++;
                else $totalPending++;
            }
        }
    @endphp

    <div class="summary">
        Ringkasan Verifikasi &mdash;
        <span>{{ $totalApproved }}</span> Disetujui &bull;
        <span>{{ $totalPending }}</span> Menunggu &bull;
        <span>{{ $totalFailed }}</span> Ditolak
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:20px;">No</th>
                <th style="width:55px;">Kode Pelaut</th>
                <th style="width:90px;">Nama Taruna</th>
                <th style="width:30px;">T.Masuk</th>
                <th style="width:55px;">NIK</th>
                <th style="width:65px;">No. WA</th>
                @foreach ($documentTypes as $type)
                    <th>{{ $type->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($registrations as $index => $reg)
                @php
                    $docsByType = $reg->documents->keyBy('document_type_id');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $reg->kode_pelaut }}</td>
                    <td>{{ $reg->nama }}</td>
                    <td class="text-center">{{ $reg->tahun_masuk_taruna }}</td>
                    <td class="text-center">{{ $reg->nik_ktp }}</td>
                    <td class="text-center">{{ $reg->no_whatsapp }}</td>

                    @foreach ($documentTypes as $type)
                        @php
                            $doc         = $docsByType->get($type->id);
                            $adminStatus = $doc ? $doc->admin_verification_status : null;
                            $ocrConf     = $doc ? $doc->ocr_confidence : null;
                            $driveFileId = $doc ? ($doc->drive_file_id ?? '') : '';
                            $isLocal     = $driveFileId !== '' && str_contains($driveFileId, '/');
                            $linkUrl     = null;
                            if ($driveFileId !== '') {
                                $linkUrl = $isLocal
                                    ? url('storage/' . $driveFileId)
                                    : 'https://drive.google.com/file/d/' . $driveFileId . '/view';
                            }
                        @endphp
                        <td class="text-center">
                            @if (!$doc)
                                <span class="badge badge-gray">Belum</span>
                            @else
                                {{-- Badge status admin --}}
                                @if ($adminStatus === 'approved')
                                    <span class="badge badge-success">✓ OK</span>
                                @elseif ($adminStatus === 'rejected')
                                    <span class="badge badge-danger">✗ Tolak</span>
                                @else
                                    <span class="badge badge-warning">⏳ Tunggu</span>
                                @endif

                                {{-- Skor OCR --}}
                                @if ($ocrConf !== null)
                                    <br>
                                    <span style="font-size:7px;color:{{ $ocrConf >= 70 ? '#1a7a3a' : ($ocrConf >= 50 ? '#9e8b00' : '#cc1a1a') }};">
                                        OCR: {{ round($ocrConf, 0) }}%
                                    </span>
                                @endif

                                {{-- Link dokumen --}}
                                @if ($linkUrl)
                                    <br>
                                    <a href="{{ $linkUrl }}"
                                       style="font-size:7px;color:#1a1a7a;text-decoration:underline;">
                                        {{ $isLocal ? 'Lihat Lokal' : 'Lihat Drive' }}
                                    </a>
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 6 + $documentTypes->count() }}"
                        class="text-center" style="padding:12px; color:#999;">
                        Belum ada data pendaftaran taruna.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        TRB-HUB &bull; Politeknik Maritim AMI Makassar &bull;
        Dokumen ini digenerate otomatis oleh sistem &bull; {{ $generatedAt }}
    </div>

</body>
</html>