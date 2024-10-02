<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentTasks extends BaseWidget
{

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

        // Check if the authenticated user's role is "ADMIN"
        if (auth()->user()->role === "ADMIN") {
            // If the user is an admin, query all tasks
            $query = $query->latest();
        } else {
            // If the user is not an admin, query tasks that belong to the user
            $query = $query->where('user_id', auth()->user()->id)->latest();
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
                Split::make([
                    TextColumn::make('title')
                        ->label('Task')
                        ->searchable()
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('project')
                            ->label('Type')
                            ->searchable()
                            ->sortable()
                            ->badge()
                            ->grow(false)
                            ->alignment(Alignment::End),
                        TextColumn::make('status')
                            ->label('Status')
                            ->sortable()
                            ->searchable()
                            ->badge()
                            ->grow(false)
                            ->alignment(Alignment::End),
                    ])
                        ->space(1)
                        ->grow(true)
                        ->alignment(Alignment::End),
                ])
                    ->from('sm'),
                Panel::make([
                    Stack::make([
                        TextColumn::make('description')
                            ->label('Description')
                            ->searchable(),
                    ]),
                ])
                ->collapsed(true),
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
