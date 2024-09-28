<script>
    
    function onStart() {
        setTimeout(() => document.body.classList.add("grabbing"), 1000)
        
    }

    function onEnd() {
        document.body.classList.remove("grabbing")
    }

    function setData(dataTransfer, el) {
        dataTransfer.setData('id', el.id)
    }

    function onAdd(e) {
        const recordId = e.item.id
        const status = e.to.dataset.statusId
        const fromOrderedIds = [].slice.call(e.from.children).map(child => child.id)
        const toOrderedIds = [].slice.call(e.to.children).map(child => child.id)

        Livewire.dispatch('status-changed', {recordId, status, fromOrderedIds, toOrderedIds})
    }


    function onDelete(e) {
    const recordId = e.item.id;

    Livewire.dispatch('delete-record', {recordId});
}


    function onUpdate(e) {
        const recordId = e.item.id
        const status = e.from.dataset.statusId
        const orderedIds = [].slice.call(e.from.children).map(child => child.id)

        Livewire.dispatch('sort-changed', {recordId, status, orderedIds})
    }


    document.addEventListener('livewire:navigated', () => {
    const statuses = @js($statuses->map(fn ($status) => $status['id']));

    if (window.innerWidth < 640) {
        return; 
    }

    statuses.forEach(status => {
        const element = document.querySelector(`[data-status-id='${status}']`);
        const sortableInstance = Sortable.create(element, {
            group: 'filament-kanban',
            ghostClass: 'opacity-50',
            animation: 150,
            onStart(event) {

                sortableInstance.option('disabled', true);

                setTimeout(() => {
                    sortableInstance.option('disabled', false);
                }, 1000);

                if (typeof onStart === 'function') {
                    onStart(event);
                }
            },
            onEnd,
            onUpdate,
            setData,
            onAdd,
            onDelete,
        });
    });
});

function hideElement(status) {
    const visibleElement = document.getElementById(status);
    visibleElement.classList.add('hidden');
    const textnode = `<span class="flex items-center" id="hidden_`+ status +`" x-on:click="showElement('`+ status +`');">
                @svg('heroicon-o-eye', ['class' => 'text-`+ color +` w-5 h-5']) <span class="pl-1 text-`+ color +`">`+ title +`</span>
        </span>`;
    document.getElementById("hiddenItems")
      .innerHTML += textnode;
  }
  function showElement(status) {
    const hiddenElement = document.getElementById(status);
    hiddenElement.classList.remove('hidden');
    const removeHidden = document.getElementById('hidden_' + status);
    removeHidden.remove();
  }

</script>
