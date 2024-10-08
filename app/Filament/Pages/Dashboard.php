<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard

{
    use HasFiltersForm;
    use HasFiltersAction;

    protected static ?string $navigationIcon = 'icon-home';

    protected static ?string $title = 'Home';

    public function getColumns(): int | string | array
    {
        return [
            'default' => 2,
            'sm' => 2,
            'md' => 3,
            'lg' => 4,
            'xl' => 5,
        ];
    }
 
    // public function filtersForm(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Section::make()
    //                 ->schema([
    //                     DatePicker::make('startDate'),
    //                     DatePicker::make('endDate'),
    //                 ])
    //                 ->columns(3),
    //         ]);
    // }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         FilterAction::make()
    //             ->form([
    //                 DatePicker::make('startDate'),
    //                 DatePicker::make('endDate'),
    //             ]),
    //     ];
    // }
}
