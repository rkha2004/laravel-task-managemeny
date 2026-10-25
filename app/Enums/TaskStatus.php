<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'Pending';
    case InProgress = 'In Progress';
    case Completed = 'Completed';

    /**
     * Get all enum string values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get human-friendly label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
        };
    }

    /**
     * Get badge color styling class for UI.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-slate-100 text-slate-700 border-slate-200',
            self::InProgress => 'bg-slate-100 text-slate-700 border-slate-200',
            self::Completed => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    /**
     * Get dot indicator color for UI.
     */
    public function dotClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-slate-400',
            self::InProgress => 'bg-slate-500',
            self::Completed => 'bg-slate-700',
        };
    }
}
