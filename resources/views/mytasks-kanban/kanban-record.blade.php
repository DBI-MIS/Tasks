@props(['record'])

<div id="{{ $record->getKey() }}"
    class="record flex flex-col {{ $record->bg_color }} dark:{{ $record->bg_color }} rounded-md p-4 cursor-grab  dark:text-gray-800 border-l-8 shadow-md border-slate-300 
    {{ $record->text_color }} justify-between mb-3"
     
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


    <div class="flex flex-col justify-between mb-2">

       

            <details class="group/sub w-full">
                <summary class="flex flex-row justify-between list-none cursor-pointer my-auto">
                    <div class="text-balance">
                        @if ($record['urgent'])
                            <x-heroicon-s-star class="top-0 right-0 {{ $record->text_color }} w-5 h-5 inline-block" />
                        @endif
                        {{ $record->{static::$recordTitleAttribute} }}
                    </div>

                    <span class="transition group-open/sub:rotate-180 h-min">
                        <abbr title="Details">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24"
                            width="24">
                            <path d="M6 9l6 6 6-6">
                            </path>
                        </svg>
                    </abbr>
                    </span>

                </summary>
                <div class="px-2">
                    <div class="text-xs font-light border-l-2 border-slate-200 pl-2 mb-2">
                        {{ $record->getTrim() ?? 'No Description' }}
                    </div>
                </div>
            </details>
       


        <div class="flex flex-row gap-x-2 my-2">
            <div
                class="w-8 h-8 rounded-full shadow shadow-gray-400 {{ $record->bg_color }} border-2 border-white pt-[2px] text-center items-center">
                <span class="text-sm">{{ strtoupper($record->user?->name[0]) }}</span>
            </div>
            <div>
                <div class="font-light text-sm">
                    {{ $record->user->name ?? 'No Owner' }}
                </div>
                <div class="font-light text-xs">
                    Created {{ $record->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>


    <div class="flex flex-row hover:space-x-1 -space-x-2 mb-2 ">
        @foreach ($record['team'] as $member)
            <div
                class="w-6 h-6 rounded-full shadow shadow-gray-400 {{ $record->bg_color }} border-2 border-white pt-[2px] text-center items-center transition-all ease-in-out group relative">
            <abbr title="{{ $member->name }}">
                <span class="text-xs text-center absolute -translate-x-1/2">{{ strtoupper($member->name[0]) }}</span>
            </abbr>
                
            </div>
        @endforeach


    </div>
    <span class="text-xs font-light m-y-5"> {{ $record->progress }}% Progress</span>
    <div class="h-3 w-full relative">
        <div class="h-1 bg-gray-200 rounded-full absolute w-full"></div>
        <div class="h-1 absolute rounded-full bg-blue-600 " style="width: {{ $record->progress }}%"></div>
    </div>

    <div class="w-full flex flex-row justify-between items-center">
        <span class="font-light text-xs float-left">
            Updated {{ $record->updated_at->diffForHumans() }}
        </span> 
        <div class="flex gap-2">
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
            wire:confirm="Are you sure you want to delete this task?">
            <abbr title="Delete Task">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                stroke="currentColor" >
      <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
    </svg>
    
            </abbr>
            </button>
            </div>

        
    </div>

</div>
