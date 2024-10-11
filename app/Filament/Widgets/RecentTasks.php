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
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use JaOcero\RadioDeck\Contracts\HasIcons;

class RecentTasks extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Recent Tasks';

    // protected static string $view = 'filament.widgets.recent-tasks';

    protected static ?int $sort = 4;

    // protected int | string | array $columnSpan = 'full';

    protected int | string | array $columnSpan = [
        'default' => 'full',
    ];

    

    public function table(Table $table): Table
    {
        $query = Task::query();

        if (auth()->user()->role === "ADMIN") {
            $query = $query->latest();
        } else {
            $query = $query->where('user_id', auth()->user()->id)->latest();
        }

        return $table
            ->query($query)
            ->persistSortInSession()
            ->defaultPaginationPageOption(6)
            ->striped()
            ->contentGrid([
                'md' => 1,
                'lg' => 2,
                'xl' => 3,
            ])
            ->columns([
                TextColumn::make('title')
                ->label('Task')
                ->wrap()
                ->searchable(),
            TextColumn::make('user.name')
                ->searchable()
                ->label('User')
                ->hidden(auth()->user()->role !== 'ADMIN'),
            TextColumn::make('project')
                ->label('Task Type')
                ->badge()
                ->searchable(),

            SelectColumn::make('status')
                ->options(TaskStatus::class),
            SelectColumn::make('is_done')
            ->label('Review')
                ->options(CompletedStatus::class),
            TextColumn::make('progress')
                ->suffix('%')
                ->numeric()
                ->sortable(),
            TextColumn::make('due_date')
                ->dateTime('M d, Y')
                ->sortable(),
            TextColumn::make('created_at')
                ->dateTime('m-d-Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime('m-d-Y h:i A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name', function (Builder $query) {
                        if (auth()->user()->role === 'ADMIN') {
                        } else {
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
