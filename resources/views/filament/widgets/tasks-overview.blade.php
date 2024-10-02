<x-filament-widgets::widget wire:init="getViewData">
    <x-filament::section>
        <div class="flex flex-row sm:flex-col gap-2 justify-between">
        @if(auth()->check() && auth()->user()->role === 'ADMIN')

        <div class="flex flex-col justify-between px-4 py-3">
            <span class="text-xs">Total Tasks</span>
            <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                <x-filament::loading-indicator class="h-10 w-10" />
            </div>
            <span class="font-bold text-4xl">{{ $totalTask }}</span>
            <span class="text-[10px]">{{ $totalCompletionRate }}% Completion Rate</span>
            
        </div>
        
        
        <div class="flex flex-col justify-between px-4 py-3">
            <span class="text-xs">Tasks for this month</span>
            <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                <x-filament::loading-indicator class="h-10 w-10" />
            </div>
            <span class="font-bold text-4xl">{{ $currentMonthAllCompletedTask }}
            <span class="text-xs">/{{ $currentMonthAllTask }}</span>   
            </span>
            
            <span class="text-[10px]">{{ $currentMonthCompletionRate }}% Completion Rate</span>  

        </div>

        <div class="flex flex-col justify-between px-4 py-3">
            <span class="text-xs">Tasks last month</span>
            <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                <x-filament::loading-indicator class="h-10 w-10" />
            </div>
            <span class="font-bold text-4xl">{{ $previousMonthAllCompletedTask }}
            <span class="text-xs">/{{ $previousMonthAllTask }}</span>
            </span>
            <span class="text-[10px]">{{ $previousMonthCompletionRate }}% Completion Rate</span> 
            
        </div>

        @endif
        @if(auth()->check() && auth()->user()->role !== 'ADMIN')

        <div class="flex flex-col justify-between px-4 py-3">
            <span class="text-xs">Total Tasks</span>
            <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                <x-filament::loading-indicator class="h-10 w-10" />
            </div>
            <span class="font-bold text-4xl">{{ $myTotalTasks }}</span>
            <span class="text-[10px]">{{ $myTotalCompletionRate }}% Completion Rate</span>
            
        </div>

            <div class="flex flex-col justify-between px-4 py-3">
                <span class="text-xs">Tasks for this month</span>
                <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <x-filament::loading-indicator class="h-10 w-10" />
                </div>
                <span class="font-bold text-4xl">{{ $myCurrentMonthCompletedTask }}
                    <span class="text-xs">/{{ $myCurrentMonthTask }}</span>
                </span>
                <span class="text-[10px]">{{ $myCurrentMonthCompletionRate }}% Completion Rate</span>  
                
                
            </div>

            <div class="flex flex-col justify-between px-4 py-3">
                <span class="text-xs">Tasks last month</span>
                <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <x-filament::loading-indicator class="h-10 w-10" />
                </div>
                <span class="font-bold text-4xl">{{ $myPreviousMonthCompletedTask }}
                    <span class="text-xs">/{{ $myPreviousMonthTask }}</span>
                </span>
                
                <span class="text-[10px]">{{ $myPreviousMonthCompletionRate }}% Completion Rate</span> 
                
            </div>

       
        @endif
    </div>

    </x-filament::section>
</x-filament-widgets::widget>
