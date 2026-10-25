<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index page displays list of tasks.
     */
    public function test_user_can_view_tasks_index_page_with_list_of_tasks(): void
    {
        $task = Task::factory()->create([
            'title' => 'Implementasi Unit Testing',
            'status' => TaskStatus::Pending,
        ]);

        $response = $this->get(route('tasks.index'));

        $response->assertOk();
        $response->assertViewIs('tasks.index');
        $response->assertSee('Implementasi Unit Testing');
        $response->assertSee('Daftar Pengelolaan Task');
    }

    /**
     * Test root url redirects/routes to tasks index.
     */
    public function test_root_url_loads_tasks_index(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('tasks.index');
    }

    /**
     * Test create task form page loads successfully.
     */
    public function test_user_can_view_create_task_form(): void
    {
        $response = $this->get(route('tasks.create'));

        $response->assertOk();
        $response->assertViewIs('tasks.create');
        $response->assertSee('Buat Task Baru');
    }

    /**
     * Test creating a task with all valid data.
     */
    public function test_user_can_create_task_with_valid_data(): void
    {
        $payload = [
            'title' => 'Mengerjakan Modul Pembayaran',
            'description' => 'Integrasi payment gateway Midtrans dan webhook.',
            'status' => TaskStatus::InProgress->value,
            'due_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->post(route('tasks.store'), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Mengerjakan Modul Pembayaran',
            'status' => TaskStatus::InProgress->value,
        ]);

        $task = Task::where('title', 'Mengerjakan Modul Pembayaran')->first();
        $this->assertNotNull($task);
        $response->assertRedirect(route('tasks.show', $task));
    }

    /**
     * Test creating a task without optional fields (description and due date).
     */
    public function test_user_can_create_task_without_optional_fields(): void
    {
        $payload = [
            'title' => 'Task Sederhana Tanpa Deskripsi',
            'description' => null,
            'status' => TaskStatus::Pending->value,
            'due_date' => null,
        ];

        $response = $this->post(route('tasks.store'), $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tasks', [
            'title' => 'Task Sederhana Tanpa Deskripsi',
            'description' => null,
            'due_date' => null,
            'status' => TaskStatus::Pending->value,
        ]);
    }

    /**
     * Test validation fails when title is missing.
     */
    public function test_cannot_create_task_without_title(): void
    {
        $payload = [
            'title' => '',
            'status' => TaskStatus::Pending->value,
        ];

        $response = $this->post(route('tasks.store'), $payload);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('tasks', 0);
    }

    /**
     * Test validation fails when status is invalid.
     */
    public function test_cannot_create_task_with_invalid_status(): void
    {
        $payload = [
            'title' => 'Valid Title',
            'status' => 'Archived', // Not in ['Pending', 'In Progress', 'Completed']
        ];

        $response = $this->post(route('tasks.store'), $payload);

        $response->assertSessionHasErrors(['status']);
        $this->assertDatabaseCount('tasks', 0);
    }

    /**
     * Test validation fails when due date format is invalid.
     */
    public function test_cannot_create_task_with_invalid_due_date(): void
    {
        $payload = [
            'title' => 'Valid Title',
            'status' => TaskStatus::Pending->value,
            'due_date' => 'invalid-date-string',
        ];

        $response = $this->post(route('tasks.store'), $payload);

        $response->assertSessionHasErrors(['due_date']);
        $this->assertDatabaseCount('tasks', 0);
    }

    /**
     * Test user can view task details.
     */
    public function test_user_can_view_task_detail(): void
    {
        $task = Task::factory()->create([
            'title' => 'Spesifik Task Detail',
            'description' => 'Rincian deskripsi task yang sangat mendalam.',
            'status' => TaskStatus::InProgress,
        ]);

        $response = $this->get(route('tasks.show', $task));

        $response->assertOk();
        $response->assertViewIs('tasks.show');
        $response->assertSee('Spesifik Task Detail');
        $response->assertSee('Rincian deskripsi task yang sangat mendalam.');
        $response->assertSee('#'.$task->id);
    }

    /**
     * Test application gracefully returns 404 when task is not found.
     */
    public function test_returns_404_when_task_not_found(): void
    {
        $response = $this->get('/tasks/999999');

        $response->assertNotFound();
        $response->assertSee('Task Tidak Ditemukan');
    }

    /**
     * Test user can view edit task form with existing data.
     */
    public function test_user_can_view_edit_task_form(): void
    {
        $task = Task::factory()->create([
            'title' => 'Judul Sebelum Diedit',
        ]);

        $response = $this->get(route('tasks.edit', $task));

        $response->assertOk();
        $response->assertViewIs('tasks.edit');
        $response->assertSee('Judul Sebelum Diedit');
    }

    /**
     * Test user can update task with valid data.
     */
    public function test_user_can_update_task_with_valid_data(): void
    {
        $task = Task::factory()->create([
            'title' => 'Judul Awal',
            'status' => TaskStatus::Pending,
        ]);

        $payload = [
            'title' => 'Judul Setelah Diperbarui',
            'description' => 'Deskripsi baru.',
            'status' => TaskStatus::Completed->value,
            'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ];

        $response = $this->put(route('tasks.update', $task), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $response->assertRedirect(route('tasks.show', $task));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Judul Setelah Diperbarui',
            'status' => TaskStatus::Completed->value,
        ]);
    }

    /**
     * Test update validation fails with invalid data.
     */
    public function test_cannot_update_task_with_empty_title(): void
    {
        $task = Task::factory()->create([
            'title' => 'Judul Awal',
        ]);

        $response = $this->put(route('tasks.update', $task), [
            'title' => '',
            'status' => TaskStatus::Pending->value,
        ]);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Judul Awal',
        ]);
    }

    /**
     * Test user can delete a task.
     */
    public function test_user_can_delete_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Task yang Akan Dihapus',
        ]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertSessionHas('success');
        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test status filtering on index page.
     */
    public function test_can_filter_tasks_by_status(): void
    {
        $pendingTask = Task::factory()->create([
            'title' => 'Task Antrean Pending',
            'status' => TaskStatus::Pending,
        ]);

        $completedTask = Task::factory()->create([
            'title' => 'Task Sudah Selesai',
            'status' => TaskStatus::Completed,
        ]);

        $response = $this->get(route('tasks.index', ['status' => 'Pending']));

        $response->assertOk();
        $response->assertSee('Task Antrean Pending');
        $response->assertDontSee('Task Sudah Selesai');
    }

    /**
     * Test keyword searching by title or description.
     */
    public function test_can_search_tasks_by_keyword(): void
    {
        $taskMatch = Task::factory()->create([
            'title' => 'Optimasi Performa Query Database',
            'description' => 'Menambahkan index pada tabel tasks.',
        ]);

        $taskOther = Task::factory()->create([
            'title' => 'Desain Banner Promosi',
            'description' => 'Menggambar asset banner media sosial.',
        ]);

        $response = $this->get(route('tasks.index', ['search' => 'Optimasi']));

        $response->assertOk();
        $response->assertSee('Optimasi Performa Query Database');
        $response->assertDontSee('Desain Banner Promosi');
    }

    /**
     * Test overdue filtering returns only uncompleted tasks past due date.
     */
    public function test_can_filter_overdue_tasks(): void
    {
        $overdueTask = Task::factory()->create([
            'title' => 'Task Mendesak Terlambat',
            'status' => TaskStatus::Pending,
            'due_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
        ]);

        $completedPastTask = Task::factory()->create([
            'title' => 'Task Masa Lalu Tapi Sudah Selesai',
            'status' => TaskStatus::Completed,
            'due_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
        ]);

        $futureTask = Task::factory()->create([
            'title' => 'Task Masa Depan',
            'status' => TaskStatus::Pending,
            'due_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
        ]);

        $response = $this->get(route('tasks.index', ['overdue' => '1']));

        $response->assertOk();
        $response->assertSee('Task Mendesak Terlambat');
        $response->assertDontSee('Task Masa Lalu Tapi Sudah Selesai');
        $response->assertDontSee('Task Masa Depan');
    }

    /**
     * Test quick status update via PATCH route.
     */
    public function test_can_update_status_via_quick_patch_route(): void
    {
        $task = Task::factory()->create([
            'status' => TaskStatus::Pending,
        ]);

        $response = $this->patch(route('tasks.status', $task), [
            'status' => TaskStatus::Completed->value,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatus::Completed->value,
        ]);
    }
}
