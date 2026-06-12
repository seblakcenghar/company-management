# Company Management App

Aplikasi Laravel untuk mengelola data `companies` dan `employees` sesuai requirement technical test.

## Stack

- PHP 8.2+
- Laravel 12
- laravel/ui (auth + Bootstrap UI)
- MySQL
- Vite (asset bundling)

## Fitur Utama

- Autentikasi admin
- CRUD `companies` (Resource Controller)
- CRUD `employees` (Resource Controller)
- Pagination 5 data per halaman
- Validasi menggunakan Form Request classes
- Query CRUD dipisahkan dengan Repository Pattern
- Upload logo company ke `storage/app/company`

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

Atur koneksi database pada `.env`, lalu jalankan:

Contoh konfigurasi database MySQL di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=company_management
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan database sudah dibuat terlebih dahulu, kemudian jalankan migrasi dan seeder:

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

## Catatan Teknis

- Batas logo `max:2048` menggunakan satuan KB (2 MiB).
- Tampilan pagination disesuaikan ke Bootstrap dengan `Paginator::useBootstrapFive()`.

## Testing Bagian 2

Jika reviewer ingin mengecek implementasi Task Laravel Bagian 2, pindah ke branch berikut:

```bash
git fetch origin
git checkout Bagian-2-Laravel-Dasar-2
git pull
```
