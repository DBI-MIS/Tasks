@if (count($status['records']) > 0)
<h3 class="font-light mx-2 text-md text-black dark:text-white">
    <x-heroicon-s-chevron-double-right class="w-5 h-5 inline-block" />
    {{ $status['title'] }}
    <span class="text-xs">({{ count($status['records']) }} {{ count($status['records']) <= 1 ? 'Task' : 'Tasks' }} )</span>
</h3>
@endif
