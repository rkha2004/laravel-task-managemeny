# TaskFlow — Aplikasi Pengelolaan Task (Laravel)

> **Laravel Junior Programmer — Practical Test Submission**  
> Proyek aplikasi sederhana untuk mengelola task menggunakan **Laravel 12**, **PHP 8.3**, **Tailwind CSS**, dan **SQLite**.

---

## Daftar Isi
1. [Tentang Aplikasi](#-tentang-aplikasi)
2. [Fitur-Fitur Utama](#-fitur-fitur-utama)
3. [Inisiatif & Fitur Tambahan](#-inisiatif--fitur-tambahan)
4. [Prasyarat Sistem](#-prasyarat-sistem)
5. [Panduan Instalasi & Menjalankan](#-panduan-instalasi--menjalankan)
6. [Menjalankan Pengujian (Automated Testing)](#-menjalankan-pengujian-automated-testing)
7. [Dokumentasi Submission](#-dokumentasi-submission)

---

## Tentang Aplikasi

Aplikasi **TaskFlow** dibangun untuk memenuhi kebutuhan tes teknis posisi Laravel Junior Programmer. Aplikasi ini mengelola siklus hidup task secara menyeluruh dengan skema database `tasks`:
- `id` (Primary Key, Auto-increment)
- `title` (String, Wajib / Required)
- `description` (Text, Opsional / Nullable)
- `status` (Enum: `Pending`, `In Progress`, `Completed`)
- `due_date` (Date, Opsional / Nullable)
- `created_at` & `updated_at` (Timestamps)

---

## Fitur-Fitur Utama (CRUD)

- **Create**: Membuat task baru melalui form interaktif dengan validasi input yang ketat dan pesan error ramah pengguna.
- **Read**:
  - Daftar task (*index*) dengan tabel responsif, cuplikan deskripsi, status badge, dan paginasi.
  - Detail task (*show*) yang menampilkan seluruh informasi, riwayat waktu, serta opsi aksi lengkap.
- **Update**: Mengubah informasi judul, deskripsi, status, maupun tanggal jatuh tempo task.
- **Delete**: Menghapus task dengan konfirmasi dialog agar mencegah penghapusan yang tidak disengaja.
- **Penanganan 404 Not Found**: Halaman khusus yang ramah saat pengguna mengakses ID task yang tidak ada atau telah terhapus.

---

## Inisiatif & Fitur Tambahan

1. **Pencarian & Penyaringan (Search & Filter)**:
   - Pencarian berbasis kata kunci (*full-text search*) pada judul dan deskripsi.
   - Filter cepat berdasarkan status (`Pending`, `In Progress`, `Completed`).
   - Filter task yang terlambat (*overdue*).
   - Pengurutan data (*sorting*): Terbaru, Terlama, Tenggat Waktu Terdekat/Terjauh, dan Judul A-Z.
2. **Indikator Visual Tenggat Waktu (Overdue & Due Today)**:
   - Badge peringatan merah dan banner khusus jika task belum selesai dan melewati tanggal jatuh tempo.
   - Badge penanda oranye jika task jatuh tempo pada hari ini (*Due Today*).
3. **Kartu Statistik Ringkasan (Dashboard Metrics)**:
   - Menghitung total task, jumlah task pending, in progress, completed, dan overdue secara real-time.
   - Setiap kartu dapat diklik untuk menyaring daftar task secara instan.
4. **Quick Status Change**:
   - Kemudahan mengubah status task langsung dari tabel daftar tanpa harus masuk ke halaman edit.
5. **Automated Testing Suite (PHPUnit)**:
   - 20 unit/feature tests mencakup seluruh skenario validasi, CRUD, pencarian, filter, dan error handling (100% lulus).
6. **Database Seeder & Factory**:
   - `TaskFactory` dan `TaskSeeder` siap pakai dengan 10+ data contoh realistis.

---

## Prasyarat Sistem

- **PHP**: >= 8.2 (Direkomendasikan PHP 8.3)
- **Composer**: >= 2.0
- **Node.js**: >= 20.x & **NPM** (untuk asset bundling)
- **Ekstensi PHP**: `pdo_sqlite`, `mbstring`, `openssl`, `curl`

---

## Panduan Instalasi & Menjalankan

Ikuti langkah-langkah mudah berikut untuk menjalankan proyek di mesin lokal Anda:

### 1. Clone Repository
```bash
git clone <URL_REPOSITORY_ANDA>
cd <NAMA_FOLDER_PROJECT>
```

### 2. Instalasi Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`, lalu generate application key:
```bash
cp .env.example .env
php artisan key:generate
```

> **Catatan Database:**  
> Aplikasi secara default menggunakan **SQLite** (`database/database.sqlite`), sehingga **tidak memerlukan konfigurasi database server tambahan (zero-config)**. Jika file database belum ada, Laravel akan otomatis membuatnya.

### 4. Jalankan Migrasi & Database Seeder
Jalankan perintah berikut untuk membuat tabel dan mengisi data contoh:
```bash
php artisan migrate --seed
```

### 5. Instalasi & Build Aset Frontend
```bash
npm install --legacy-peer-deps
npm run build
```
*(Atau jalankan `npm run dev` jika ingin menjalankan Vite development server).*

### 6. Jalankan Server Lokal Laravel
```bash
php artisan serve
```
Buka browser dan akses aplikasi melalui: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## Menjalankan Pengujian (Automated Testing)

Untuk menjalankan seluruh rangkaian automated feature tests:

```bash
php artisan test
```

Atau untuk menjalankan pengujian khusus fitur Task:

```bash
php artisan test --filter=TaskFeatureTest
```

---

## Dokumentasi Submission

Detail lengkap mengenai evaluasi pengerjaan, keputusan teknis, kendala dan perbaikan, penggunaan AI, serta langkah berikutnya dapat dibaca pada file **[`SUBMISSION.md`](SUBMISSION.md)**.

### Kolaborator Penguji
Sesuai instruksi tes, akses kolaborator telah disiapkan untuk diundang ke repository ini:  
`haycal@koweb-indonesia.com`
