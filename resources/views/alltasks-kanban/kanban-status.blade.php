
{{-- @props(['status'])

<div class="max-w-[500px] flex-1">
    @include(static::$headerView)
    <div data-status-id="{{ $status['id'] }}" class="flex-wrap gap-x-2 gap-y-3 mb-7 mt-7">
        
        <div class="bg-red-500 h-10">Dropzone</div>



        @foreach ($status['records'] as $record)
            @include(static::$recordView)
        @endforeach
    </div>
</div> --}}


@props(['status'])

<div class="max-w-[500px] flex-1">
    {{-- Include the header view --}}
    @include(static::$headerView)

    {{-- Container for the status records --}}
    <div data-status-id="{{ $status['id'] }}" class="flex-wrap gap-x-2 gap-y-3 mb-7 mt-7">
        
        {{-- Conditionally display the dropzone if status count is 0 or null --}}
        @if (empty($status['records']) || count($status['records']) === 0)
            <div class="bg-slate-200/50 rounded-lg text-center h-auto text-white py-5">Dropzone</div>
        @endif

        {{-- Loop through each record in the status and include the record view --}}
        @foreach ($status['records'] as $record)
            @include(static::$recordView)
        @endforeach
    </div>
</div>
