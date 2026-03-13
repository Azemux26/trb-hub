🔍 TRB-HUB: Document Management System

Current Status: Phase 2 - Automated Validation & OCR Integration (Completed)

Aplikasi manajemen dokumen Taruna yang fokus pada integritas data, validasi dokumen otomatis, dan efisiensi pengelolaan dokumen melalui teknologi OCR dan penyimpanan cloud.

Sistem ini memanfaatkan Tesseract OCR, Imagick, dan integrasi Google Drive API untuk melakukan validasi dokumen secara otomatis sebelum disimpan ke cloud storage.

🛠 Tech Stack

Backend:

Laravel 12

PHP 8+

MySQL

Libraries & Tools:

Tesseract OCR

Imagick

Ghostscript

Google Drive API

Laravel Queue Worker

Admin Panel:

Filament

🚀 Latest Progress: Automated Document Validation

Saat ini aplikasi telah berhasil mengimplementasikan sistem validasi dokumen otomatis pada modul Upload Dokumen Taruna.

Sistem tidak hanya menyimpan file, tetapi juga membaca isi dokumen untuk memastikan keaslian dan kualitas scan.

✅ Fitur yang Sudah Berjalan

Zero-Disk OCR
Proses pembacaan teks dilakukan di memori (stdout) untuk menghindari limitasi izin akses folder pada Windows.

Precision Scoring
Implementasi ekstraksi data hOCR untuk mendapatkan skor akurasi asli dari mesin Tesseract.

Multi-Format Processing
Mendukung pemrosesan otomatis file PDF dan Image melalui integrasi Ghostscript dan Imagick.

Smart UI/UX Feedback
Antarmuka dinamis yang memberikan peringatan kepada Taruna jika hasil scan buram atau keyword tidak ditemukan.

Asynchronous Queue Processing
Proses OCR dan upload file dijalankan melalui Laravel Queue Worker untuk menjaga performa aplikasi tetap responsif.

Cloud Storage Integration
Dokumen yang lolos validasi akan diupload otomatis ke Google Drive dan file lokal akan dihapus untuk menghemat storage server.

🏗 System Architecture 
flowchart LR

A[Taruna User] --> B[TRB-HUB Laravel Application]

B --> C[Upload Document Module]
C --> D[Laravel Storage Temporary]

D --> E[Queue Worker]

E --> F[ProcessDocumentOCR Job]
F --> G[Tesseract OCR]

G --> H[OCR Validation]

H --> I{Validation Result}

I -->|Valid| J[UploadToGoogleDrive Job]
I -->|Failed| K[Admin Verification Queue]

J --> L[Google Drive Storage]

L --> M[Document Metadata Database]

K --> M

M --> N[Admin Panel Filament]

Diagram ini menjelaskan bagaimana sistem melakukan:

upload dokumen

proses OCR

validasi dokumen

penyimpanan ke Google Drive

🔎 OCR Processing Workflow
flowchart TD

A[Upload Document] --> B[Save File to Temporary Storage]

B --> C[Queue Job: ProcessDocumentOCR]

C --> D{File Type}

D -->|PDF| E[Convert First Page to PNG using Imagick]
D -->|Image| F[Use Image Directly]

E --> G[Tesseract OCR Scan]
F --> G

G --> H[Extract Text]

H --> I[Keyword Matching]

H --> J[Confidence Score Calculation]

I --> K{Validation Result}
J --> K

K -->|Valid| L[Dispatch UploadToGoogleDrive Job]

K -->|Failed| M[Mark as OCR Failed]

Workflow ini menunjukkan bagaimana sistem melakukan:

konversi dokumen

ekstraksi teks

keyword validation

confidence scoring

flowchart TD

A[Validated Document] --> B[Queue Job: UploadToGoogleDrive]

B --> C[Google OAuth Authentication]

C --> D[Generate Access Token using Refresh Token]

D --> E[Upload File to Google Drive Folder]

E --> F[Receive Google Drive File ID]

F --> G[Save File Metadata to Database]

G --> H[Delete Local File from Laravel Storage]

Setelah dokumen berhasil diupload ke Google Drive:

metadata file disimpan di database

file lokal dihapus untuk menghemat storage server

🛠️ Environment Setup (Required for This Progress)

Karena aplikasi ini menggunakan engine eksternal, setup berikut wajib dilakukan agar fitur validasi berjalan di lingkungan lokal.

1️⃣ Tesseract OCR Engine

Instalasi:

C:\Program Files\Tesseract-OCR\tesseract.exe

Language data:

ind
eng

Catatan: Wajib terdaftar di System PATH Windows.

2️⃣ Ghostscript & Imagick

Ghostscript digunakan untuk:

render PDF → image

Imagick digunakan untuk:

convert PDF → PNG (200 DPI)

Aktifkan extension Imagick di php.ini:

extension=imagick
3️⃣ Jalankan Queue Worker

Queue worker harus berjalan agar proses OCR dapat diproses.

php artisan queue:work
📂 Technical Workflow (Backend)

Bagaimana sistem memvalidasi dokumen Taruna saat ini:

1️⃣ Trigger

ProcessDocumentOCR dijalankan melalui Queue Job setelah file masuk ke storage.

2️⃣ Imaging

Mengonversi PDF halaman pertama menjadi PNG 200 DPI menggunakan Imagick.

3️⃣ Double-Scan

Scan 1
Mengambil teks mentah untuk verifikasi kata kunci.

Scan 2
Mengambil metadata x_wconf untuk kalkulasi skor kepercayaan rata-rata.

4️⃣ Validation

Jika:

confidence < 70%
atau
keyword tidak cocok

maka status dokumen ditandai sebagai:

failed
⚠️ Known Issues & Solutions (Windows Environment)
Problem

Error "No Output" saat menggunakan library wrapper OCR.

Solution

Menggunakan perintah langsung:

shell_exec

dengan parameter stdout sehingga tidak bergantung pada folder temporary Windows.

📅 Road Map Next Step
✅ Completed

 Integrasi OCR Tesseract & Confidence Scoring

 UI/UX Dashboard Upload Taruna

 Integrasi Google Drive API

 Queue Processing untuk OCR & Upload

 Upload otomatis dokumen ke Google Drive

🚧 Next Development

 Modul Verifikasi Admin (Approval Workflow)

 Dashboard Admin menggunakan Filament

 Status dokumen (OCR Failed / Waiting Verification / Approved)

📌 Planned Features

 Export Laporan Validasi Dokumen (PDF / Spreadsheet)

 Notifikasi status dokumen kepada Taruna

 REST API untuk integrasi mobile

 Convert ke Android

👨‍💻 Lead Developer

Muhammad Rafli Adriansyah
IT Staff & Simulator Lab Technician
Politeknik Maritim AMI Makassar