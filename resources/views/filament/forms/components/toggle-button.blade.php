<div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }" class="flex items-center space-x-2">
    <!-- Toggle Wrapper -->
    <div class="relative">
        <!-- Hidden Checkbox -->
        <input type="checkbox" x-model="state" class="sr-only" />
        
        <!-- Background of the Toggle -->
        <div class="w-12 h-6 rounded-full transition-all"
             :class="state ? 'bg-blue-500' : 'bg-gray-300'">
        </div>
        
        <!-- Draggable Dot -->
        <div class="dot absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform"
             :class="state ? 'translate-x-6' : ''">
        </div>
    </div>
    
    <!-- Toggle Text -->
    <span x-text="state ? 'On' : 'Off'"></span>
</div>
