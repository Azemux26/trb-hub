# 🔍 TRB-HUB: Document Management System
> **Current Status:** Phase 2 - Automated Validation & OCR Integration (Completed)

Aplikasi manajemen dokumen Taruna yang fokus pada integritas data dan verifikasi otomatis menggunakan **Tesseract OCR**.

---

## 🚀 Latest Progress: Automated Document Validation
Saat ini, aplikasi telah berhasil mengimplementasikan sistem validasi otomatis pada modul **Upload Dokumen Taruna**. Sistem tidak hanya menyimpan file, tetapi juga "membaca" isi dokumen untuk memastikan keasliannya.

### ✅ Fitur yang Sudah Berjalan:
- **Zero-Disk OCR**: Proses pembacaan teks dilakukan di memori (stdout) untuk menghindari limitasi izin akses folder pada Windows.
- **Precision Scoring**: Implementasi ekstraksi data `hOCR` untuk mendapatkan skor akurasi asli dari mesin Tesseract (bukan angka statis).
- **Multi-Format Processing**: Mendukung pemrosesan otomatis file PDF melalui integrasi Ghostscript dan Imagick.
- **Smart UI/UX Feedback**: Antarmuka dinamis yang memberikan peringatan kepada Taruna jika hasil scan buram atau keyword tidak ditemukan.

---

## 🛠️ Environment Setup (Required for This Progress)
Karena aplikasi ini sudah menggunakan engine eksternal, setup berikut **wajib** dilakukan agar fitur validasi berjalan di lingkungan lokal:

1. **Tesseract OCR Engine**
   - Instalasi: `C:\Program Files\Tesseract-OCR\tesseract.exe`
   - Language Data: `ind` (Indonesian) & `eng` (English).
   - *Catatan: Wajib terdaftar di System PATH Windows.*

2. **Ghostscript & Imagick**
   - Ghostscript diperlukan untuk render PDF.
   - PHP Extension `imagick` harus aktif di `php.ini`.

---

## 📂 Technical Workflow (Backend)
Bagaimana sistem memvalidasi dokumen Taruna saat ini:
1. **Trigger**: `ProcessDocumentOCR` dijalankan via Queue Job setelah file masuk ke storage.
2. **Imaging**: Mengonversi PDF halaman pertama menjadi PNG 200 DPI menggunakan Imagick.
3. **Double-Scan**: 
   - Scan 1: Mengambil teks mentah untuk verifikasi kata kunci (Keyword Matching).
   - Scan 2: Mengambil metadata `x_wconf` untuk kalkulasi skor kepercayaan rata-rata.
4. **Validation**: Jika skor < 70% atau keyword tidak cocok, sistem menandai status sebagai `failed`.

---

## ⚠️ Known Issues & Solutions (Windows Environment)
- **Problem**: Error "No Output" saat menggunakan library wrapper.
- **Solution**: Dialihkan menggunakan perintah direct `shell_exec` dengan parameter `stdout` untuk mematikan ketergantungan pada folder temporary Windows.

---

## 📅 Road Map Next Step
- [x] Integrasi OCR Tesseract & Confidence Scoring.
- [x] UI/UX Dashboard Upload Taruna.
- [x] Integrasi Google Drive API.
- [ ] Modul Verifikasi Admin (Approval Workflow).
- [ ] Export Laporan Validasi Dokumen (PDF/Excel).
- [ ] Convert ke Android.

---
**Lead Developer:** **Muhammad Rafli Adriansyah** *IT Staff & Simulator Lab Technician - Polimarim AMI Makassar*