{{-- <x-filament-widgets::widget wire:init="getViewData"> --}}
    

<div class="px-4 py-3 {{$getRecord()->bg_color}} rounded-lg">
    <div class="flex flex-col items-start pb-2 {{$getRecord()->text_color}}">
        
            <div>

                {{ $getRecord()->title }}

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

    <div class="flex items-start gap-1 pt-2 {{ $getRecord()->text_color }}">
        <span class="text-[10px]">Created:</span>
        <div class="text-xs">

            {{ $getRecord()->created_at->diffForHumans() }}

        </div>
    </div>





</div>
{{-- </x-filament-widgets::widget> --}}
