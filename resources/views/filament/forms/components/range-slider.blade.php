<div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }" class="flex items-center gap-2 w-full">
    <div class="flex flex-col items-center w-full">
     <span class="text-xs mb-2">Progress<span x-text="' '+ state + '%'"></span></span>
    <input 
        type="range" 
        x-model="state" 
        min="1" 
        max="100"
        class="h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-900"
        :style="'background: linear-gradient(to right, #3b82f6 ' + state + '%, #e5e7eb ' + state + '%);  width: 100%'"
        
        
    />
    </div>
{{-- <div>
 <abbr title="Set to 100%" >
 {{ $getAction('setDone') }} 
 </abbr>
 </div> --}}

   
    
</div>