<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Carbon\Carbon;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'due_date' => 'date:Y-m-d',
        ];
    }

    /**
     * Determine if the task is overdue (past due date and not completed).
     */
    public function isOverdue(): bool
    {
        if ($this->status === TaskStatus::Completed || ! $this->due_date) {
            return false;
        }

        return $this->due_date->lt(Carbon::today());
    }

    /**
     * Determine if the task is due today and not completed.
     */
    public function isDueToday(): bool
    {
        if ($this->status === TaskStatus::Completed || ! $this->due_date) {
            return false;
        }

        return $this->due_date->isToday();
    }

    /**
     * Scope a query to only include overdue tasks.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', '!=', TaskStatus::Completed->value)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', Carbon::today());
    }

    /**
     * Scope a query to filter tasks by search keyword, status, overdue status, and sort order.
     *
     * @param  Builder<static>  $query
     * @param  array<string, mixed>  $filters
     * @return Builder<static>
     */
    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        $query->when($filters['search'] ?? null, function (Builder $query, string $search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        $query->when($filters['status'] ?? null, function (Builder $query, string $status) {
            if ($status !== 'all' && in_array($status, TaskStatus::values(), true)) {
                $query->where('status', $status);
            }
        });

        $query->when(($filters['overdue'] ?? null) === '1' || ($filters['overdue'] ?? null) === true, function (Builder $query) {
            $query->overdue();
        });

        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            'due_asc' => $query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC'),
            'due_desc' => $query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date DESC'),
            'title_asc' => $query->orderBy('title', 'asc'),
            'title_desc' => $query->orderBy('title', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return $query;
    }
}
