<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleTasks = [
            [
                'title' => 'Inisialisasi repository & konfigurasi environment Laravel',
                'description' => 'Setup project baru menggunakan Laravel 12, konfigurasi database SQLite, dan pengaturan environment dasar.',
                'status' => TaskStatus::Completed,
                'due_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
            ],
            [
                'title' => 'Perancangan skema database & migration tabel tasks',
                'description' => 'Membuat migration untuk tabel tasks dengan field id, title, description, status, due_date, dan timestamps.',
                'status' => TaskStatus::Completed,
                'due_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'title' => 'Implementasi model Task, enum TaskStatus, dan Form Request',
                'description' => 'Membuat Eloquent model dengan casting enum, method pembantu isOverdue, query scopeFilter, serta validasi request.',
                'status' => TaskStatus::InProgress,
                'due_date' => Carbon::today()->format('Y-m-d'),
            ],
            [
                'title' => 'Desain antarmuka Blade yang bersih dan modern',
                'description' => 'Membangun layout responsif dengan komponen navigasi, kartu statistik ringkasan, dan badge penanda status.',
                'status' => TaskStatus::InProgress,
                'due_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            ],
            [
                'title' => 'Fitur pencarian kata kunci dan filter berdasarkan status',
                'description' => 'Memungkinkan pengguna menyaring task berdasarkan status (Pending, In Progress, Completed) serta pencarian judul/deskripsi.',
                'status' => TaskStatus::Pending,
                'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            ],
            [
                'title' => 'Indikator visual task jatuh tempo (Overdue)',
                'description' => 'Menampilkan badge peringatan khusus ketika tanggal due date telah terlewat dan status task belum Completed.',
                'status' => TaskStatus::Pending,
                'due_date' => Carbon::now()->subDays(2)->format('Y-m-d'), // Overdue!
            ],
            [
                'title' => 'Perbaiki penanganan task tidak ditemukan (404 Not Found)',
                'description' => 'Tampilkan halaman 404 yang ramah ketika pengguna mengakses ID task yang tidak ada dalam database.',
                'status' => TaskStatus::Pending,
                'due_date' => Carbon::now()->subDays(1)->format('Y-m-d'), // Overdue!
            ],
            [
                'title' => 'Pengujian otomatis menggunakan PHPUnit feature tests',
                'description' => 'Menulis test suite lengkap untuk memastikan operasi CRUD, validasi input, dan filter berjalan tanpa regresi.',
                'status' => TaskStatus::Pending,
                'due_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            ],
            [
                'title' => 'Dokumentasi README.md dan file SUBMISSION.md',
                'description' => 'Menyusun petunjuk instalasi, instruksi menjalankan aplikasi, serta penjelasan keputusan teknis pengerjaan.',
                'status' => TaskStatus::Pending,
                'due_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            ],
            [
                'title' => 'Eksplorasi optimasi query dan formatting Pint',
                'description' => null,
                'status' => TaskStatus::Pending,
                'due_date' => null,
            ],
        ];

        foreach ($sampleTasks as $taskData) {
            Task::create($taskData);
        }
    }
}
