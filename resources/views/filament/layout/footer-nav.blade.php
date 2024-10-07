@auth
<footer class=" fixed bottom-0 py-4 bg-white dark:bg-gray-900 block sm:hidden w-full shadow-lg shadow-slate-700 dark:border-gray-800 border-t-[1px]">
    <div class="flex justify-evenly w-full">

        <x-nav-link 
        wire:navigate
        href="{{ route('filament.admin.pages.notes-board') }}" 
        :active="request()->routeIs('filament.admin.pages.notes-board')">
            <div class="flex flex-col items-center">
                {{ svg('icon-note-alt') }}
                  
                  
                  
                 <span class="text-[8px]">Notes</span> 
            </div>
            
        </x-nav-link>

        <x-nav-link
        wire:navigate
        href="{{ route('filament.admin.pages.tasks-kanban-board') }}" 
        :active="request()->routeIs('filament.admin.pages.tasks-kanban-board')">
            <div class="flex flex-col items-center">
                {{ svg('icon-task') }}
                  
                  
                 <span class="text-[8px]">Tasks</span> 
            </div>
              
        </x-nav-link>

        <x-nav-link 
        wire:navigate
        href="{{ route('filament.admin.pages.completed-task-board') }}"
        :active="request()->routeIs('filament.admin.pages.completed-task-board')">
            <div class="flex flex-col items-center">
                {{ svg('icon-task-list') }}
                  
                  
                  
                  
                 <span class="text-[8px]">Completed</span> 
            </div>
              
        </x-nav-link>

        @if(auth()->check() && auth()->user()->role === 'ADMIN')
    <x-nav-link
    wire:navigate
    href="{{ route('filament.admin.pages.all-tasks-board') }}"
    :active="request()->routeIs('filament.admin.pages.all-tasks-board')">
        <div class="flex flex-col items-center">
            {{ svg('icon-all-inbox') }}
              
            
            <span class="text-[8px]">All</span> 
        </div>
    </x-nav-link>
@endif
        
    </div>
    

</footer>
@endauth