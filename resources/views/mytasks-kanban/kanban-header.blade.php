<div class="inline-flex justify-between w-full 
{{ empty($status['records']) || count($status['records']) == 0 ? 'sm:rotate-90 text-nowrap origin-[10px_10px]  ' : 'rotate-0 origin-center' }}">

<div class="flex flex-row items-center gap-2
{{ empty($status['records']) || count($status['records']) == 0 ? 'border-b border-gray-500 ' : 'border-0' }}
 ">
    
    <h3 class="font-light text-md text-black dark:text-white">
    
        {{-- <x-heroicon-s-chevron-double-right class="w-5 h-5 inline-block" /> --}}
        {{ $status['title'] }}
        <span class="text-xs">({{ count($status['records']) }} {{ count($status['records']) <= 1 ? 'Task' : 'Tasks' }} )</span>
    </h3>

    <button
    wire:click="mountAction('task')"
    style="width: 20px"
    class="hover:text-blue-800 text-slate-400
    {{ empty($status['records']) || count($status['records']) == 0 ? 'block ' : 'hidden ' }}
     "
    <abbr title="Create Task">
       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    </svg>

    </abbr>
    </button>

</div>

<span class="transition group-open/main:rotate-180 text-black
{{ empty($status['records']) || count($status['records']) == 0 ? ' hidden ' 
: 'block ' }}
">
    <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor"
        stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24"
        width="24">
        <path d="M6 9l6 6 6-6">
        </path>

    </svg>
</span>

</div>

