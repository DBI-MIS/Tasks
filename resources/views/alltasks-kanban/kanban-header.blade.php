{{-- <h3 class="mb-2 px-4 font-semibold text-md text-gray-400">
    <span class="text-primary-400">❖</span>
    {{ $status['title'] }}
    <span class="text-xs">({{ count($status['records'])}})</span>
</h3> --}}
<h3 class="font-light mx-2 text-md text-black dark:text-white">
    <x-heroicon-s-chevron-double-right class="w-5 h-5 inline-block" />
    {{ $status['title'] }}
    <span class="text-xs">({{ count($status['records']) }} {{ count($status['records']) <= 1 ? 'Task' : 'Tasks' }} )</span>
</h3>

