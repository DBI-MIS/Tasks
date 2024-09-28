@props(['status'])

<div class="flex-1">
    <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" >
        <x-filament::loading-indicator class="h-10 w-10 mx-auto z-50" />
    </div>

    <details open class="group/main">
        
    <summary class="flex flex-row justify-between list-none cursor-pointer">
        @include(static::$headerView)
        <span class="transition group-open/main:rotate-180 text-black">
            <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor"
                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24"
                width="24">
                <path d="M6 9l6 6 6-6">
                </path>
    
            </svg>
        </span>
    </summary>
        
    
        @if (empty($status['records']) || count($status['records']) === 0)
            {{-- <div class="border-b-2 border-dotted text-center h-auto text-slate-200 p-2">Drop Task Below Here</div> --}}


            <div class="hidden sm:block border-b-2 border-dotted text-center h-auto text-slate-200 p-2">
                Drop Task Below Here
                
            </div>
            <div class="w-full text-center py-2">
            <button
        wire:click="mountAction('task')"
        style="width: 20px"
        class="hover:text-blue-800 text-slate-400"
        <abbr title="Create Task">
           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>

        </abbr>
        </button>
    </div>
        @endif
    
        
    <div data-status-id="{{ $status['id'] }}" class="block gap-x-2 gap-y-3 mb-7 mt-7 my-4 sm:max-w-full">

        @foreach ($status['records'] as $record)
            @include(static::$recordView)
        @endforeach
    </div>
        </details>



        

       
       {{-- <x-filament::actions
       :actions="$this->getHeaderActions()"/>
        --}}


</div>
