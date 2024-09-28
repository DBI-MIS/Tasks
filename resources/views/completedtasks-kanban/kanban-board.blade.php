<x-filament-panels::page>
    
<div class="mb-4">
    
    <form wire:submit.prevent="filterByDate">
        <div class="flex justify-end space-x-4 flex-col sm:flex-row">
            
            <div>
                <x-filament::input
                    label="Start Date"
                    type="date" 
                    wire:model="startDate"  
                />
            </div>
            <div>
                <x-filament::input
                    label="End Date"
                    type="date" 
                    wire:model="endDate"  
                />
            </div>
            <div>
                <x-filament::button type="submit" color="primary" class="w-full">
                    Search
                </x-filament::button>
            </div>
            
        </div>
    </form>
</div>

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
