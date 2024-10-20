@props(['status'])


    <div wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" >
        <x-filament::loading-indicator class="h-10 w-10 mx-auto z-50" />
    </div>

    <details open class="group/main
    flex py-1 px-1 
{{ empty($status['records']) || count($status['records']) == 0 ? ' w-6 ' 
: 'w-min-max w-full' }}"
style="max-width: 500px">
        
    <summary class="block list-none cursor-pointer relative">
        @include(static::$headerView)
    </summary>
        
        <div data-status-id="{{ $status['id'] }}" class="block">

        @foreach ($status['records'] as $record)
            @include(static::$recordView)
        @endforeach
    </div>
        </details>




