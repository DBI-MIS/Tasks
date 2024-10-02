<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;
use Carbon\CarbonImmutable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class TasksOverview extends Widget
{
    use InteractsWithPageFilters;

    protected static string $view = 'filament.widgets.tasks-overview';

    protected static ?int $sort = 2;

    // protected int | string | array $columnSpan = 'full';

    protected int | string | array $columnSpan = [
        'default' =>2,
        'sm' => 2,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
      
    ];



    public function getViewData(): array

    {

        //Previous Month
        $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfPreviousPeriod = $startOfPreviousMonth->copy()->endOfMonth();

        //Current Month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfPeriod = $startOfMonth->copy()->addDays(30);

        $startDate = $this->filters['startDate'] ?? $startOfMonth;
        $endDate = $this->filters['endDate'] ?? $endOfPeriod;

        $startDatePrevious = $this->filters['startDate'] ?? $startOfPreviousMonth;
        $endDatePrevious = $this->filters['endDate'] ?? $endOfPreviousPeriod;


        $totalTasks = Task::query()
        ->count();
        $totalCompletedTasks = Task::query()
        ->where('is_done', 'done')
        ->count();

        $currentMonthAllTasks = Task::query()
            ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        $currentMonthAllCompletedTasks = Task::query()
            ->where('is_done', 'done')
            ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        $previousMonthAllTasks = Task::query()
                ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDatePrevious))
                ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDatePrevious))
                ->count();
        $previousMonthAllCompletedTasks = Task::query()
                ->where('is_done', 'done')
                ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDatePrevious))
                ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDatePrevious))
                ->count();


        // $completionRate = $totalTasks > 0 ? round(($totalCompletedTasks / $totalTasks) * 100) : 0;
        $totalCompletionRate = $totalTasks > 0 ? round(($totalCompletedTasks / $totalTasks) * 100, 1) : 0;
        $currentMonthCompletionRate = $currentMonthAllTasks > 0 ? round(($currentMonthAllCompletedTasks / $currentMonthAllTasks) * 100, 1) : 0;
        $previousMonthCompletionRate = $previousMonthAllTasks > 0 ? round(($previousMonthAllCompletedTasks / $previousMonthAllTasks) * 100, 1) : 0;

        $myTotalTasks = Task::query()
                ->where('user_id', auth()->id())
                ->count();
        $myTotalCompletedTasks = Task::query()
                ->where('user_id', auth()->id())
                ->where('is_done', 'done')
                ->count();

        $myCurrentMonthTask = Task::query()
                ->where('user_id', auth()->id())
                ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                ->count();
        $myCurrentMonthCompletedTask = Task::query()
                ->where('user_id', auth()->id())
                ->where('is_done', 'done')
                ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                ->count();

        $myPreviousMonthTask = Task::query()
                ->where('user_id', auth()->id())
                ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDatePrevious))
                ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDatePrevious))
                ->count();
        $myPreviousMonthCompletedTask = Task::query()
                ->where('user_id', auth()->id())
                ->where('is_done', 'done')
                ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDatePrevious))
                ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDatePrevious))
                ->count();

        $myTotalCompletionRate = $myTotalTasks > 0 ? round(($myTotalCompletedTasks / $myTotalTasks) * 100, 1) : 0;
        $myCurrentMonthCompletionRate = $myCurrentMonthTask > 0 ? round(($myCurrentMonthCompletedTask / $myCurrentMonthTask) * 100, 1) : 0;
        $myPreviousMonthCompletionRate = $myPreviousMonthTask > 0 ? round(($myPreviousMonthCompletedTask / $myPreviousMonthTask) * 100, 1) : 0;


        return [
            'totalTask' => $totalTasks,
            'currentMonthAllTask' => $currentMonthAllTasks,
            'currentMonthAllCompletedTask' => $currentMonthAllCompletedTasks,
            'previousMonthAllTask' => $previousMonthAllTasks,
            'previousMonthAllCompletedTask' => $previousMonthAllCompletedTasks,
            'totalCompletionRate' => $totalCompletionRate,
            'currentMonthCompletionRate' => $currentMonthCompletionRate,
            'previousMonthCompletionRate' => $previousMonthCompletionRate,

            'myTotalTasks' => $myTotalTasks,
            'myTotalCompletedTasks' => $myTotalCompletedTasks,
            'myCurrentMonthTask' => $myCurrentMonthTask,
            'myCurrentMonthCompletedTask' => $myCurrentMonthCompletedTask,
            'myPreviousMonthTask' => $myPreviousMonthTask,
            'myPreviousMonthCompletedTask' => $myPreviousMonthCompletedTask,
            'myTotalCompletionRate' => $myTotalCompletionRate,
            'myCurrentMonthCompletionRate' => $myCurrentMonthCompletionRate,
            'myPreviousMonthCompletionRate' => $myPreviousMonthCompletionRate,
    

        ];
    }

}
