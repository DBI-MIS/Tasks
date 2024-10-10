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
                            <x-icon-note-alt class="size-8"/>
                        <span class="text-xs font-light">My Notes</span>
                    </div>
                    </div>
                </abbr>
                <abbr title="My Tasks" class="no-underline">
                <div href="{{ route('filament.admin.pages.tasks-kanban-board') }}" type="button" wire:navigate
                    class="text-nowrap text-center cursor-pointer text-sm text-white font-bold bg-sky-700 hover:bg-blue-900 w-full rounded-md py-2 px-2 sm:w-max" style="min-width: 90px">
                    <div class="flex flex-col items-center">

                        <x-icon-task class="size-8"/>
                    <span class="text-xs font-light">Tasks</span>
                    </div>
                </div>
            </abbr>
            <abbr title="Completed Tasks" class="no-underline">
                <div href="{{ route('filament.admin.pages.completed-task-board') }}" type="button" wire:navigate
                    class="text-nowrap text-center cursor-pointer text-sm text-white font-bold bg-sky-700 hover:bg-blue-900 w-full rounded-md py-2 px-2 sm:w-max" style="min-width: 90px">
                    <div class="flex flex-col items-center">
                        <x-icon-task-list class="size-8"/>
                    <span class="text-xs font-light">Completed</span>
                    </div>
                </div>
            </abbr>
            </div>





        </div>



    </x-filament::section>
</x-filament-widgets::widget>
