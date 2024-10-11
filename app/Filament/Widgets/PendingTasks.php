<?php

namespace App\Filament\Widgets;

use App\CompletedStatus;
use App\Models\Task;
use App\TaskStatus;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class PendingTasks extends Widget
{
    protected static string $view = 'filament.widgets.pending-tasks';

    use InteractsWithPageFilters;

    protected static ?string $heading = 'Pending Tasks';

    public static function getHeading(): ?string
{
    return static::$heading;
}

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => '2',
        'sm' => 2,
        'md' => 2,
        'lg' => 3,
        'xl' => 4,
    ];

    public function getViewData(): array
    {
        return [
            'tasks' => Task::when(auth()->user()->role === "ADMIN", function ($query) {
                    $query->where('status', '!=', TaskStatus::Done)
                          ->where('is_done', '!=', CompletedStatus::Done);
                }, function ($query) {
                    $query->where('user_id', auth()->user()->id)
                          ->where('status', '!=', TaskStatus::Done)
                          ->where('is_done', '!=', CompletedStatus::Done);
                })
                ->latest()
                ->get(),
        ];
    }
}
