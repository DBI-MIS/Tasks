<?php

namespace App\Filament\Widgets;

use App\CompletedStatus;
use App\Models\Task;
use App\TaskStatus;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use JaOcero\RadioDeck\Contracts\HasIcons;

class RecentTasks extends BaseWidget
{

    protected static ?string $heading = 'Pending Tasks';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 2,
        'sm' => 2,
        'md' => 2,
        'lg' => 3,
        'xl' => 4,



    ];

    public function table(Table $table): Table
    {
        $query = Task::query();

        if (auth()->user()->role === "ADMIN") {
            $query = $query->where('status', '!=', TaskStatus::Done)
                           ->where('is_done', '!=', CompletedStatus::Done)
                           ->latest();
        } else {
            $query = $query->where('user_id', auth()->user()->id)
                           ->where('status', '!=', TaskStatus::Done)
                           ->where('is_done', '!=', CompletedStatus::Done)
                           ->latest();
        }

        return $table
            ->query($query)
            ->persistSortInSession()
            ->defaultPaginationPageOption(25)
            ->striped()
            ->contentGrid([
                'md' => 1,
                'lg' => 2,
                'xl' => 3,
            ])
            ->columns([
                View::make('filament.widgets.recent-tasks')
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name', function (Builder $query) {
                        if (auth()->user()->role === 'ADMIN') {
                            // Show all users if the role is 'ADMIN'
                            // No need to include withTrashed() here
                        } else {
                            // Show only the user themselves if they are not 'ADMIN'
                            $query->where('id', auth()->user()->id);
                        }
                    })
                    ->default(auth()->user()->id)
                    ->label('User')
                    ->selectablePlaceholder(false)
                    ->indicator('User'),
            ]);
    }
}
