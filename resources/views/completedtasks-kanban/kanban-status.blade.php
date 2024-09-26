@props(['status'])

<div class="">

    <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" >
        <x-filament::loading-indicator class="h-10 w-10 mx-auto z-50" />
    </div>
    
    <details open class="group/main">
        
    <summary class="flex flex-row justify-between list-none cursor-pointer">
        @include(static::$headerView)
        @if (empty($status['records']) || count($status['records']) === 0)
            <div class="border-b-2 border-dotted text-center h-auto text-slate-200 p-2 min-h-4  w-full">Drag Below</div>
        @endif
        <span class="transition group-open/main:rotate-180 text-black">
            <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor"
                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24"
                width="24">
                <path d="M6 9l6 6 6-6">
                </path>
    
            </svg>
        </span>
        
    </summary>

    

    <div
data-status-id="{{ $status['id'] }}"
class="flex flex-wrap gap-y-3 my-4 gap-x-2 sm:max-w-full" 
>



@foreach($status['records'] as $record)

    @include(static::$recordView)
@endforeach
</div>
    </details>
</div>
