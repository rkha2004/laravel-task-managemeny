<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource with optional filters, search, and statistics.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->query('search', $request->query('q')),
            'status' => $request->query('status'),
            'overdue' => $request->query('overdue'),
            'sort' => $request->query('sort', 'latest'),
        ];

        // Summary statistics for dashboard cards
        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', TaskStatus::Pending->value)->count(),
            'in_progress' => Task::where('status', TaskStatus::InProgress->value)->count(),
            'completed' => Task::where('status', TaskStatus::Completed->value)->count(),
            'overdue' => Task::overdue()->count(),
        ];

        $tasks = Task::filter($filters)
            ->paginate(10)
            ->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'filters' => $filters,
            'stats' => $stats,
            'statuses' => TaskStatus::cases(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tasks.create', [
            'statuses' => TaskStatus::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = Task::create($request->validated());

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Task baru berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', [
            'task' => $task,
            'statuses' => TaskStatus::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Perubahan task berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $taskTitle = $task->title;
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', "Task \"{$taskTitle}\" berhasil dihapus.");
    }

    /**
     * Quick update of task status from task list.
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(TaskStatus::class)],
        ]);

        $task->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status task berhasil diperbarui.');
    }
}
