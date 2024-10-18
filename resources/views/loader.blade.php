<div
    x-cloak
    x-show="$store.isLoading.value"
    class="fixed inset-0 flex justify-center items-center z-[6000001] bg-white/50 backdrop-blur-sm"
>
    <div class="flex gap-2 items-center">
        <div class="text-xl hidden sm:block">
            <div class="flex flex-row items-center bg-white p-4 rounded-lg shadow-lg">
                <span>Loading...</span>
                <x-filament::loading-indicator class="h-10 w-10 ml-2" />
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => Alpine.store('isLoading', {
            value: false,
            delayTimer: null,
            set(value) {
                clearTimeout(this.delayTimer);
                if (value === false) this.value = false;
                else this.delayTimer = setTimeout(() => this.value = true, 2000);
            }
        }));
        document.addEventListener("livewire:init", () => {
            Livewire.hook('commit.prepare', () => Alpine.store('isLoading').set(true));
            Livewire.hook('commit', ({succeed}) => succeed(() => queueMicrotask(() => Alpine.store('isLoading').set(false))));
        });
    </script>


</div>
