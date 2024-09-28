<?php

namespace App\Filament\Pages;

use App\CompletedStatus;
use App\Filament\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\TaskStatus;
use Carbon\Carbon;
use Closure;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use Parallax\FilamentComments\Actions\CommentsAction;
use Parallax\FilamentComments\Models\Traits\HasFilamentComments;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use SearchableTrait;

class AllTasksBoard extends KanbanBoard implements HasActions
{

    // use HasFilamentComments;


    protected static string $view = 'alltasks-kanban.kanban-board';

    protected static string $headerView = 'alltasks-kanban.kanban-header';

    protected static string $recordView = 'alltasks-kanban.kanban-record';

    protected static string $statusView = 'alltasks-kanban.kanban-status';

    protected static string $scriptsView = 'alltasks-kanban.kanban-scripts';

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        if ($user && $user->role === 'ADMIN') {
            return true;
        }

        return false;
    }


    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    protected ?string $subheading = 'Task with star is urgent';

    // protected static string $recordStatusAttribute = 'status';

    protected static string $model = Task::class;

    protected static string $statusEnum = TaskStatus::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Board';

    protected static ?string $title = 'All Tasks';

    protected string $editModalTitle = 'Edit Record';

    protected string $editModalSaveButtonLabel = 'Save';

    protected string $editModalCancelButtonLabel = 'Cancel';

    protected bool $editModalSlideOver = true;

    // public bool $disableEditModal = true;


    // protected function records(): Collection
    // {

    //     return Task::ordered()->get();

    // }

    protected function records(): Collection
    {
        // Get current date and determine the start and end of the period (last 30 days)
        $currentDate = Carbon::now();
        $startOfPeriod = $currentDate->copy()->subDays(30);
        $endOfPeriod = $currentDate;

        // Retrieve tasks created from Monday to Friday with status not equal to 'done'
        // and either belong to a team with the current authenticated user or are directly assigned to the current authenticated user
        return Task::where(function ($query) use ($startOfPeriod, $endOfPeriod) {
            $query->where('is_done', '!=', 'done')
                ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod]);
        })
            ->get();
    }

    public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        Task::find($recordId)->update(['status' => $status]);
        Task::setNewOrder($toOrderedIds);
        // Log::info($message);
    }

    public function onSortChanged(int $recordId, string $status, array $orderedIds): void
    {
        Task::setNewOrder($orderedIds);
    }


    protected function getEditModalFormSchema(null | int $recordId): array
    {
        return [

            RadioDeck::make('is_done')
                ->label('Status')
                ->options(CompletedStatus::class)
                ->descriptions(CompletedStatus::class)
                ->icons(CompletedStatus::class)
                ->default(CompletedStatus::PendingReview)
                ->iconSize(IconSize::Small)
                ->iconPosition(IconPosition::Before)
                ->alignment(Alignment::Center)
                ->visible(function (Get $get) {
                    return $get('status') == 'review';
                })
                ->extraCardsAttributes([
                    'class' => 'rounded-md'
                ])
                ->extraOptionsAttributes([
                    'class' => 'text-sm leading-none w-full flex flex-col items-center justify-center p-1'
                ])
                ->extraDescriptionsAttributes([
                    'class' => 'text-xs font-light text-center'
                ])
                ->color('primary')
                ->padding('px-3 px-3')
                ->columns(3),

            Section::make('Task Details')
                ->description(' ')
                ->schema([

                    Toggle::make('urgent')
                        ->required()
                        ->columnSpan(1),

                    // TextInput::make('progress')
                    //     ->label('')
                    //     ->prefix('Progress')
                    //     ->numeric()
                    //     ->maxValue(100)
                    //     ->minValue(0)
                    //     ->suffix('%')
                    //     ->columnSpan(3),

                    ViewField::make('progress')
                        ->view('filament.forms.components.range-slider')
                        ->viewData([
                            'min' => 1,
                            'max' => 100,
                        ])
                        ->registerActions([
                            Action::make('setDone')
                                ->icon('heroicon-m-check-circle')
                                ->iconButton()
                                ->action(function (Set $set) {
                                    $set('progress', 100);
                                }),
                        ])
                        ->columnSpanFull(),

                    Cluster::make([
                        TextInput::make('title')
                            ->label('Task Name')
                            ->autocapitalize('words')
                            ->required()
                            ->columnSpan(2),
                        Textarea::make('description')
                            ->rows('3')
                            ->columnSpan(2),
                    ])
                        ->label('Task Name')
                        ->hint('')
                        ->helperText('*Description')->columnSpan(3),

                    Cluster::make([
                        Select::make('project')
                            ->label('Type ')
                            ->default('support assistance')
                            ->live()
                            ->options([
                                'support assistance' => 'Support/Assistance',
                                'troubleshooting' => 'Troubleshooting',
                                'installation' => 'Installation',
                                'update' => 'Update',
                                'technical support' => 'Technical Support',
                                'system management' => 'System Management',
                                'web development' => 'Web Development',
                                'email account management' => 'Email/Account Management',
                                'backup management' => 'Backup Management',
                                'preventive maintenance' => 'Preventive Maintenance',
                                'printing editing' => 'Printing/Editing',
                                'desktop publishing' => 'Desktop Publishing',
                                'others' => 'Others',
                            ])
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $bgColors = [
                                    'support assistance' => 'bg-sky-800',
                                    'troubleshooting' => 'bg-red-400',
                                    'installation' => 'bg-sky-400',
                                    'update' => 'bg-pink-400',
                                    'technical support' => 'bg-orange-400',
                                    'system management' => 'bg-yellow-400',
                                    'web development' => 'bg-lime-400',
                                    'email account management' => 'bg-green-400',
                                    'backup management' => 'bg-teal-400',
                                    'preventive maintenance' => 'bg-yellow-400',
                                    'printing editing' => 'bg-violet-400',
                                    'desktop publishing' => 'bg-fuchsia-400',
                                    'others' => 'bg-white',
                                ];

                                $textColors = [
                                    'support assistance' => 'text-white',
                                    'troubleshooting' => 'text-black',
                                    'installation' => 'text-white',
                                    'update' => 'text-white',
                                    'technical support' => 'text-black',
                                    'system management' => 'text-black',
                                    'web development' => 'text-black',
                                    'email account management' => 'text-black',
                                    'backup management' => 'text-black',
                                    'preventive maintenance' => 'text-black',
                                    'printing editing' => 'text-white',
                                    'desktop publishing' => 'text-white',
                                    'others' => 'text-sky-600',
                                ];

                                $set('bg_color', $bgColors[$state] ?? 'bg-sky-400');
                                $set('text_color', $textColors[$state] ?? 'text-black');
                            })
                            ->columnSpan(1),
                        DatePicker::make('due_date')
                            ->label('Due Date')
                            ->date('D - M d, Y')
                            ->nullable()->columnSpan(1),
                    ])
                        ->label('Type')
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
                        ->helperText(' ')->columnSpan(3),

                    Hidden::make('text_color')
                        ->default('text-white'),
                    Hidden::make('bg_color')
                        ->default('bg-sky-400'),

                    // Cluster::make([
                    //     Select::make('text_color')
                    //         ->default('text-white')
                    //         ->required()
                    //         ->options([
                    //             'text-white' => 'white',
                    //             'text-black' => 'black',
                    //             'text-yellow-400' => 'yellow',
                    //             'text-red-600' => 'red',
                    //             'text-sky-600' => 'blue',
                    //             'text-lime-600' => 'green',
                    //         ])
                    //         ->label(__('Text Color'))
                    //         ->columnSpan(1),

                    //     Select::make('bg_color')
                    //         ->default('bg-sky-400')
                    //         ->required()
                    //         ->options([
                    //             'bg-white' => 'white',
                    //             'bg-black' => 'black',
                    //             'bg-sky-400' => 'blue',
                    //             'bg-sky-800' => 'dark blue',
                    //             'bg-red-400' => 'red',
                    //             'bg-orange-400' => 'orange',
                    //             'bg-yellow-400' => 'yellow',
                    //             'bg-lime-400' => 'lime',
                    //             'bg-green-400' => 'green',
                    //             'bg-teal-400' => 'teal',
                    //             'bg-cyan-400' => 'cyan',
                    //             'bg-violet-400' => 'violet',
                    //             'bg-fuchsia-400' => 'fucshia',
                    //             'bg-pink-400' => 'pink',
                    //             'bg-rose-400' => 'rose',
                    //         ])

                    //         ->label(__('Background Color'))
                    //         ->columnSpan(1),
                    // ])
                    //     ->label('Customization - Text Color | BG Color')
                    //     ->hint('Default is White Text & Blue Background')
                    //     ->helperText(' ')->columnSpan(3),
                    // ToggleButtons::make('status')
                    //     ->label('Set')->inline()->grouped()
                    //     ->options([
                    //         'todo' => 'Back to Todo',
                    //         'ongoing' => 'On-Going',
                    //         'review' => 'For Review',
                    //         'deleted' => 'Delete',
                    //     ])
                    //     ->colors([
                    //         'todo' => 'info',
                    //         'ongoing' => 'warning',
                    //         'review' => 'success',
                    //         'deleted' => 'danger',
                    //     ])


                ])->columns(3),



            // Actions::make([
            //     Action::make('delete')
            //         ->icon('heroicon-m-x-mark')
            //         ->color('danger')
            //         ->requiresConfirmation()
            //         ->action(function () use ($recordId) {
            //             static::$model::find($recordId)->delete();

            //             $this->dispatch('close-modal', id: 'kanban--edit-record-modal');
            //         }),
            // ]),



        ];
    }



    protected function getEditModalRecordData(int $recordId, array $data): array
    {
        return Task::find($recordId)->toArray();
    }


    protected function editRecord($recordId, array $data, array $state): void
    {
        // Determine status based on progress
        if ($data['progress'] == 100) {
            // Task progress is 100%
            $data['status'] = TaskStatus::ForReview;
        } elseif ($data['progress'] > 0 && $data['progress'] < 100) {
            // Task is ongoing
            $data['status'] = TaskStatus::OnGoing;
        } elseif ($data['progress'] == 0) {
            // Task is yet to be started
            $data['status'] = TaskStatus::Todo;
        } elseif ($data['progress'] == -1) {
            // Task is marked for deletion
            $data['status'] = TaskStatus::Delete;
        }


        Task::find($recordId)->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'urgent' => $data['urgent'],
            'project' => $data['project'],
            'due_date' => $data['due_date'],
            'progress' => $data['progress'],
            'user_id' => $data['user_id'],
            'is_done' => $data['is_done'] ?? 'pending',
            'text_color' => $data['text_color'],
            'bg_color' => $data['bg_color'],
            'status' => $data['status'],

        ]);
    }


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('task')
                ->model(Task::class)
                ->button()
                ->size(ActionSize::Large)
                ->icon('heroicon-m-plus-circle')
                ->form([
                    Toggle::make('urgent')
                        ->required(),
                    Cluster::make([
                        TextInput::make('title')
                            ->label('Task Name')
                            ->required()
                            ->columnSpan(1),
                        Textarea::make('description')
                            ->required()
                            ->rows('3')
                            ->columnSpan(2),
                    ])
                        ->label('Task Name')
                        ->hint('')
                        ->helperText('*Description')->columns(1),

                    Cluster::make([


                        Select::make('project')
                            ->label('Type')
                            ->default('support assistance')
                            ->live()
                            ->options([
                                'support assistance' => 'Support/Assistance',
                                'troubleshooting' => 'Troubleshooting',
                                'installation' => 'Installation',
                                'update' => 'Update',
                                'technical support' => 'Technical Support',
                                'system management' => 'System Management',
                                'web development' => 'Web Development',
                                'email account management' => 'Email/Account Management',
                                'backup management' => 'Backup Management',
                                'preventive maintenance' => 'Preventive Maintenance',
                                'printing editing' => 'Printing/Editing',
                                'desktop publishing' => 'Desktop Publishing',
                                'others' => 'Others',
                            ])
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $bgColors = [
                                    'support assistance' => 'bg-sky-800',
                                    'troubleshooting' => 'bg-red-400',
                                    'installation' => 'bg-sky-400',
                                    'update' => 'bg-pink-400',
                                    'technical support' => 'bg-orange-400',
                                    'system management' => 'bg-yellow-400',
                                    'web development' => 'bg-lime-400',
                                    'email account management' => 'bg-green-400',
                                    'backup management' => 'bg-teal-400',
                                    'preventive maintenance' => 'bg-yellow-400',
                                    'printing editing' => 'bg-violet-400',
                                    'desktop publishing' => 'bg-fuchsia-400',
                                    'others' => 'bg-white',
                                ];

                                $textColors = [
                                    'support assistance' => 'text-white',
                                    'troubleshooting' => 'text-black',
                                    'installation' => 'text-white',
                                    'update' => 'text-white',
                                    'technical support' => 'text-black',
                                    'system management' => 'text-black',
                                    'web development' => 'text-black',
                                    'email account management' => 'text-black',
                                    'backup management' => 'text-black',
                                    'preventive maintenance' => 'text-black',
                                    'printing editing' => 'text-white',
                                    'desktop publishing' => 'text-white',
                                    'others' => 'text-sky-600',
                                ];

                                $set('bg_color', $bgColors[$state] ?? 'bg-sky-400');
                                $set('text_color', $textColors[$state] ?? 'text-white');
                            })
                            ->columnSpan(1),
                        DatePicker::make('due_date')
                            ->label('Due Date')
                            ->date('D - M d, Y')
                            ->default(Carbon::now())
                            ->nullable(),

                    ])
                        ->label('Type')
                        ->hint('Due Date')
                        ->columns(2),

                    Cluster::make([
                        Select::make('user_id')
                            ->default(auth()->id())
                            ->relationship('user', 'name')
                            ->required()
                            ->columnSpan(1),
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
                        ->helperText(' ')->columns(3),

                    Hidden::make('text_color')
                        ->default('text-white'),
                    Hidden::make('bg_color')
                        ->default('bg-sky-400'),

                    // Cluster::make([
                    //     Select::make('text_color')
                    //         ->default('text-white')
                    //         ->required()
                    //         ->options([
                    //             'text-white' => 'white',
                    //             'text-black' => 'black',
                    //             'text-yellow-400' => 'yellow',
                    //             'text-red-600' => 'red',
                    //             'text-sky-600' => 'blue',
                    //             'text-lime-600' => 'green',
                    //         ])
                    //         ->label(__('Text Color')),

                    //     Select::make('bg_color')
                    //         ->default('bg-sky-400')
                    //         ->required()
                    //         ->options([
                    //             'bg-white' => 'white',
                    //             'bg-black' => 'black',
                    //             'bg-sky-400' => 'blue',
                    //             'bg-sky-800' => 'dark blue',
                    //             'bg-red-400' => 'red',
                    //             'bg-orange-400' => 'orange',
                    //             'bg-yellow-400' => 'yellow',
                    //             'bg-lime-400' => 'lime',
                    //             'bg-green-400' => 'green',
                    //             'bg-teal-400' => 'teal',
                    //             'bg-cyan-400' => 'cyan',
                    //             'bg-violet-400' => 'violet',
                    //             'bg-fuchsia-400' => 'fucshia',
                    //             'bg-pink-400' => 'pink',
                    //             'bg-rose-400' => 'rose',
                    //         ])

                    //         ->label(__('Background Color')),
                    // ])
                    //     ->label('Customization - Text Color | BG Color')
                    //     ->hint('Default is White Text & Blue Background')
                    //     ->helperText(' ')->columns(2),


                ]),




        ];
    }

    protected function additionalRecordData(Model $record): Collection
    {

        return collect([

            
            'urgent' => $record->urgent,
            'progress' => $record->progress,
            // 'owner' => $record->user->name,
            'description' => $record->description,
            'text_color' => $record->text_color,
            'bg_color' => $record->bg_color,


        ]);
    }


public function deleteRecord(int $recordId)
{
    Task::find($recordId)->delete();

    Notification::make()
    ->title('Deleted successfully')
    ->success()
    ->send();
}



}
