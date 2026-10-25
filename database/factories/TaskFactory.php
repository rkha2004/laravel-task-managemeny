<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->boolean(80) ? fake()->paragraph(2) : null,
            'status' => fake()->randomElement(TaskStatus::cases())->value,
            'due_date' => fake()->boolean(85)
                ? fake()->dateTimeBetween('-2 weeks', '+4 weeks')->format('Y-m-d')
                : null,
        ];
    }

    /**
     * Indicate that the task is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::Pending->value,
        ]);
    }

    /**
     * Indicate that the task is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::InProgress->value,
        ]);
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::Completed->value,
        ]);
    }

    /**
     * Indicate that the task is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => fake()->randomElement([TaskStatus::Pending->value, TaskStatus::InProgress->value]),
            'due_date' => Carbon::now()->subDays(fake()->numberBetween(1, 14))->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the task is due today.
     */
    public function dueToday(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => fake()->randomElement([TaskStatus::Pending->value, TaskStatus::InProgress->value]),
            'due_date' => Carbon::today()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the task has no due date.
     */
    public function withoutDueDate(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => null,
        ]);
    }

    /**
     * Indicate that the task has no description.
     */
    public function withoutDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => null,
        ]);
    }
}
