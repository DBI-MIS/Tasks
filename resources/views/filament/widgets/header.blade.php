<x-filament-widgets::widget>
    <x-filament::section>
  
    <div class="w-full flex sm:flex-row flex-col justify-between gap-2 sm:items-center">
        <div class="pb-2">
            <h1 class="text-xl font-medium">Hi there <span class="capitalize">{{ $user->name }}</span>!</h1>
            <span class="font-medium text-md">{{ $currentTime->format('l - M d, Y') }}</span>
        </div>
   
        
      
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-max">
            <div href="{{route('filament.admin.pages.notes-board')}}" type="button"
            wire:navigate class="text-nowrap text-center cursor-pointer text-sm text-white font-bold bg-sky-700 w-full rounded-md py-2 px-2 sm:w-max">
            My Notes
            </div>
            <div href="{{route('filament.admin.pages.tasks-kanban-board')}}" type="button"
            wire:navigate class="text-nowrap text-center cursor-pointer text-sm text-white font-bold bg-sky-700 w-full rounded-md py-2 px-2 sm:w-max">
            My Tasks
            </div>
            <div href="{{route('filament.admin.pages.completed-task-board')}}" type="button"
            wire:navigate class="text-nowrap text-center cursor-pointer text-sm text-white font-bold bg-sky-700 w-full rounded-md py-2 px-2 sm:w-max">
            Completed Tasks
            </div>
        </div>
            
  

           
        
    </div>



    </x-filament::section>
</x-filament-widgets::widget>
