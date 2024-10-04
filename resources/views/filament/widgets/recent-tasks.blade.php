<div class="px-4 py-3 {{$getRecord()->bg_color}} rounded-lg">
    <div class="flex flex-row justify-between pb-2 {{$getRecord()->text_color}}">
        <div class="flex flex-col items-start">
            <span class="text-[10px]">Task:</span>
            <div>

                {{ $getRecord()->title }}

            </div>
        </div>
        <div class="flex flex-col items-end">
            <span class="text-[10px]">Created:</span>
            <div class="text-xs">

                {{ $getRecord()->created_at->diffForHumans() }}

            </div>
        </div>



    </div>

    <div class="flex flex-row gap-2 {{$getRecord()->text_color}} ">
        <div class="text-[10px] border rounded-lg px-2 py-1 {{$getRecord()->bg_color}} ">

            {{ $getRecord()->project }}

        </div>

        <div class="text-[10px] border rounded-lg px-2 py-1 {{$getRecord()->bg_color}}">

            {{ $getRecord()->status }}

        </div>

    </div>






    {{-- <x-filament-tables::columns.layout :components="$getComponents()" :record="$getRecord()" :record-key="$recordKey" /> --}}

</div>
