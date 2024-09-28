<x-filament-panels::page>
    
    <div class="w-full">
    <div x-data wire:ignore.self class="flex flex-col sm:flex-row  gap-x-2">
    
            @foreach($statuses as $status)
            
                @include(static::$statusView)
            
            @endforeach
        
        
        <div wire:ignore>
            @include(static::$scriptsView)
        </div>
        
    </div>
    
</div>
    @unless($disableEditModal)
        <x-filament-kanban::edit-record-modal />
    @endunless
</x-filament-panels::page>

