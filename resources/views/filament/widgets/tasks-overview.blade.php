<x-filament-widgets::widget wire:init="getViewData">
    @if (auth()->check() && auth()->user()->role === 'ADMIN')
        <div class="space-y-4">
            <x-filament::section>

                <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <x-filament::loading-indicator class="h-10 w-10" />
                </div>


                <div class="flex flex-row w-full gap-6 items-center ">

                    <div class="relative">
                        <!-- Background circle -->
                        <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200" d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor"
                                stroke-width="4" />
                            <!-- Progress circle -->
                            <path class="text-blue-500" d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="100"
                                stroke-dashoffset="{{ 100 - $totalCompletionRate }}" />
                        </svg>
                        <!-- Percentage text -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-semibold text-blue-500">{{ $totalCompletionRate }}%</span>
                        </div>
                    </div>


                    <div class="flex flex-col w-48">
                        <span class="font-bold text-5xl w-full">{{ $totalPendingTasks }}</span>
                        <span class="text-sm">Total Pending Tasks</span>
                        <div class="flex flex-col w-full">
                            <span class="font-bold text-sm">{{ $totalTask }} <span class="text-[10px]">Total
                                    Tasks</span></span>


                        </div>
                    </div>
                </div>

            </x-filament::section>

            <x-filament::section>
                <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <x-filament::loading-indicator class="h-10 w-10" />
                </div>

                <div class="flex flex-row w-full gap-6 items-center ">

                    <div class="relative">
                        <!-- Background circle -->
                        <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200" d="M18 2.0845
                        a 15.9155 15.9155 0 0 1 0 31.831
                        a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" />
                            <!-- Progress circle -->
                            <path class="text-blue-500" d="M18 2.0845
                        a 15.9155 15.9155 0 0 1 0 31.831
                        a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4"
                                stroke-linecap="round" stroke-dasharray="100"
                                stroke-dashoffset="{{ 100 - $currentMonthCompletionRate }}" />
                        </svg>
                        <!-- Percentage text -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-semibold text-blue-500">{{ $currentMonthCompletionRate }}%</span>
                        </div>
                    </div>


                    <div class="flex flex-col w-48">
                        <span class="font-bold text-5xl w-full">{{ $totalCurrentMonthPendingTasks }}</span>
                        <span class="text-sm">Pending Tasks this month</span>
                        <div class="flex flex-col w-full">
                            <span class="font-bold text-sm w-full">{{ $currentMonthAllCompletedTask }}
                                <span class="text-[10px]">Tasks this month</span>
                            </span>

                        </div>
                    </div>
                </div>

            </x-filament::section>

            <x-filament::section>

                <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <x-filament::loading-indicator class="h-10 w-10" />
                </div>

                <div class="flex flex-row  w-full gap-6 items-center ">

                    <div class="relative">
                        <!-- Background circle -->
                        <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" />
                            <!-- Progress circle -->
                            <path class="text-blue-500" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4"
                                stroke-linecap="round" stroke-dasharray="100"
                                stroke-dashoffset="{{ 100 - $previousMonthCompletionRate }}" />
                        </svg>
                        <!-- Percentage text -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-semibold text-blue-500">{{ $previousMonthCompletionRate }}%</span>
                        </div>
                    </div>

                    <div class="flex flex-col w-48">
                        <span class="font-bold text-5xl w-full">{{ $totalPreviousMonthPendingTasks }}
                        </span>
                        <span class="text-sm">Pending Tasks last month</span>
                        <div class="flex flex-col w-full">
                            <span class="font-bold text-sm ">{{ $previousMonthAllCompletedTask }}
                                <span class="text-[10px]">Tasks last month</span>
                            </span>
                        </div>

                    </div>
                </div>


            </x-filament::section>
        </div>
    @endif


    @if (auth()->check() && auth()->user()->role !== 'ADMIN')

        <div class="space-y-4">

            <x-filament::section>

                <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <x-filament::loading-indicator class="h-10 w-10" />
                </div>

                <div class="flex flex-row  w-full gap-6 items-center ">

                    <div class="relative">
                        <!-- Background circle -->
                        <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200" d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor"
                                stroke-width="4" />
                            <!-- Progress circle -->
                            <path class="text-blue-500" d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="100"
                                stroke-dashoffset="{{ 100 - $myTotalCompletionRate }}" />
                        </svg>
                        <!-- Percentage text -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-semibold text-blue-500">{{ $myTotalCompletionRate }}%</span>
                        </div>
                    </div>


                    <div class="flex flex-col w-48">
                        <span class="font-bold text-5xl w-full">{{ $myTotalPendingTasks }}</span>
                        <span class="text-sm">Total Pending Tasks</span>
                        <div class="flex flex-col w-full">
                            <span class="text-sm font-bold">{{ $myTotalTasks }} <span class="text-[10px]">Total
                                    Tasks</span></span>
                        </div>
                    </div>
                </div>

            </x-filament::section>

            <x-filament::section>

                <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <x-filament::loading-indicator class="h-10 w-10" />
                </div>

                <div class="flex flex-row  w-full gap-6 items-center ">

                    <div class="relative">
                        <!-- Background circle -->
                        <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200" d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor"
                                stroke-width="4" />
                            <!-- Progress circle -->
                            <path class="text-blue-500" d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="100"
                                stroke-dashoffset="{{ 100 - $myCurrentMonthCompletionRate }}" />
                        </svg>
                        <!-- Percentage text -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span
                                class="text-xl font-semibold text-blue-500">{{ $myCurrentMonthCompletionRate }}%</span>
                        </div>
                    </div>

                    <div class="flex flex-col w-48">
                        <span class="font-bold text-5xl w-full">{{ $myTotalCurrentMonthPendingTasks }}
                        </span>
                        <span class="text-sm">Pending Tasks this month</span>
                        <div class="flex flex-col w-full">
                            <span class="text-sm font-bold">{{ $myCurrentMonthTask }}
                                <span class="text-[10px]">Tasks this month</span>
                            </span>
                        </div>
                    </div>
                </div>


            </x-filament::section>

            <x-filament::section>

                <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <x-filament::loading-indicator class="h-10 w-10" />
                </div>

                <div class="flex flex-row  w-full gap-6 items-center ">

                    <div class="relative">
                        <!-- Background circle -->
                        <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200" d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor"
                                stroke-width="4" />
                            <!-- Progress circle -->
                            <path class="text-blue-500" d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="100"
                                stroke-dashoffset="{{ 100 - $myPreviousMonthCompletionRate }}" />
                        </svg>
                        <!-- Percentage text -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span
                                class="text-xl font-semibold text-blue-500">{{ $myPreviousMonthCompletionRate }}%</span>
                        </div>
                    </div>


                    <div class="flex flex-col w-48">
                        <span class="font-bold text-5xl w-full">{{ $myTotalPreviousMonthPendingTasks }}
                        </span>
                        <span class="text-sm">Pending Tasks last month</span>
                        <div class="flex flex-col w-full">
                            <span class="text-sm font-bold">{{ $myPreviousMonthCompletedTask }}
                                <span class="text-[10px]">Tasks last month</span>
                            </span>
                        </div>
                    </div>
                </div>

        </div>


        </x-filament::section>

        <div class="space-y-4">
            
    @endif
</x-filament-widgets::widget>
