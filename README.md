# Company Management App

Aplikasi Laravel untuk mengelola data `companies` dan `employees` sesuai requirement technical test.

## Stack

- PHP 8.2+
- Laravel 12
- laravel/ui (auth + Bootstrap UI)
- MySQL/PostgreSQL/SQLite
- Vite (asset bundling)

## Fitur Utama

- Autentikasi admin
- CRUD `companies` (Resource Controller)
- CRUD `employees` (Resource Controller)
- Pagination 5 data per halaman
- Validasi menggunakan Form Request classes
- Query CRUD dipisahkan dengan Repository Pattern
- Upload logo company ke `storage/app/company`
- Export PDF data employees per company (laravel-snappy)
- Import Excel employees minimal 100 records (chunk insert 10 data)
- Select2 AJAX + pagination untuk dropdown company pada form create employee

## Akun Admin Seed

- Email: `admin@transisi.id`
- Password: `transisi`

## Quick Start (Untuk Reviewer)

Jalankan dari root project:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Install dependency sistem untuk PDF export (wkhtmltopdf), contoh di Ubuntu/Debian:

```bash
sudo apt-get update
sudo apt-get install -y wkhtmltopdf
```

Atur koneksi database pada `.env`, lalu jalankan:

```bash
php artisan migrate --seed
npm install
```

Jalankan aplikasi:

```bash
php artisan serve
npm run dev
```

Buka URL aplikasi (default: `http://127.0.0.1:8000`) dan login dengan akun admin seed.

## Verifikasi Cepat

- Cek route: `php artisan route:list`
- Cek seeder admin: `php artisan db:seed --class=AdminUserSeeder`

## Verifikasi Fitur Bagian 2

1. Export PDF employees per company
	- Login, buka menu Employees.
	- Gunakan panel `Export Employees (PDF)`.
	- Pilih company, lalu klik `Export PDF`.

2. Import Excel employees (minimum 100 records)
	- Pastikan sudah ada minimal 1 company.
	- Buka menu Employees.
	- Gunakan panel `Import Employees (Excel)`.
	- Pilih company tujuan import.
	- Upload file sample: `public/samples/employees_import_100.xlsx`.

3. Select2 AJAX pada create employee
	- Buka menu Employees > Add Employee.
	- Field Company menggunakan Select2 dengan request AJAX dan pagination.

## Catatan Teknis

- Batas logo `max:2048` menggunakan satuan KB (2 MiB).
- Tampilan pagination disesuaikan ke Bootstrap dengan `Paginator::useBootstrapFive()`.
- Konfigurasi binary PDF dapat diatur via `.env`:
	- `WKHTML_PDF_BINARY`
	- `WKHTML_IMG_BINARY`
- Jika muncul error `The binary 'wkhtmltopdf' is not executable`, pasang binary sistem:
	- Ubuntu/Debian: `sudo apt-get install -y wkhtmltopdf`
	- lalu set `.env` ke path binary yang benar (contoh `/usr/bin/wkhtmltopdf`) dan jalankan `php artisan optimize:clear`.
