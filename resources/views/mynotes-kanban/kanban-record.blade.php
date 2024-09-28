@props(['record'])

<div id="{{ $record->getKey() }}" 
    class="record flex flex-col {{ $record->bg_color }} dark:{{ $record->bg_color }} rounded-md p-4 cursor-grab  dark:text-gray-800 border-l-8 shadow-md border-slate-300 
    {{ $record->text_color }} justify-between
    min-w-[200px] min-h-[180px] max-w-[300px] max-h-[240px] flex-1"
    @if ($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}) < 3) 
    x-data 
    x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('{{ $record->bg_color }}', 'dark:{{ $record->bg_color }}')
            }, 2000)
        " 
        @endif>

        <div class="font-semibold text-lg ">
            {{ $record->{static::$recordTitleAttribute} }}          
        </div>

        <div class="text-balance font-light text-xs ">
            {{ $record->getTrim() ?? 'No Description'}}         
        </div>
        
        @if($record->status)
        <div class="flex flex-row justify-between mb-2">
            <div class="text-balance font-light text-[10px] border rounded-md p-1">
                {{ $record->status }}          
            </div>
        </div>
        @endif
<div class="flex flex-col w-full gap-2">
<div class="flex flex-row gap-1">
    <div class="font-light text-[10px] ">
    Created {{ $record->created_at->diffForHumans() }}
    </div>
</div>

<div class="flex gap-2 justify-end">
    <button wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})"
        style="width: 20px">
        <abbr title="Edit Task">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </abbr>
    </button>
    <button 
    wire:click="deleteRecord({{ $record->id }})" 
    style="width: 20px"
    wire:confirm="Are you sure you want to delete this Note?">
    <abbr title="Delete Note">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
        stroke="currentColor" >
<path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
</svg>

    </abbr>
    </button>
    </div>

</div>
    
</div>
