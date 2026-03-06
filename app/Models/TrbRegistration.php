<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrbRegistration extends Model
{
    protected $table = 'trb_registrations';

    protected $fillable = [
        'kode_pelaut',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'nik_ktp',
        'alamat',
        'kelurahan_desa',
        'kecamatan',
        'kabupaten_kota',
        'jenis_kelamin',
        'nama_ibu_kandung',
        'no_whatsapp',
        'tahun_masuk_taruna',
        'registration_year',
        'status',
        'submitted_at',
        'edit_token_hash',
        'edit_token_expires_at',
        'token_last_regenerated_by',
        'token_last_regenerated_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'registration_year' => 'integer',
        'submitted_at' => 'datetime',
        'edit_token_expires_at' => 'datetime',
        'token_last_regenerated_at' => 'datetime',
    ];

     public function documents(): HasMany
    {
        return $this->hasMany(TrbDocument::class, 'trb_registration_id');
    }

    public function tokenLastRegeneratedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'token_last_regenerated_by');
    }

    
}
