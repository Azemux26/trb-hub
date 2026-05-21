Sip. Untuk tahap awal freelance, menurutku **paling enak pakai 1 visual teaser saja**:

* **opsi terbaik:** 1 GIF pendek 5–10 detik
* **opsi aman:** 1 screenshot utama

Kenapa? Karena repo tetap terlihat **profesional, ringan, dan teknis**, tapi tetap ada bukti visual. Detail lengkap, banyak screenshot, dan penjelasan case study kamu arahkan ke **website portfolio** atau **kontak**. Itu paling pas buat positioning awal sebagai freelancer.

README kamu yang sekarang sudah punya fondasi teknis yang bagus: deskripsi sistem, stack, alur, struktur aplikasi, setup, dan changelog. Aku rapikan arahnya supaya lebih kuat untuk branding freelance tanpa membuang step-by-step cloning/install. 

Di bawah ini versi final yang siap tempel.

---

````md
# TRB-HUB — Document Verification & TRB Management System

> Web-based document submission and verification system for cadets, built to digitize the Training Record Book (TRB) workflow at Politeknik Maritim AMI Makassar.

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Filament](https://img.shields.io/badge/Filament-v5-orange)
![MySQL](https://img.shields.io/badge/Database-MySQL-informational)
![OCR](https://img.shields.io/badge/OCR-Tesseract-success)
![Queue](https://img.shields.io/badge/Queue-Laravel%20Queue-purple)

---

## Overview

TRB-HUB is a web application designed to simplify the submission, validation, and verification of cadet documents for the Training Record Book (TRB) process.

The system combines **automatic OCR-based document validation** with **manual admin verification**, creating a more structured and efficient workflow than traditional manual submission.

This project was developed as part of an academic/research use case at **Politeknik Maritim AMI Makassar**.

---

## The Problem

In a manual workflow, document submission often creates several issues:

- cadets submit files without a consistent validation process
- admins spend extra time checking document completeness and authenticity
- file management becomes difficult when documents are scattered across local storage or manual channels
- verification status is unclear for users
- manual checking slows down the overall process

A digital system was needed to make the process more traceable, scalable, and easier to manage.

---

## The Solution

TRB-HUB solves this by providing:

- **cadet registration flow** without requiring a full user login
- **document upload per document type**
- **automatic OCR validation** in the background using queue jobs
- **Google Drive integration** for cloud-based file storage
- **manual admin verification** for final approval or rejection
- **PDF reporting** for administrative recap
- **token-based edit access** so users can update their data securely within a limited period

This creates a hybrid workflow:

**automation for speed, admin review for control**

---

## Key Features

### Cadet Side
- registration form for identity data
- token-based edit system valid for 7 days
- upload documents by category
- real-time document status visibility
- re-upload flow when OCR validation fails

### Admin Side
- secure admin access via Filament panel
- monitor all submitted documents
- view OCR results and confidence data
- approve or reject documents with notes
- export PDF reports
- manage master document types and validation rules

### System Features
- OCR processing with **Tesseract OCR**
- background jobs using **Laravel Queue**
- file upload workflow to **Google Drive API**
- PDF rendering support with **Ghostscript + ImageMagick**
- reporting with **DomPDF**

---

## Preview

> This repository focuses on the technical implementation and setup.  
> A full visual walkthrough and case study are available in my portfolio.

<!-- Choose one of these:
1. Use a short GIF preview
2. Or use one main screenshot only
-->

### Option A — GIF Preview
![TRB-HUB Demo](docs/demo/trb-hub-preview.gif)

### Option B — Main Screenshot
<!-- ![TRB-HUB Preview](docs/screenshots/trb-hub-preview-main.png) -->

### Portfolio & Contact
- **Portfolio:** https://your-portfolio-link.com
- **Contact:** your-email@example.com
- **Private walkthrough / collaboration:** available on request

---

## Technical Highlights

This project is more than a CRUD app. It includes:

- **asynchronous document processing**
  - OCR validation is handled in the background using queue jobs
- **third-party integration**
  - Google Drive API for cloud storage
- **document intelligence**
  - OCR keyword validation and confidence checking
- **role-based workflow**
  - cadet submission flow + admin review flow
- **report generation**
  - PDF export for document recap
- **production-oriented architecture**
  - service layer, jobs, admin resources, and modular controllers

---

## Workflow

### Cadet Workflow
1. Cadet fills out the registration form
2. System generates an edit token
3. Cadet uploads required documents
4. Files are temporarily stored locally
5. OCR job runs in the background
6. If OCR passes, the file is uploaded to Google Drive
7. If OCR fails, the cadet is asked to re-upload
8. Admin performs final verification

### Admin Workflow
1. Admin logs into `/admin`
2. Reviews submitted documents
3. Checks OCR result and file link
4. Approves or rejects the document
5. Adds notes when necessary
6. Exports PDF reports when needed

---

## Architecture Snapshot

```bash
app/
├── Filament/
│   ├── Pages/
│   │   └── LaporanPdf.php
│   └── Resources/
│       ├── MasterDocumentTypes/
│       ├── TrbDocuments/
│       └── TrbRegistrations/
├── Http/
│   └── Controllers/
│       ├── Admin/
│       └── Taruna/
├── Jobs/
│   ├── ProcessDocumentOCR.php
│   └── UploadToGoogleDrive.php
├── Models/
├── Services/
````

### Important Modules

* `ProcessDocumentOCR.php` → handles automatic OCR validation
* `UploadToGoogleDrive.php` → uploads validated files to Google Drive
* `TarunaDocumentService.php` → document submission logic
* `TarunaRegistrationService.php` → registration and token flow
* `LaporanService.php` → report generation logic

---

## Tech Stack

| Layer            | Technology                |
| ---------------- | ------------------------- |
| Backend          | Laravel 12                |
| Admin Panel      | Filament v5               |
| Frontend         | Bootstrap 5.3             |
| Admin Styling    | Tailwind CSS v4           |
| Build Tool       | Vite v7                   |
| OCR Engine       | Tesseract OCR             |
| Image Processing | ImageMagick + PHP Imagick |
| PDF Rendering    | Ghostscript               |
| Cloud Storage    | Google Drive API (OAuth2) |
| PDF Export       | DomPDF                    |
| Queue            | Laravel Queue             |
| Database         | MySQL                     |
| Language         | PHP 8.2+                  |

---

## Why This Project Matters

This project demonstrates my ability to build:

* real-world administrative systems
* document workflow automation
* OCR-powered validation features
* queue-based background processing
* cloud storage integrations
* role-based verification dashboards

It reflects the kind of system often needed by schools, campuses, offices, and internal business operations.

---

## System Requirements

### Required Software

* PHP >= 8.2
  extensions: `imagick`, `gd`, `pdo_mysql`
* Composer
* Node.js & NPM
* MySQL
* **Tesseract OCR 64-bit**

  * install language data: `ind` and `eng`
  * add to system PATH
* **Ghostscript 64-bit**
* **ImageMagick**

  * enable `extension=imagick` in `php.ini`

---

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/Azemux26/trb-hub.git
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

### 4. Configure `.env`

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

### 5. Run Database Migration

```bash
php artisan migrate
```

### 6. Build Assets

```bash
npm run build
```

### 7. Create First Admin User

```bash
php artisan make:filament-user
```

---

## Running the Project

### Development

```bash
composer run dev
```

This command runs:

* `php artisan serve`
* `php artisan queue:listen --tries=1`
* `npm run dev`

### Production

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:work --tries=3 --daemon
```

---

## Database Structure

| Table                   | Purpose                                                 |
| ----------------------- | ------------------------------------------------------- |
| `users`                 | Admin account data                                      |
| `master_document_types` | Document type configuration                             |
| `trb_registrations`     | Cadet identity data and edit token                      |
| `trb_documents`         | Uploaded documents, OCR result, and verification status |

---

## Actors

| Actor     | Access                                                            |
| --------- | ----------------------------------------------------------------- |
| **Cadet** | register, upload documents, edit identity using token             |
| **Admin** | login to Filament, verify documents, regenerate token, export PDF |

---

## Additional Configuration

### Linux / Ubuntu Deployment

Update Tesseract path in `ProcessDocumentOCR.php`:

```php
// Windows
$tesseractExec = '"C:\Program Files\Tesseract-OCR\tesseract.exe"';

// Linux
$tesseractExec = '/usr/bin/tesseract';
```

### Google Drive Setup

1. create a project in Google Cloud Console
2. enable Google Drive API
3. create OAuth2 credentials
4. generate a refresh token
5. fill the `.env` file with the credentials

---

## Challenges I Solved

Some of the more interesting technical challenges in this project:

* converting uploaded PDF files into a format suitable for OCR
* running OCR processing asynchronously without blocking user flow
* separating temporary local storage from cloud storage flow
* designing a verification process that combines automation and manual review
* building admin tools that remain practical for real operational use

---

## Future Improvements

* dashboard analytics for document completion rates
* email or WhatsApp notifications
* better OCR rule customization per document type
* audit trail for admin verification activity
* multi-institution support

---

## Changelog

### v0.3 — 20 March 2026

* PDF report export with Google Drive links
* detection for local vs Drive file sources
* improved admin verification form
* rejection notes validation
* improved status badges and navigation labels
* real-time document status on cadet page

### v0.2 — 14 March 2026

* OCR integration with confidence score
* automatic upload to Google Drive after OCR validation
* custom Filament admin theme
* Laravel queue processing for OCR + Drive upload

### v0.1 — 17 February 2026

* initial system architecture and database design
* cadet registration with edit token
* document upload per type
* base Filament admin panel

---

## Author

**Rafli**
Backend Web Developer focused on Laravel-based information systems, document workflows, and admin dashboards.

* GitHub: [https://github.com/Azemux26](https://github.com/Azemux26)
* Portfolio: [https://your-portfolio-link.com](https://your-portfolio-link.com)
* Email: [your-email@example.com](mailto:your-email@example.com)

---

## License

This project was developed for academic/research purposes at Politeknik Maritim AMI Makassar.

````








