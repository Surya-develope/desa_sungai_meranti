# 🏛️ Desa Sungai Meranti - Sistem Informasi Administrasi Desa

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistem informasi administrasi desa modern yang dirancang untuk Gemeinde Sungai Meranti, Kabupaten Bengkalis, Provinsi Riau. Platform digital terdepan untuk melayani kebutuhan administrasi surat dan dokumen masyarakat dengan sistem yang transparan, efisien, dan mudah diakses.

## 🌟 Fitur Utama

### 👥 Untuk Masyarakat
- **🔐 Sistem Autentikasi Aman**
  - Pendaftaran dan login dengan validasi email
  - Manajemen profil pengguna
  - Dashboard personal yang informatif

- **📄 Administrasi Surat Online**
  - Pengajuan berbagai jenis surat (SKCK, Surat Keterangan, dll)
  - Upload dokumen persyaratan
  - Tracking status pengajuan real-time
  - Riwayat lengkap pengajuan

- **📊 Dashboard Interaktif**
  - Statistik personal pengajuan
  - Status tracking surat
  - Notifikasi dan update terbaru

### 👨‍💼 Untuk Admin Desa
- **📈 Panel Kontrol Komprehensif**
  - Dashboard dengan statistik real-time
  - Manajemen pengajuan surat
  - Konfirmasi dan persetujuan dokumen

- **📋 Manajemen Data**
  - CRUD jenis surat
  - Manajemen pengguna dan权限
  - Laporan dan analitik

- **🔧 Sistem Konfigurasi**
  - Template surat otomatis
  - Pengaturan workflow approval
  - Parameter sistem yang fleksibel

### 🔍 Fitur Teknis
- **🎨 Interface Modern**
  - Responsive design untuk semua device
  - UI/UX yang intuitif dan user-friendly
  - Animasi dan transisi yang smooth

- **🔌 API RESTful**
  - Endpoint lengkap untuk integrasi
  - Dokumentasi API yang komprehensif
  - Authentication dengan Laravel Sanctum

- **📱 Progressive Web App**
  - Akses offline dengan service worker
  - Push notifications
  - Installable di device mobile

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 12.x** - Framework PHP modern
- **PHP 8.2+** - Bahasa pemrograman
- **MySQL/MariaDB** - Database relasional
- **Laravel Sanctum** - Authentication API
- **DomPDF & PhpWord** - Generator PDF & Word

### Frontend
- **Tailwind CSS 4.x** - Framework CSS utility-first
- **Alpine.js** - JavaScript framework ringan
- **Vite** - Build tool modern
- **Blade** - Template engine Laravel

### Development Tools
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework
- **Laravel Sail** - Docker development environment
- **Faker** - Data seeding

## 📋 Persyaratan Sistem

### Minimum Requirements
- **PHP**: 8.2 atau lebih tinggi
- **Composer**: 2.x
- **Node.js**: 18.x atau lebih tinggi
- **NPM**: 9.x atau lebih tinggi
- **MySQL**: 8.0 atau MariaDB 10.6
- **Web Server**: Apache/Nginx

### Ekstensi PHP yang Diperlukan
```bash
php-cli
php-fpm
php-mysql
php-xml
php-curl
php-zip
php-mbstring
php-bcmath
php-gd
php-fileinfo
```

## 🚀 Instalasi dan Setup

### 1. Clone Repository
```bash
git clone https://github.com/Joko206/desa_sungai_meranti.git
cd desa_sungai_meranti
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed database with initial data
php artisan db:seed
```

### 5. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 6. Start Development Server
```bash
# Using Laravel Artisan
php artisan serve

# Or using Laravel Sail (recommended)
./vendor/bin/sail up -d
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🔧 Konfigurasi Environment

Edit file `.env` untuk konfigurasi:

```env
APP_NAME="Desa Sungai Meranti"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desa_sungai_meranti
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

## 📁 Struktur Proyek

```
desa_sungai_meranti/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── PengajuanController.php
│   │   │   ├── AdminPengajuanController.php
│   │   │   └── JenisSuratController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── UserDesa.php
│   │   ├── PengajuanSurat.php
│   │   ├── JenisSurat.php
│   │   └── SuratTerbit.php
│   └── Services/
│       └── SuratGeneratorService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layout/
│   │   ├── auth/
│   │   └── warga/
│   └── js/
├── routes/
│   ├── web.php
│   └── api.php
├── public/
│   ├── logo-desa.png
│   └── Desa-teluk-Meranti-1.jpg
└── tests/
```

## 🌐 API Documentation

### Authentication Endpoints
```
POST   /api/login          # Login user
POST   /api/register       # Register new user
POST   /api/logout         # Logout user
GET    /api/user           # Get current user
```

### Document Management
```
GET    /api/jenis-surat     # Get all document types
GET    /api/jenis-surat/{id} # Get specific document type
GET    /api/jenis-surat/{id}/placeholders # Get form placeholders
```

### Application Management
```
GET    /api/pengajuan       # Get user applications
POST   /api/pengajuan       # Create new application
GET    /api/pengajuan/{id}  # Get specific application
PUT    /api/pengajuan/{id}  # Update application
DELETE /api/pengajuan/{id}  # Delete application
```

### Tracking
```
GET    /api/tracking/{tracking_code} # Track application status
```

## 👤 Default User Accounts

Setelah menjalankan seeders, akun default yang tersedia:

### Admin Account
- **Email**: admin@desasungaimeranti.id
- **Password**: password123
- **Role**: Administrator

### Resident Account
- **Email**: warga@desasungaimeranti.id  
- **Password**: password123
- **Role**: Resident

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Feature/Api/ApiWorkflowTest.php
```

## 🚀 Deployment

### Production Setup
```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

### Using Laravel Sail (Docker)
```bash
# Build and start containers
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate

# Build assets
./vendor/bin/sail npm run build
```

## 🤝 Contributing

Kami sangat menghargai kontribusi dari komunitas! Berikut panduan berkontribusi:

1. **Fork** repository ini
2. Buat **feature branch** (`git checkout -b feature/AmazingFeature`)
3. **Commit** perubahan Anda (`git commit -m 'Add some AmazingFeature'`)
4. **Push** ke branch (`git push origin feature/AmazingFeature`)
5. Buka **Pull Request**

### Coding Standards
- Ikuti [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
- Gunakan PHPStan untuk static analysis
- Pastikan semua test passing
- Tulis dokumentasi untuk fitur baru

## 📝 Changelog

### v2.0.0 (2025-10-29)
- ✅ Implementasi logo clickable di semua layout
- ✅ Optimasi tampilan jenis surat tanpa foto
- ✅ Peningkatan responsivitas UI/UX
- ✅ Perbaikan sistem tracking real-time
- ✅ Optimasi performa dan loading speed

### v1.0.0 (2025-10-26)
- 🎉 Initial release
- ✅ Sistem autentikasi lengkap
- ✅ Dashboard untuk warga dan admin
- ✅ Sistem pengajuan surat online
- ✅ API RESTful
- ✅ Dokumentasi komprehensif

## 📞 Support & Contact

Tim Pengembangan Desa Sungai Meranti

- **Email**: dev@desasungaimeranti.id
- **Website**: [https://desasungaimeranti.id](https://desasungaimeranti.id)
- **GitHub**: [Joko206/desa_sungai_meranti](https://github.com/Joko206/desa_sungai_meranti)

## 📄 License

Proyek ini dilisensikan di bawah MIT License - lihat file [LICENSE](LICENSE) untuk detail lengkap.

## 🙏 Acknowledgments

- **Laravel Team** - Untuk framework yang luar biasa
- **Tailwind CSS** - Untuk utility-first CSS framework
- **Alpine.js** - Untuk JavaScript framework yang ringan
- ** Komunitas PHP Indonesia** - Untuk inspirasi dan support
- **Masyarakat Desa Sungai Meranti** - Untuk trust dan kesempatan

---

<div align="center">

**Dibuat dengan ❤️ untuk masyarakat Indonesia**

[🇮🇩 Desa Sungai Meranti | Kabupaten Bengkalis | Provinsi Riau 🇮🇩](https://desasungaimeranti.id)

</div>
