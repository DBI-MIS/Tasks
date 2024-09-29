<?php

namespace App\Filament\Pages;

use App\CompletedStatus;
use App\Status;
use App\TaskStatus;
use App\Models\Task;
use Carbon\Carbon;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Set;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;


class CompletedTaskBoard extends KanbanBoard
{
    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-check';

    protected static string $view = 'completedtasks-kanban.kanban-board';

    protected static string $headerView = 'completedtasks-kanban.kanban-header';

    protected static string $recordView = 'completedtasks-kanban.kanban-record';

    protected static string $statusView = 'completedtasks-kanban.kanban-status';

    protected static string $scriptsView = 'completedtasks-kanban.kanban-scripts';

    protected static string $recordStatusAttribute = 'is_done';

    protected static string $model = Task::class;

    protected static string $statusEnum = CompletedStatus::class;

    public function getSubheading(): ?string
{
    $prefix = 'Completed Tasks for ';
    
    // Get the current month using now()->format('F')
    $currentMonth = now()->format('F');
    
    // Return the prefix concatenated with the current month
    return $prefix . $currentMonth;
}

    protected static ?string $navigationGroup = 'Board';

    protected static ?string $title = 'My Completed Tasks';

    protected static ?int $navigationSort = 3;

    protected string $editModalTitle = 'Edit Completed Task';

    protected string $editModalSaveButtonLabel = 'Update';

    protected string $editModalCancelButtonLabel = 'Cancel';

    protected bool $editModalSlideOver = true;

    

    // protected function records(): Collection
    // {

    //     return Task::ordered()
    //         ->whereHas(
    //             'team',
    //             function ($query) {
    //                 return $query->where('user_id', auth()->id());
    //             }
    //         )
    //         ->orWhere('user_id', auth()->id())
    //         ->get();
    // }

    public $startDate;
    public $endDate;

    protected $rules = [
        'startDate' => 'nullable|date',
        'endDate' => 'nullable|date|after_or_equal:startDate',
    ];

    public function filterByDate()
    {
        $this->validate();
    }

    protected function records(): Collection
{
    // Get the start of the current month
    $startOfMonth = Carbon::now()->startOfMonth();
    // Add 30 days to the start of the month
    $endOfPeriod = $startOfMonth->copy()->addDays(30);

    // If the user hasn't provided a custom date range, use the default range
    $startDate = $this->startDate ?? $startOfMonth;
    $endDate = $this->endDate ?? $endOfPeriod;

    // Default query: filter by 'is_done' and created within the first 30 days of the month
    $query = Task::where('is_done', 'done')
                 ->whereBetween('created_at', [$startDate, $endDate]);

    // Additional filtering: tasks that belong to a team or are directly assigned to the user
    return $query->where(function ($query) {
                $query->whereHas('team', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->orWhere('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();
}


    // protected function records(): Collection
    // {
    //     // Get current date and the weekday number (0 for Sunday, 1 for Monday, etc.)
    //     $currentDate = Carbon::now();
    //     $startOfPeriod = $currentDate->copy()->subDays(30);
    //     $endOfPeriod = $currentDate;
    
    //     // Retrieve tasks created from Monday to Friday with status not equal to 'done'
    //     // and either belong to a team with the current authenticated user or are directly assigned to the current authenticated user
    //     return Task::where(function ($query) use ($startOfPeriod, $endOfPeriod) {
    //             $query->where('is_done', '=', 'done')
    //                   ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod]);
    //         })
                    
    //                 ->where(function ($query) {
    //                     $query->whereHas('team', function ($query) {
    //                         $query->where('user_id', auth()->id());
    //                     })
    //                     ->orWhere('user_id', auth()->id());
    //                 })
    //                 ->get();
    // }

    public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {


        Task::find($recordId)->update(['is_done' => $status]);
        Task::setNewOrder($toOrderedIds);
        // Log::info($message);
    }

    public function onSortChanged(int $recordId, string $status, array $orderedIds): void
    {
        Task::setNewOrder($orderedIds);
    }


    protected function getEditModalFormSchema(null|int $recordId): array
    {
        return [
            RadioDeck::make('is_done')
            ->label('Status')
                        ->options(CompletedStatus::class)
                        ->descriptions(CompletedStatus::class)
                        ->icons(CompletedStatus::class)
                        ->required()
                        ->iconSize(IconSize::Small)
                        ->iconPosition(IconPosition::Before)
                        ->alignment(Alignment::Center)
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
                        ->helperText('*Description')->columnSpanFull(),

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


        Task::find($recordId)->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'urgent' => $data['urgent'],
            'project' => $data['project'],
            'due_date' => $data['due_date'],
            'progress' => $data['progress'],
            'user_id' => $data['user_id'],
            'is_done' => $data['is_done'],
            'text_color' => $data['text_color'],
            'bg_color' => $data['bg_color'],

        ]);
    }

    

    protected function additionalRecordData(Model $record): Collection
    {

        return collect([
            'urgent' => $record->urgent,
            'progress' => $record->progress,
            'description' => $record->description,
            'is_done' => $record->is_done,
            'text_color' => $record->text_color,
            'bg_color' => $record->bg_color,

        ]);
    }
}
