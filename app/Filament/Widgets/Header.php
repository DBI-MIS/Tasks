<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;

class Header extends Widget
{
    protected static ?int $sort = 1;

    protected static string $view = 'filament.widgets.header';


    protected int | string | array $columnSpan = [
        'default' => 'full',
    ];

    // protected int | string | array $columnStart = '2';

    protected static bool $isLazy = true;

    public function getViewData(): array
    {

        return [
            'currentTime' => Carbon::now(),
            'user' => Auth()->user(),
        ];
    }
}
