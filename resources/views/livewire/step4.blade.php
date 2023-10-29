<x-tall-crud-dialog-modal wire:model.live="confirmingWith">
<x-slot name="title">
    Eager Load a Relationship
</x-slot>

<x-slot name="content">
    <div class="mt-4">
        <div>
            <x-tall-crud-label>Select Relationship</x-tall-crud-label>
            <x-tall-crud-select class="block mt-1 w-1/2" wire:model.lazy="withRelation.name">
                <option value="">-Please Select-</option>
                @foreach ($allRelations as $allRelation)
                @foreach ($allRelation as $c)
                <option value="{{$c['name']}}">{{$c['name']}}</option>
                @endforeach
                @endforeach
            </x-tall-crud-select>
            @error('withRelation.name') <x-tall-crud-error-message>{{$message}}
            </x-tall-crud-error-message> @enderror
        </div>

     
        <div class="mt-4 p-4 rounded border border-gray-300" x-show="isvalidWithRelation">
            <div class="mt-4">
                <x-tall-crud-label>Display Column</x-tall-crud-label>
                <x-tall-crud-select class="block mt-1 w-1/2"
                    wire:model="withRelation.displayColumn">
                    <option value="">-Please Select-</option>

                    <template x-if="columns.contains(column)" x-for="column in columns">
                    <option x-bind:value="column" x-text="column"></option>
             </template>
                </x-tall-crud-select>
            <h1 x-text="message"></h1>
            </div>
        </div>
    
        
    </div>
</x-slot>

<x-slot name="footer">
    <x-tall-crud-button wire:click="$set('confirmingWith', false)">Cancel</x-tall-crud-button>
    <x-tall-crud-button mode="add" wire:click="addWithRelation()">Save</x-tall-crud-button>
</x-slot>
</x-tall-crud-dialog-modal>



    <x-tall-crud-accordion-header tab="1">
        Eager Load <h1 x-text="isvalidWithRelation"></h1>
        <x-slot name="help">
            Eager Load a Related Model to display in Listing
        </x-slot>
    </x-tall-crud-accordion-header>

    <x-tall-crud-accordion-wrapper   ref="advancedTab1" tab="1">
        <x-tall-crud-button class="mt-4" wire:click="createNewWithRelation">Add
        </x-tall-crud-button>
        <x-tall-crud-table class="mt-4">
            <x-slot name="header">
                <x-tall-crud-table-column>Relation</x-tall-crud-table-column>
                <x-tall-crud-table-column>Display Column</x-tall-crud-table-column>
                <x-tall-crud-table-column>Actions</x-tall-crud-table-column>
            </x-slot>

                 <template  x-for="(withRelation,i) in withRelations" :key="i">
                                <tr>
                <x-tall-crud-table-column x-text="withRelation.relationName"></x-tall-crud-table-column>
                <x-tall-crud-table-column x-text="withRelation.displayColumn"></x-tall-crud-table-column>
                <x-tall-crud-table-column>
                    <x-tall-crud-button wire:click="deleteWithRelation(i)" mode="delete">
                        Delete
                    </x-tall-crud-button>
                </x-tall-crud-table-column>
            </tr>
             </template>

        </x-tall-crud-table>
    </x-tall-crud-accordion-wrapper>