<x-filament-widgets::widget wire:init="getViewData">
    <x-filament::section>
        <div class="flex flex-col gap-2 justify-between flex-wrap">
            @if (auth()->check() && auth()->user()->role === 'ADMIN')
                <div class="flex flex-row  w-full gap-6 items-center ">
                    <div class="flex flex-row items-center  ">
                        <div wire:loading
                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                            <x-filament::loading-indicator class="h-10 w-10" />
                        </div>
                        <span class="font-bold text-[42pt] w-full text-right" style="min-width: 80px">{{ $totalPendingTasks }}</span>
                        </span>
                    </div>
                    <div class="flex flex-col w-48">
                        <span class="text-sm">Total Pending Tasks</span>
                        <div class="flex flex-col w-full">
                            <span class="font-bold text-sm">{{ $totalTask }} <span class="text-[10px]">Total
                                    Tasks</span></span>
                            </span>

                            
                            <div class="h-3 w-full relative">
                                <div class="h-2 bg-gray-200 rounded-full absolute w-full"></div>
                                <div class="h-2 absolute rounded-full bg-blue-600 "
                                    style="width: {{ $totalCompletionRate }}%"></div>
                            </div>
                            <span class="text-[10px]">{{ $totalCompletionRate }}% Completion Rate</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row  w-full gap-6 items-center ">
                    <div class="flex flex-row items-center ">
                        <span class="font-bold text-[42pt] w-full text-right"
                            style="min-width: 80px">{{ $totalCurrentMonthPendingTasks }}

                        </span>
                    </div>
                    <div class="flex flex-col w-48">
                        <span class="text-sm">Pending Tasks this month</span>
                        <div class="flex flex-col w-full">
                            <span class="font-bold text-sm w-full">{{ $currentMonthAllCompletedTask }}
                                <span class="text-[10px]">Tasks this month</span>
                            </span>
                            <div class="h-3 w-full relative">
                                <div class="h-2 bg-gray-200 rounded-full absolute w-full"></div>
                                <div class="h-2 absolute rounded-full bg-blue-600 "
                                    style="width: {{ $currentMonthCompletionRate }}%"></div>
                            </div>
                            <span class="text-[10px]">{{ $currentMonthCompletionRate }}% Completion Rate</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row  w-full gap-6 items-center ">
                    <div class="flex flex-row items-center  ">
                        <span class="font-bold text-[42pt] w-full text-right"
                            style="min-width: 80px">{{ $totalPreviousMonthPendingTasks }}
                        </span>
                    </div>
                    <div class="flex flex-col w-48">
                        <span class="text-sm">Pending Tasks last month</span>
                        <div class="flex flex-col w-full">
                            <span class="font-bold text-sm ">{{ $previousMonthAllCompletedTask }}
                                <span class="text-[10px]">Tasks last month</span>
                            </span>
                            <div class="h-3 w-full relative">
                                <div class="h-2 bg-gray-200 rounded-full absolute w-full"></div>
                                <div class="h-2 absolute rounded-full bg-blue-600 "
                                    style="width: {{ $previousMonthCompletionRate }}%"></div>
                            </div>
                            <span class="text-[10px]">{{ $previousMonthCompletionRate }}% Completion Rate</span>
                        </div>

                    </div>
                </div>
            @endif
            @if (auth()->check() && auth()->user()->role !== 'ADMIN')
                <div class="flex flex-row w-full gap-6 items-center ">
                    <div class="flex flex-row items-center  ">
                        <span class="font-bold text-[42pt] w-full text-right"
                            style="min-width: 80px">{{ $myTotalPendingTasks }}</span>
                        </span>
                    </div>
                    <div class="flex flex-col w-48">
                        <span class="text-sm">Total Pending Tasks</span>
                        <div class="flex flex-col w-full">
                            <span class="text-sm font-bold">{{ $myTotalTasks }} <span class="text-[10px]">Total
                                    Tasks</span></span>
                                    <div class="h-3 w-full relative">
                                        <div class="h-2 bg-gray-200 rounded-full absolute w-full"></div>
                                        <div class="h-2 absolute rounded-full bg-blue-600 "
                                            style="width: {{ $myTotalCompletionRate }}%"></div>
                                    </div>
                            <span class="text-[10px]">{{ $myTotalCompletionRate }}% Completion Rate</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row  w-full gap-6 items-center ">
                    <div class="flex flex-row items-center ">
                        <span class="font-bold text-[42pt] w-full text-right"
                            style="min-width: 80px">{{ $myTotalCurrentMonthPendingTasks }}
                        </span>
                    </div>
                    <div class="flex flex-col w-48">
                        <span class="text-sm">Pending Tasks this month</span>
                        <div class="flex flex-col w-full">
                            <span class="text-sm font-bold">{{ $myCurrentMonthTask }}
                                <span class="text-[10px]">Tasks this month</span>
                            </span>
                            <div class="h-3 w-full relative">
                                <div class="h-2 bg-gray-200 rounded-full absolute w-full"></div>
                                <div class="h-2 absolute rounded-full bg-blue-600 "
                                    style="width: {{ $myCurrentMonthCompletionRate }}%"></div>
                            </div>
                            <span class="text-[10px]">{{ $myCurrentMonthCompletionRate }}% Completion Rate</span>
                        </div>
                    </div>
                </div>


                <div class="flex flex-row  w-full gap-6 items-center ">
                    <div class="flex flex-row items-center  ">
                        <span class="font-bold text-[42pt] w-full text-right"
                            style="min-width: 80px">{{ $myTotalPreviousMonthPendingTasks }}
                        </span>
                    </div>
                    <div class="flex flex-col w-48">
                        <span class="text-sm">Pending Tasks last month</span>
                        <div class="flex flex-col w-full">
                            <span class="text-sm font-bold">{{ $myPreviousMonthCompletedTask }}
                                <span class="text-[10px]">Tasks last month</span>
                            </span>
                            <div class="h-3 w-full relative">
                                <div class="h-2 bg-gray-200 rounded-full absolute w-full"></div>
                                <div class="h-2 absolute rounded-full bg-blue-600 "
                                    style="width: {{ $myPreviousMonthCompletionRate }}%"></div>
                            </div>
                            <span class="text-[10px]">{{ $myPreviousMonthCompletionRate }}% Completion Rate</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
