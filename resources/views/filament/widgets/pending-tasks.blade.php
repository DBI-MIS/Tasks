<x-filament-widgets::widget>
    <div class="text-md font-bold mb-4">
        {{ \App\Filament\Widgets\PendingTasks::getHeading() }}
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
        @forelse ($tasks as $task)
            <div class="px-4 py-3 {{ $task->bg_color ?? 'bg-gray-100' }} rounded-lg">
                <div class="flex flex-col items-start pb-2 {{ $task->text_color ?? 'text-gray-700' }}">
                    <div>{{ $task->title ?? 'No Title' }}</div>
                </div>

                <div class="flex flex-row gap-2 {{ $task->text_color ?? 'text-gray-700' }}">
                    <div class="text-[10px] border rounded-lg px-2 py-1 {{ $task->bg_color ?? 'bg-gray-100' }}">
                        {{ $task->project ?? 'No Project' }}
                    </div>
                    <div class="text-[10px] border rounded-lg px-2 py-1 {{ $task->bg_color ?? 'bg-gray-100' }}">
                        {{ $task->status ?? 'No Status' }}
                    </div>
                </div>
                <div class="flex items-start gap-1 pt-2 {{ $task->text_color ?? 'text-gray-700' }}">
                    <span class="text-[10px]">Created:</span>
                    <div class="text-xs">{{ $task->created_at ? $task->created_at->diffForHumans() : 'Unknown' }}</div>
                </div>
            </div>
        @empty
            <div class="text-gray-500 text-sm">
                No pending tasks available.
            </div>
        @endforelse
    </div>
</x-filament-widgets::widget>
