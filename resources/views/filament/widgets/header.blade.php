<x-filament-widgets::widget>
    <x-filament::section>

        <div class="w-full flex sm:flex-row flex-col justify-between gap-2 sm:items-center">
            <div class="pb-2">
                <h1 class="text-xl font-medium">Hi there <span class="capitalize">{{ $user->name }}</span>!</h1>
                <span class="font-medium text-md">{{ $currentTime->format('l - M d, Y') }}</span>
            </div>



            <div class="flex flex-row gap-2 sm:w-max">
                <abbr title="My Notes" class="no-underline">
                    <div href="{{ route('filament.admin.pages.notes-board') }}" type="button" wire:navigate
                        class="text-nowrap text-center cursor-pointer text-sm text-white font-bold bg-sky-700 hover:bg-blue-900 w-full rounded-md py-2 px-2 sm:w-max" style="min-width: 90px">
                        <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 sm:size-6">
                            <path
                                d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                            <path
                                d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                        </svg>
                        <span class="text-xs font-light">My Notes</span>
                    </div>
                    </div>
                </abbr>
                <abbr title="My Tasks" class="no-underline">
                <div href="{{ route('filament.admin.pages.tasks-kanban-board') }}" type="button" wire:navigate
                    class="text-nowrap text-center cursor-pointer text-sm text-white font-bold bg-sky-700 hover:bg-blue-900 w-full rounded-md py-2 px-2 sm:w-max" style="min-width: 90px">
                    <div class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 sm:size-6">
                        <path fill-rule="evenodd"
                            d="M17.663 3.118c.225.015.45.032.673.05C19.876 3.298 21 4.604 21 6.109v9.642a3 3 0 0 1-3 3V16.5c0-5.922-4.576-10.775-10.384-11.217.324-1.132 1.3-2.01 2.548-2.114.224-.019.448-.036.673-.051A3 3 0 0 1 13.5 1.5H15a3 3 0 0 1 2.663 1.618ZM12 4.5A1.5 1.5 0 0 1 13.5 3H15a1.5 1.5 0 0 1 1.5 1.5H12Z"
                            clip-rule="evenodd" />
                        <path
                            d="M3 8.625c0-1.036.84-1.875 1.875-1.875h.375A3.75 3.75 0 0 1 9 10.5v1.875c0 1.036.84 1.875 1.875 1.875h1.875A3.75 3.75 0 0 1 16.5 18v2.625c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625v-12Z" />
                        <path
                            d="M10.5 10.5a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963 5.23 5.23 0 0 0-3.434-1.279h-1.875a.375.375 0 0 1-.375-.375V10.5Z" />
                    </svg>
                    <span class="text-xs font-light">Tasks</span>
                    </div>
                </div>
            </abbr>
            <abbr title="Completed Tasks" class="no-underline">
                <div href="{{ route('filament.admin.pages.completed-task-board') }}" type="button" wire:navigate
                    class="text-nowrap text-center cursor-pointer text-sm text-white font-bold bg-sky-700 hover:bg-blue-900 w-full rounded-md py-2 px-2 sm:w-max" style="min-width: 90px">
                    <div class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 sm:size-6">
                        <path fill-rule="evenodd"
                            d="M17.663 3.118c.225.015.45.032.673.05C19.876 3.298 21 4.604 21 6.109v9.642a3 3 0 0 1-3 3V16.5c0-5.922-4.576-10.775-10.384-11.217.324-1.132 1.3-2.01 2.548-2.114.224-.019.448-.036.673-.051A3 3 0 0 1 13.5 1.5H15a3 3 0 0 1 2.663 1.618ZM12 4.5A1.5 1.5 0 0 1 13.5 3H15a1.5 1.5 0 0 1 1.5 1.5H12Z"
                            clip-rule="evenodd" />
                        <path
                            d="M3 8.625c0-1.036.84-1.875 1.875-1.875h.375A3.75 3.75 0 0 1 9 10.5v1.875c0 1.036.84 1.875 1.875 1.875h1.875A3.75 3.75 0 0 1 16.5 18v2.625c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625v-12Z" />
                        <path
                            d="M10.5 10.5a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963 5.23 5.23 0 0 0-3.434-1.279h-1.875a.375.375 0 0 1-.375-.375V10.5Z" />
                    </svg>
                    <span class="text-xs font-light">Completed</span>
                    </div>
                </div>
            </abbr>
            </div>





        </div>



    </x-filament::section>
</x-filament-widgets::widget>
