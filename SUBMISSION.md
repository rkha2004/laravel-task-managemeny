# Submission — Laravel Junior Programmer Practical Test

Dokumen ini berisi penjelasan singkat mengenai hasil pengerjaan tes praktikal pengelolaan task menggunakan framework Laravel.

---

## 1. Fitur Selesai

- [x] **Database & Skema Data**:
  - Tabel `tasks` dengan seluruh field yang dipersyaratkan (`id`, `title`, `description`, `status`, `due_date`, `created_at`, `updated_at`).
  - Indexing pada kolom pencarian dan filtering (`title`, `status`, `due_date`).
- [x] **Model & Enum**:
  - PHP 8.1+ Backed Enum `App\Enums\TaskStatus` (`Pending`, `In Progress`, `Completed`) dengan helper badge & label.
  - Model `App\Models\Task` dengan attribute casting, query scope `scopeFilter`, `scopeOverdue`, serta helper method `isOverdue()` dan `isDueToday()`.
- [x] **CRUD Penuh (Create, Read, Update, Delete)**:
  - **Create**: Form pembuatan task baru dengan validasi dan penanganan input otomatis.
  - **Read**: Tampilan daftar task terpaginasi (*index*) serta tampilan detail task komprehensif (*show*).
  - **Update**: Form edit informasi task dengan prefilled values yang aman (*update*).
  - **Delete**: Penghapusan task dengan konfirmasi pengguna (*destroy*).
- [x] **Validasi Input & Penanganan Error**:
  - Implementasi dedicated Form Request (`StoreTaskRequest` & `UpdateTaskRequest`).
  - Aturan validasi: `title` wajib (maks 255), `description` opsional (nullable), `status` hanya enum valid, `due_date` opsional dengan format tanggal valid.
  - Pesan error dalam Bahasa Indonesia yang informatif dan ramah pengguna.
  - Penanganan kondisi ketika task tidak ditemukan (*404 Not Found*) melalui view `resources/views/errors/404.blade.php`.
- [x] **Inisiatif Tambahan**:
  - **Pencarian & Filter**: Pencarian kata kunci pada judul/deskripsi, filter berdasarkan status, filter task overdue, serta fitur sorting (terbaru, terlama, tenggat waktu terdekat/terjauh, judul A-Z).
  - **Visual Overdue & Due Today Indicator**: Penanda badge merah dan banner informatif jika task melewati batas tanggal dan belum berstatus *Completed*.
  - **Kartu Statistik Ringkasan**: Ringkasan jumlah task per status yang interaktif untuk filter instan.
  - **Quick Status Toggle**: Kemudahan mengubah status task langsung dari tabel daftar.
  - **Automated Testing Suite**: 20 feature & unit tests menggunakan PHPUnit yang menguji alur CRUD, validasi, pencarian, filter, dan respon 404 (100% passed).
  - **Database Seeder & Factory**: `TaskFactory` dan `TaskSeeder` siap pakai untuk pengujian instan.
  - **UI/UX Modern**: Desain responsif menggunakan Tailwind CSS dengan fallback styling dan font modern (Plus Jakarta Sans).

---

## 2. Fitur Belum Selesai

- [ ] **Autentikasi Multi-User**: Saat ini aplikasi berjalan dalam mode *single-tenant* (semua pengguna melihat task yang sama), belum dipisahkan per user account (`user_id` foreign key).
- [ ] **Kategori atau Label / Tag**: Pengelompokan task berdasarkan kategori tertentu (misal: "Bug", "Fitur", "Urgent").
- [ ] **Export Data**: Ekspor daftar task ke dalam format CSV atau PDF.

---

## 3. Keputusan Teknis

1. **Pemilihan SQLite sebagai Default Database**:
   - Dipilih agar penguji atau evaluator dapat langsung menjalankan aplikasi (`zero-config`) tanpa perlu menyetel MySQL server lokal atau membuat kredensial baru.
   - Tetap 100% kompatibel jika ingin dialihkan ke MySQL / PostgreSQL via `.env`.
2. **Penggunaan Backed Enum (`TaskStatus`)**:
   - Memastikan tipe data status strictly-typed dan konsisten di seluruh layer (Database, Model, Controller, Form Request, dan Blade View), menghindari *magic strings*.
3. **Pemisahan Logika Validasi ke Form Request**:
   - Menjaga *Single Responsibility Principle* pada `TaskController` sehingga controller tetap ramping (*skinny controller*) dan fokus pada orkestrasi respon.
4. **Query Scopes (`scopeFilter` dan `scopeOverdue`)**:
   - Memusatkan logika query filtering dan overdue di dalam Model `Task`, sehingga kode controller lebih bersih dan logika query dapat digunakan kembali di tempat lain maupun diuji secara terisolasi.
5. **Asset Bundling dengan Tailwind CSS & Fallback**:
   - Menggunakan Vite untuk kompilasi CSS Tailwind v4, namun menambahkan fallback CDN di template master agar tampilan tetap rapi bahkan jika penguji membuka aplikasi tanpa menjalankan proses build npm terlebih dahulu.

---

## 4. Kendala dan Perbaikan

1. **Kendala**: Masalah dependency resolution peer npm (`Cannot read properties of null reading edgesOut` dan missing binary `@rolldown/binding-win32-x64-msvc`) pada versi Node.js lokal saat instalasi paket frontend.
   - **Perbaikan**: Menggunakan flag `--legacy-peer-deps` saat `npm install` dan menginstal binding arsitektur Windows secara eksplisit, lalu menjalankan `npm run build` sehingga seluruh asset terkompilasi dengan sukses.
2. **Kendala**: Paginasi query string hilang ketika berpindah halaman saat sedang menerapkan filter status atau pencarian.
   - **Perbaikan**: Menggunakan method `->withQueryString()` pada pemanggilan `Task::filter(...)->paginate(10)` di `TaskController` sehingga parameter filter tetap dipertahankan saat navigasi halaman.
3. **Kendala**: Pada pengujian awal, tabel database `tasks` belum termigrasi pada pengujian bawaan framework `ExampleTest`.
   - **Perbaikan**: Menambahkan trait `RefreshDatabase` pada `ExampleTest` sehingga database in-memory selalu bersih dan termigrasi secara otomatis saat seluruh suite dijalankan.

---

## 5. Penggunaan AI

- **Bagian yang Dibantu AI**:
  - Membantu *scaffolding* boilerplate struktur awal Laravel dan perancangan variasi dataset pada `TaskFactory` dan `TaskSeeder`.
  - Memberikan saran pola implementasi query scope dan styling kelas Tailwind untuk UI badge.
  - Membantu penyusunan skenario automated feature tests.
- **Pemeriksaan & Penyesuaian Mandiri**:
  - Mengevaluasi dan memastikan skema migration tabel `tasks` strictly sesuai spesifikasi soal test (tipe kolom, nullability, dan enum value).
  - Menyesuaikan aturan validasi pada Form Request agar memberikan pesan error dalam Bahasa Indonesia yang informatif.
  - Mengoreksi penanganan parameter HTTP request pada filter pencarian dan sorting.
  - Menjalankan dan memverifikasi seluruh automated test suite (20 tests) dan code style menggunakan Laravel Pint (`vendor/bin/pint --format agent`).

---

## 6. Langkah Berikutnya (Jika Diberikan Waktu Tambahan)

1. **Autentikasi & Otorisasi Pengguna (Laravel Breeze / Auth)**:
   - Menambahkan relasi `User hasMany Tasks` dan Policy authorization sehingga pengguna hanya dapat mengelola task miliknya sendiri.
2. **Prioritas Task (Task Priority)**:
   - Menambahkan field priority (`Low`, `Medium`, `High`, `Urgent`) dengan pengurutan otomatis berdasarkan bobot kepentingan.
3. **Drag-and-Drop Kanban Board**:
   - Menyediakan tampilan Kanban Board (kolom Pending, In Progress, Completed) dengan interaksi drag-and-drop menggunakan Livewire atau Alpine.js.
4. **Notifikasi Email / Webhook Pengingat**:
   - Mengirim notifikasi otomatis saat task mendekati tanggal jatuh tempo (*due date reminder*) melalui Laravel Scheduled Commands / Cron Job.
