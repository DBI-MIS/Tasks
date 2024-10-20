<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum TaskStatus: string implements HasLabel, HasColor, HasIcon
{
    use IsKanbanStatus;

    case Todo = 'todo';
    case OnGoing = 'ongoing';
    case ForReview = 'review';
    case Done = 'done';
    case OnHold = 'onhold';

    public static function kanbanCases(): array
    {
        return [
            static::Todo,
            static::OnGoing,
            static::ForReview,
            // static::Done,
            // static::OnHold,
        ];
    }

    public function getTitle(): string
    {
        return match ($this) {
            self::Todo => 'To do',
            self::OnGoing => 'On Going',
            self::ForReview => 'For Review',
            self::Done => 'Done',
            self::OnHold => 'On Hold',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Todo => 'To do',
            self::OnGoing => 'On Going',
            self::ForReview => 'For Review',
            self::Done => 'Done',
            self::OnHold => 'On Hold',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Todo => 'gray',
            self::OnGoing => 'warning',
            self::ForReview => 'success',
            self::Done => 'success',
            self::OnHold => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Todo => 'heroicon-m-pencil',
            self::OnGoing => 'heroicon-m-paper-airplane',
            self::ForReview => 'heroicon-m-eye',
            self::Done => 'heroicon-m-check',
            self::OnHold => 'heroicon-m-x-mark',
        };
    }
}