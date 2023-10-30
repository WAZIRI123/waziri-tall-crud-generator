
<div wire:ignore.self x-data="{ addFeature : @entangle('addFeature'),editFeature : @entangle('editFeature'),sortingMode : @entangle('sortingMode'),sortFieldsListing : @entangle('sortFields.listing'),sortFieldsAdd : @entangle('sortFields.add'),sortFieldsEdit : @entangle('sortFields.edit')}">
    <ul>
        <li class="flex">
            <span class="cursor-pointer text-blue-500 font-medium" wire:click="showSortDialog('listing')">Listing ></h1></span>
            <x-tall-crud-tooltip>
                Change the Order of Columns displayed in the Listing
            </x-tall-crud-tooltip>
        </li>
        @if($this->addFeature)
        <li class="flex mt-4">
            <span class="cursor-pointer text-blue-500 font-medium" wire:click="showSortDialog('add')">Add Fields</span>
            <x-tall-crud-tooltip>
                Change the Order of Fields displayed in the Add Form
            </x-tall-crud-tooltip>
        </li>
        @endif
        @if($this->editFeature)
        <li class="flex mt-4">
            <span class="cursor-pointer text-blue-500 font-medium" wire:click="showSortDialog('edit')">Edit Fields</span>
            <x-tall-crud-tooltip>
                Change the Order of Fields displayed in the Edit Form
            </x-tall-crud-tooltip>
        </li>
        @endif
    </ul>
    <x-tall-crud-dialog-modal wire:model.live="confirmingSorting">
    <x-slot name="title">
        Sort Fields
    </x-slot>

    <x-slot name="content">
        <ul drag-root class="overflow-hidden rounded shadow divide-y" x-show="sortingMode=='listing'">

        <template  x-for="field in sortFieldsListing">

        
                <li x-bind:drag-item="field.type !== undefined && field.type == 'withCount') ?  field.field . ' (Count)' : field.field" draggable="true" x-bind:wire:key="field.field" class="w-64 p-4 bg-white border" x-text="`${field.field} ${field.type !== undefined && field.type === 'withCount'?'(Count)' : ''}`">
                    
                </li>

             </template>

             </ul>

             <ul drag-root class="overflow-hidden rounded shadow divide-y" x-show="sortingMode=='add'">
             <template x-for="field in sortFieldsAdd">

        
<li x-bind:drag-item="field.type !== undefined && field.type == 'withCount') ?  field.field . ' (Count)' : field.field" draggable="true" x-bind:wire:key="field.field" class="w-64 p-4 bg-white border" x-text="`${field.field} ${field.type !== undefined && field.type === 'withCount'?'(Count)' : ''}`">
    
</li>


</template>
             </ul>

             <ul drag-root class="overflow-hidden rounded shadow divide-y" x-show="sortingMode=='edit'">
         

<template  x-for="field in sortFieldsEdit">

        
<li x-bind:drag-item="field.type !== undefined && field.type == 'withCount') ?  field.field . ' (Count)' : field.field" draggable="true" x-bind:wire:key="field.field" class="w-64 p-4 bg-white border" x-text="`${field.field} ${field.type !== undefined && field.type === 'withCount'?'(Count)' : ''}`">
    
</li>

</template>
             </ul>

  
    </x-slot>

    <x-slot name="footer">
        <x-tall-crud-button mode="add" wire:click="hideSortDialog()">Done</x-tall-crud-button>
    </x-slot>
</x-tall-crud-dialog-modal>

</div>


<script>
    window.addEventListener('init-sort-events', event => {
        let root = document.querySelector('[drag-root]')
        root.querySelectorAll('[drag-item]').forEach( el => {
            el.addEventListener('dragstart' , e => {
                e.target.setAttribute('dragging', true);
            })

            el.addEventListener('drop' , e => {
                e.target.classList.remove('bg-yellow-200')
                let draggingEl = root.querySelector('[dragging]')
                e.target.before(draggingEl);
                let component = window.Livewire.find(
                    e.target.closest('[wire\\:id]').getAttribute('wire:id')
                )

                let orderIds = Array.from(root.querySelectorAll('[drag-item]'))
                    .map(itemEl => itemEl.getAttribute('drag-item'))
                component.call('reorder', orderIds);
            })
            
            el.addEventListener('dragenter' , e => {
                e.target.classList.add('bg-yellow-200')
                e.preventDefault();
            })
            el.addEventListener('dragover' , e => e.preventDefault())
            el.addEventListener('dragleave' , e => {
                e.target.classList.remove('bg-yellow-200')
            })

            el.addEventListener('dragend' , e => {
                e.target.removeAttribute('dragging');
            })
        })
    })
</script>