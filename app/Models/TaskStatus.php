<?php

namespace App\Models;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model implements HasLabel, HasColor, HasIcon
{
    protected $fillable = [
        'name',
        'label',
        'color',
        'icon',
        'details',
    ];

    public static function kanbanCases(): array
    {
        return self::whereIn(
            'name',
                [
                    'todo',
                    'ongoing',
                    'review',
                ])
                ->get();
    }


    public static function statuses(): array
    {
        return self::whereIn('name', ['todo', 'ongoing', 'review'])->pluck('name')->toArray();
    }
    
    protected function records(): Collection
    {
        // Start the query on the TaskStatus model
        $query = static::query();

        // Check if the 'scopeOrdered' method exists on the model
        if (method_exists(static::class, 'scopeOrdered')) {
            // Apply the 'ordered' scope if it exists
            $query->ordered();
        }

        // Execute the query and return the results as a collection
        return $query->get();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'asc'); // Example ordering logic
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getColor(): string | array | null
    {
        return $this->color;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getTitle(): string
    {
        return $this->label;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_status_id');
    }
}
