@props(['status'])

<div class="max-w-[500px] flex-1">
    {{-- Include the header view --}}
    @include(static::$headerView)

    {{-- Conditionally display the dropzone if status count is 0 or null --}}
    @if (empty($status['records']) || count($status['records']) === 0)
            <div class="border-b-2 border-dotted text-center h-auto text-slate-200 p-2">Drop Task Below Here</div>
        @endif

    {{-- Container for the status records --}}
    <div data-status-id="{{ $status['id'] }}" class="flex-wrap gap-x-2 gap-y-3 mb-7 mt-7">

        {{-- Loop through each record in the status and include the record view --}}
        @foreach ($status['records'] as $record)
            @include(static::$recordView)
        @endforeach
    </div>
</div>
