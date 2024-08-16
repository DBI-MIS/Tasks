@props(['status'])

<div class="">
    @include(static::$headerView)

    @if (empty($status['records']) || count($status['records']) === 0)
            <div class="border-b-2 border-dotted text-center h-auto text-slate-200 p-2"></div>
        @endif

    <div
data-status-id="{{ $status['id'] }}"
class="flex flex-wrap gap-y-3 my-4 gap-x-2 " 
>



@foreach($status['records'] as $record)

    @include(static::$recordView)
@endforeach
</div>
</div>
