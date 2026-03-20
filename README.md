# TRB-HUB — Sistem Manajemen Dokumen Taruna

> **Politeknik Maritim AMI Makassar**  
> Sistem digitalisasi pengumpulan dan verifikasi dokumen Training Record Book (TRB) untuk taruna.

---

## 📋 Deskripsi

TRB-HUB adalah sistem berbasis web yang dibangun untuk mempermudah proses pengumpulan, validasi otomatis, dan verifikasi manual dokumen taruna Politeknik Maritim AMI Makassar. Sistem ini menggunakan OCR (Optical Character Recognition) untuk memvalidasi keaslian dokumen secara otomatis sebelum diverifikasi oleh admin.

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel 12 |
| Admin Panel | Filament v5 |
| Frontend (Taruna) | Bootstrap 5.3 |
| CSS Framework (Admin) | Tailwind CSS v4 |
| Build Tool | Vite v7 |
| OCR Engine | Tesseract OCR (64-bit) |
| Image Processing | ImageMagick + PHP Imagick |
| PDF Rendering | Ghostscript |
| Cloud Storage | Google Drive API (OAuth2) |
| PDF Export | DomPDF (barryvdh/laravel-dompdf) |
| Queue | Laravel Queue (Database Driver) |
| Database | MySQL |
| PHP | ^8.2 |

---

## ⚙️ Persyaratan Sistem

### Software Wajib
- PHP >= 8.2 (dengan extension: `imagick`, `gd`, `pdo_mysql`)
- Composer
- Node.js & NPM
- MySQL
- **Tesseract OCR 64-bit** — [Download UB Mannheim](https://github.com/UB-Mannheim/tesseract/wiki)
  - Wajib install language data: `ind` (Indonesian) + `eng` (English)
  - Tambahkan ke System PATH: `C:\Program Files\Tesseract-OCR`
- **Ghostscript 64-bit** — untuk membaca PDF
- **ImageMagick** — untuk konversi PDF ke gambar
  - Aktifkan `extension=imagick` di `php.ini`

---

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/trb-hub.git
cd trb-hub
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi `.env`
```env
APP_NAME="TRB-HUB"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trb_hub
DB_USERNAME=root
DB_PASSWORD=

# Google Drive OAuth2
GOOGLE_DRIVE_CLIENT_ID=your_client_id
GOOGLE_DRIVE_CLIENT_SECRET=your_client_secret
GOOGLE_DRIVE_REFRESH_TOKEN=your_refresh_token

QUEUE_CONNECTION=database
```

### 5. Migrasi Database
```bash
php artisan migrate
```

### 6. Build Assets
```bash
npm run build
```

### 7. Buat Admin Pertama
```bash
php artisan make:filament-user
```

---

## 🖥️ Menjalankan Aplikasi

### Development (semua service sekaligus)
```bash
# PowerShell — gunakan ; bukan &&
composer run dev
```

Perintah di atas akan menjalankan:
- `php artisan serve` — Web server
- `php artisan queue:listen --tries=1` — Queue worker (OCR + Upload Drive)
- `npm run dev` — Vite dev server

### Production
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan queue worker di background
php artisan queue:work --tries=3 --daemon
```

---

## 📁 Struktur Aplikasi

```
app/
├── Filament/
│   ├── Pages/
│   │   └── LaporanPdf.php          ← Halaman export laporan
│   └── Resources/
│       ├── MasterDocumentTypes/    ← CRUD jenis dokumen
│       ├── TrbDocuments/           ← Verifikasi dokumen taruna
│       └── TrbRegistrations/       ← Manajemen pendaftaran taruna
├── Http/
│   └── Controllers/
│       ├── Admin/
│       │   └── LaporanController.php
│       └── Taruna/
│           ├── TarunaDocumentController.php
│           └── TarunaRegistrationController.php
├── Jobs/
│   ├── ProcessDocumentOCR.php      ← Job OCR otomatis
│   └── UploadToGoogleDrive.php     ← Job upload ke Drive
├── Models/
│   ├── MasterDocumentType.php
│   ├── TrbDocument.php
│   ├── TrbRegistration.php
│   └── User.php
└── Services/
    ├── LaporanService.php          ← Logic generate PDF
    ├── TarunaDocumentService.php   ← Logic upload dokumen
    └── TarunaRegistrationService.php ← Logic pendaftaran & token
```

---

## 🔄 Alur Sistem

### Alur Taruna
```
1. Taruna mengisi form pendaftaran (identitas)
2. Sistem generate edit token (berlaku 7 hari)
3. Taruna upload dokumen satu per satu
4. Sistem simpan file sementara di local storage
5. Job OCR dijalankan otomatis (background queue)
6. Jika OCR valid → file diupload ke Google Drive → file lokal dihapus
7. Jika OCR gagal → taruna diminta upload ulang
8. Admin melakukan verifikasi akhir (approve/reject)
```

### Alur Admin
```
1. Login melalui /admin
2. Pantau semua dokumen di menu "Dokumen Taruna"
3. Klik "Verifikasi" pada dokumen
4. Lihat hasil OCR + link dokumen di Drive
5. Pilih Setujui / Tolak + isi catatan
6. Export laporan PDF dari menu "Laporan PDF"
```

---

## 🗄️ Struktur Database

| Tabel | Fungsi |
|---|---|
| `users` | Akun admin KAPALATI |
| `master_document_types` | Konfigurasi jenis dokumen (OCR keywords, mime types, dll) |
| `trb_registrations` | Data identitas taruna + edit token |
| `trb_documents` | Dokumen taruna + hasil OCR + status verifikasi |

---

## 🎨 Konfigurasi Custom Theme (Filament)

File theme ada di: `resources/css/filament/admin/theme.css`

Warna brand:
| Peran | Hex | Keterangan |
|---|---|---|
| Primary | `#1a1a7a` | Navy — dominan logo AMI |
| Warning | `#e0c800` | Gold — dari logo AMI |
| Danger | `#cc1a1a` | Merah |
| Success | `#1a7a3a` | Hijau |
| Info | `#1a5a9a` | Biru |

---

## 🔧 Konfigurasi Tambahan

### Linux/Ubuntu Deployment
Ganti path Tesseract di `ProcessDocumentOCR.php`:
```php
// Windows
$tesseractExec = '"C:\Program Files\Tesseract-OCR\tesseract.exe"';

// Linux
$tesseractExec = '/usr/bin/tesseract';
```

### Google Drive Setup
1. Buat project di [Google Cloud Console](https://console.cloud.google.com)
2. Enable Google Drive API
3. Buat OAuth2 credentials
4. Jalankan sekali untuk mendapatkan refresh token
5. Isi `.env` dengan credentials yang didapat

---

## 👥 Aktor Sistem

| Aktor | Akses |
|---|---|
| **Taruna** | Daftar, upload dokumen, edit identitas (via token, tanpa login) |
| **Admin KAPALATI** | Login Filament, verifikasi dokumen, regenerate token, export PDF |

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan penelitian/skripsi Politeknik Maritim AMI Makassar.
