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
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use SearchableTrait;

class AllTasksBoard extends KanbanBoard implements HasActions
{

    // use HasFilamentComments;
    use HasFiltersAction;
    use InteractsWithPageFilters;
    // use HasFiltersForm;


    protected static string $view = 'alltasks-kanban.kanban-board';

    protected static string $headerView = 'alltasks-kanban.kanban-header';

    protected static string $recordView = 'alltasks-kanban.kanban-record';

    protected static string $statusView = 'alltasks-kanban.kanban-status';

    protected static string $scriptsView = 'alltasks-kanban.kanban-scripts';

    public static function canAccess(): bool
    {
        return auth()->user()->canManageSettings();
    }


    protected static ?string $navigationIcon = 'icon-all-inbox';

    // protected ?string $subheading = 'Task with star is urgent';

    // protected static string $recordStatusAttribute = 'status';

    protected static string $model = Task::class;

    protected static string $statusEnum = TaskStatus::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Task';

    protected static ?string $title = 'All Tasks';

    protected string $editModalTitle = 'Edit Task';

    protected string $editModalSaveButtonLabel = 'Update';

    protected string $editModalCancelButtonLabel = 'Cancel';

    protected bool $editModalSlideOver = true;

    // protected function records(): Collection
    // {

    //     return Task::ordered()->get();

    // }

    public $startDate;
    public $endDate;
    public $searchText;
    public $searchSelect;


    // protected $rules = [
    //     'searchText' => 'nullable',
    //     'searchSelect' => 'nullable',
    //     'startDate' => 'nullable|date',
    //     'endDate' => 'nullable|date|after_or_equal:startDate',
    // ];

    // public function filterByDate()
    // {
    //     $this->validate();
    // }

    protected function records(): Collection
    {

        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->startOfWeek();
        $startOfMonth = $currentDate->startOfMonth();
        $startOfPeriod = $currentDate->copy()->subDays(30);
        $endOfPeriod = $startOfMonth->copy()->addDays(30);

        //filter
        $startDate = $this->filters['startDate'] ?? $startOfMonth;
        $endDate = $this->filters['endDate'] ?? $endOfPeriod;
        $searchText = $this->filters['searchText'] ?? null;
        $searchSelect = $this->filters['searchSelect'] ?? null;

        $query = Task::when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDate));

        return Task::where(function ($query) use ($startOfPeriod, $endOfPeriod, $searchText) {
            $query->where('is_done', '!=', 'done')
                ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod]);
            $query->when(!empty($searchText), fn(Builder $query) => $query->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($searchText) . "%"]));
        })
            ->whereHas('user', function ($query) use ($searchSelect) {
                $query->where('status', 'active');

                if (!empty($searchSelect)) {
                    $query->whereIn('department', $searchSelect);
                }
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->orderBy('order_column', 'desc')
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

                    ViewField::make('progress')
                        ->view('filament.forms.components.range-slider')
                        ->viewData([
                            'min' => 1,
                            'max' => 100,

                        ])
                        ->columnSpan(3),

                    Cluster::make([
                        TextInput::make('title')
                            ->label('Task Name')
                            ->autocapitalize('words')
                            ->required()
                            ->columnSpan(3),
                        Textarea::make('description')
                            ->rows('4')
                            ->columnSpan(3),
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



                ])->columns(3),



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
        } 

        // if ($data['is_done'] = CompletedStatus::Done) {
        //     $data['status'] = TaskStatus::Done;
        // }


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



                ]),

            FilterAction::make('filter')
                ->form([
                    TextInput::make('searchText')
                        ->label('Search Task'),
                    CheckboxList::make('searchSelect')
                        ->label('Filter By Department')
                        ->default('All')
                        ->options([
                            'Sales' => 'Sales',
                            'TSD' => 'TSD',
                            'DBE' => 'DBE',
                            'Admin' => 'Admin',
                            'Accounting' => 'Accounting',
                            'HRAD' => 'HRAD',
                            'Audit' => 'Audit',
                            'Purchasing' => 'Purchasing',
                            'MIS' => 'MIS',
                            'Warehouse' => 'Warehouse',
                            'Others' => 'Others',
                        ])
                        ->columns(2)
                        ->bulkToggleable()
                        ->selectAllAction(
                            fn(Action $action) => $action->label('All'),
                        ),
                    // ->afterStateHydrated(function ($component, $state) {
                    //     if (!filled($state)) {
                    //         $component->state([
                    //             'Sales',
                    //             'TSD',
                    //             'DBE',
                    //             'Admin',
                    //             'Accounting',
                    //             'HRAD',
                    //             'Audit',
                    //             'Purchasing',
                    //             'MIS',
                    //             'Warehouse'
                    //         ]);
                    //     }
                    // })
                    // ->formatStateUsing(function ($context, $state) {
                    //     return $context === 'create' ? [
                    //         'Sales',
                    //             'TSD',
                    //             'DBE',
                    //             'Admin',
                    //             'Accounting',
                    //             'HRAD',
                    //             'Audit',
                    //             'Purchasing',
                    //             'MIS',
                    //             'Warehouse'
                    //         ] : $state;
                    // }),
                    // Select::make('searchSelect')
                    // ->options(User::all()->pluck('department', 'id'))   
                    //     ->multiple()
                    //     ->label('Department'),
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),

                ])
                ->iconButton()
                ->icon('heroicon-m-funnel'),







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
