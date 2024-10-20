<?php

namespace App\Filament\Resources;

use App\CompletedStatus;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\TaskStatus;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter as FiltersFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Illuminate\Support\Facades\Auth;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Task';

    protected static ?string $label = 'History';

    protected static ?int $navigationSort = 6;

    // public static function shouldRegisterNavigation(): bool
    // {
    //     $user = Auth::user();

    //     if ($user && $user->role === 'ADMIN') {
    //         return true;
    //     }

    //     return false;
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Task Details')
                    ->description(' ')
                    ->schema([

                        Toggle::make('urgent')
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('progress')
                            ->label('')
                            ->prefix('Progress')
                            ->numeric()
                            ->step(25)
                            ->maxValue(100)
                            ->minValue(0)
                            ->suffix('%')
                            ->columnSpan(2),

                        Cluster::make([
                            TextInput::make('title')
                                ->label('Task Name')
                                ->autocapitalize('words')
                                ->required()
                                ->columnSpan(3),
                            Textarea::make('description')
                                ->required()
                                ->rows('3')
                                ->columnSpan(3),
                        ])
                            ->label('Task Name')
                            ->hint('')
                            ->helperText('*Description can be Blank')->columnSpan(3),

                        Cluster::make([


                            TextInput::make('project')
                                ->label('Project')
                                ->nullable()
                                ->columnSpan(1),
                            DatePicker::make('due_date')
                                ->label('Due Date')
                                ->date('D - M d, Y')
                                ->nullable()->columnSpan(1),
                        ])
                            ->label('Project')
                            ->hint('Due Date')
                            ->columnSpan(3),

                        Cluster::make([
                            Select::make('user_id')
                                ->default(auth()->id())
                                ->relationship('user', 'name')
                                ->required()
                                ->columnSpan(2),
                            Select::make('team')
                                ->label('Assigned User')
                                ->relationship('team', 'name')
                                ->multiple()
                                ->nullable()
                                ->searchable()
                                ->preload()
                                ->columnSpan(2),
                        ])
                            ->label('User')
                            ->hint('Assigned User/s')
                            ->helperText(' ')
                            ->columnSpan(3),

                        Cluster::make([
                            Select::make('status')
                                ->options(TaskStatus::class)
                                ->columnSpan(1),
                            Select::make('is_done')
                                ->options(CompletedStatus::class)
                                ->columnSpan(1),
                        ])
                            ->label('Status')
                            ->hint('Reviewed')
                            ->helperText(' ')
                            ->columnSpan(3),

                    ])->columnSpan(2),


            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Check if the authenticated user is an admin
                if (auth()->user()->role !== 'ADMIN') {
                    // If the user is an admin, return all records
                    return $query->where('user_id', auth()->user()->id)
                        ->orderBy('created_at', 'desc')
                        ->orderBy('updated_at', 'desc');
                }
            })
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession()
            ->columns([


                TextColumn::make('title')
                    ->label('Task Name')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('User'),

                SelectColumn::make('status')
                    ->options(TaskStatus::class),
                SelectColumn::make('is_done')
                ->label('Review')
                    ->options(CompletedStatus::class),
                TextColumn::make('progress')
                    ->suffix('%')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('m-d-Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('m-d-Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('updated_at', 'desc')
            ->persistSortInSession()
            ->defaultPaginationPageOption(50)
            ->filters([

                SelectFilter::make('status')
                    ->options(TaskStatus::class),
                SelectFilter::make('is_done')
                    ->options(CompletedStatus::class),

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

                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->format('m-d-Y h:i A'),
                        Forms\Components\DatePicker::make('created_until')->default(now()),

                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Filter::make('updated_at')
                    ->form([
                        Forms\Components\DatePicker::make('updated_from'),
                        Forms\Components\DatePicker::make('updated_until')->default(now()),

                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['updated_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                            )
                            ->when(
                                $data['updated_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('updated_at', '<=', $date),
                            );
                    })
                //
            ], layout: FiltersLayout::AboveContent)

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // ->hidden(auth()->user()->role !== 'ADMIN'),
                Action::make('history')->url(fn($record) => TaskResource::getUrl('history', ['record' => $record])),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('table')
                            ->fromTable()
                            ->askForFilename()
                            ->askForWriterType()
                            // ->withFilename(date('Y-m-d') . ' - export')
                            // ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                            ->withColumns([
                                Column::make('user.name')->heading('Name'),
                                Column::make('title')->heading('Task'),
                                Column::make('progress')->heading('progress'),
                                Column::make('status')->heading('Status'),
                                Column::make('is_done')->heading(' Review Status'),
                                Column::make('updated_at'),
                                Column::make('created_at'),
                                Column::make('deleted_at'),
                            ]),
                    ])
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            // 'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'history' => Pages\TaskActivityLogPage::route('/{record}/history'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
