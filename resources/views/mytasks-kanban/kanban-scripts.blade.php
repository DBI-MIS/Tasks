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

function onMarkAsDone(e) {
    const recordId = e.item.id;

    Livewire.dispatch('mark-as-done', {recordId});
}

function onMarkAsOnHold(e) {
    const recordId = e.item.id;

    Livewire.dispatch('mark-as-on-hold', {recordId});
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
            onMarkAsDone,
            onMarkAsOnHold,
        });
    });
});

</script>
