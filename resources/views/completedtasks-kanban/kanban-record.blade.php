@props(['record'])

<div id="{{ $record->getKey() }}"
    class="record flex flex-col {{ $record->bg_color }} dark:{{ $record->bg_color }} rounded-md p-4 cursor-grab  dark:text-gray-800 border-l-8 shadow-md border-slate-300 
    {{ $record->text_color }} justify-between
    min-h-[100px] w-full"
    style="max-width: 380px;"
    @if ($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}) < 3) 
    x-data 
    x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('{{ $record->bg_color }}', 'dark:{{ $record->bg_color }}')
            }, 3000)
        " 
        @endif>

    <div class="flex flex-col justify-between mb-2 w-full">
        <div class="flex flex-row justify-between mb-2 items-center">
            <div class="font-light text-[10px] {{ $record->text_color }}">
                Created {{ $record->created_at->diffForHumans() }}
                </div>
                @if ($record['urgent'])
                <div class="flex items-center">
                
                <x-heroicon-s-star class="top-0 right-0  w-5 h-5 inline-block" />
            </div>
            @endif
        </div>
        
        <div class="text-md ">
            
            {{ $record->{static::$recordTitleAttribute} }}

          
        </div>  

</div>

    {{-- <div class="flex flex-row hover:space-x-1 -space-x-2 mt-1 mb-2 ">
        
        @foreach ($record['team'] as $member)
        <div
            class="w-6 h-6 rounded-full shadow shadow-gray-400 {{ $record->bg_color }} border-2 border-white pt-[2px] text-center items-center transition-all ease-in-out group relative">
            <span class="text-xs text-center absolute -translate-x-1/2">{{ strtoupper($member->name[0]) }}</span>
            <span
                class="group-hover:opacity-100 transition-opacity bg-slate-300 p-2 text-sm text-slate-600 rounded-md absolute left-1/2 
        -translate-x-1/2 translate-y-1/3 opacity-0 m-4 mx-auto z-10 block w-[100px]">{{ $member->name }}</span>
        </div>
    @endforeach


    </div> --}}
    {{-- <span class="text-xs font-light"> {{ $record->progress }}% Progress</span>
    <div class="h-3 w-full relative mb-2">
        <div class="h-1 bg-gray-200 rounded-full absolute w-full"></div>
        <div class="h-1 absolute rounded-full bg-blue-600 " style="width: {{ $record->progress }}%"></div>
    </div> --}}

    <div class="w-full flex flex-row justify-between items-center">
        <span class="font-light text-[10px] float-left">
            Updated {{ $record->updated_at->diffForHumans() }}
        </span>
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
    </div>
</div>
