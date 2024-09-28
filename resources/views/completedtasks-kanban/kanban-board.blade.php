<x-filament-panels::page>
    <span class="text-slate-400 text-[12px] hidden sm:block">Drag Up to Send Back for Review or Drag Down to Undone</span>
        <div class="">
            <div x-data wire:ignore.self >
                    @foreach($statuses as $status)
              
        
                    @include(static::$statusView)
                    
                    @endforeach
                
        
                <div wire:ignore>
                    @include(static::$scriptsView)
                </div>
            </div>
        </div>
        
            @unless($disableEditModal)
                <x-filament-kanban::edit-record-modal/>
            @endunless
        </x-filament-panels::page>
