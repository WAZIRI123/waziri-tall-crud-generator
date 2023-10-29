<div wire:ignore.self x-data="{ selected : @entangle('selected').live,
                    
                    
                    
                    isvalidWithRelation: @entangle('withRelation.isValid').live,
                    
                    isvalidWithCountRelation: @entangle('withCountRelation.isValid').live,


            columns: @entangle('withRelation.columns').live,
            
            withCountRelationColumns: @entangle('withCountRelation.columns').live,
            
            displayColumn: @entangle('withRelation.displayColumn').live,
            
            withCountRelationDisplayColumn: @entangle('withCountRelation.displayColumn').live,
            
            withRelations: @entangle('withRelations').live,
            
            
            withCountRelations: @entangle('withCountRelations').live,
            

            addFeature: @entangle('addFeature').live,

            editFeature: @entangle('editFeature').live,

            isValidbelongsToManyRelation: @entangle('belongsToManyRelation.isValid').live,

            isValidbelongsToRelation: @entangle('belongsToRelation.isValid').live,


            belongsToManyRelationColumns: @entangle('belongsToManyRelation.columns').live,

          

            belongsToRelationColumns: @entangle('belongsToRelation.columns').live,


            belongsToManyRelations: @entangle('belongsToManyRelations').live,


            nameBtm: @entangle('belongsToManyRelation.name').live,

            nameBt: @entangle('belongsToRelation.name').live,

            nameBtmDisplayColumn: @entangle('belongsToManyRelation.displayColumn').live,


            nameWithRelation: @entangle('withRelation.name').live,

            nameWithCountRelation: @entangle('withCountRelation.name').live,

           
            nameWithRelationDisplayColumn: @entangle('withRelation.displayColumn').live,


            nameBtDisplayColumn: @entangle('belongsToRelation.displayColumn').live,

            belongsToRelations: @entangle('belongsToRelations').live,

            
            }">

    <div >
        
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
                <x-tall-crud-error-message x-text="nameWithRelation == '' ? 'Please select a Relation' : withRelations.find(r => r.relationName == nameWithRelation) ? 'Relation Already Defined.' : ''">
</x-tall-crud-error-message>
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
                <x-tall-crud-error-message  x-text="nameWithRelationDisplayColumn == '' ? ' displayColumn required '  : ''">
                </x-tall-crud-error-message> 
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
        Eager Load 
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
        
        

    
<x-tall-crud-dialog-modal wire:model.live="confirmingWithCount">
    <x-slot name="title">
        Eager Load Count
    </x-slot>

    <x-slot name="content">
        <div class="mt-4">
            <div>
                <x-tall-crud-label>Select Relationship</x-tall-crud-label>
                <x-tall-crud-select class="block mt-1 w-1/2" wire:model.lazy="withCountRelation.name">
                    <option value="">-Please Select-</option>
                    @foreach ($allRelations as $allRelation)
                    @foreach ($allRelation as $c)
                    <option value="{{$c['name']}}">{{$c['name']}}</option>
                    @endforeach
                    @endforeach
                </x-tall-crud-select>
                <x-tall-crud-error-message x-text="nameWithCountRelation == '' ? 'Please select a Relation' : withCountRelations.find(r => r.relationName == nameWithCountRelation) ? 'Relation Already Defined.' : ''">
</x-tall-crud-error-message>
            </div>

            <x-tall-crud-label class="mt-2" x-show="isvalidWithCountRelation">
                Make Heading Sortable
                <x-tall-crud-checkbox class="ml-2" wire:model="withCountRelation.isSortable" />
            </x-tall-crud-label>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-tall-crud-button wire:click="$set('confirmingWithCount', false)">Cancel
        </x-tall-crud-button>
        <x-tall-crud-button mode="add" wire:click="addWithCountRelation()">Save
        </x-tall-crud-button>
    </x-slot>
</x-tall-crud-dialog-modal>

<x-tall-crud-accordion-header tab="2">
            Eager Load Count
            <x-slot name="help">
                Eager Load Count of a Related Model to display in Listing
            </x-slot>
        </x-tall-crud-accordion-header>

        <x-tall-crud-accordion-wrapper ref="advancedTab2" tab="2">
            <x-tall-crud-button class="mt-4" wire:click="createNewWithCountRelation">Add
            </x-tall-crud-button>
            <x-tall-crud-table class="mt-4">
                <x-slot name="header">
                    <x-tall-crud-table-column>Relation</x-tall-crud-table-column>
                    <x-tall-crud-table-column>Sortable</x-tall-crud-table-column>
                    <x-tall-crud-table-column>Actions</x-tall-crud-table-column>
                </x-slot>
                <template  x-for="(withCountRelation,i) in withCountRelations" :key="i">
            <tr>
                <x-tall-crud-table-column x-text="withCountRelation.relationName"></x-tall-crud-table-column>
                <x-tall-crud-table-column x-text="withCountRelation.isSortable? 'Yes': 'No'">
                </x-tall-crud-table-column>
                <x-tall-crud-table-column>
                    <x-tall-crud-button wire:click.prevent="deleteWithCountRelation($i)"
                        mode="delete">
                        Delete
                    </x-tall-crud-button>
                </x-tall-crud-table-column>
            </tr>
            </template>
            </x-tall-crud-table>
        </x-tall-crud-accordion-wrapper>

 

@if ($this->addFeature || $this->editFeature)
<x-tall-crud-dialog-modal wire:model.live="confirmingBelongsToMany">
    <x-slot name="title">
        Add a Belongs to Many Relationship
    </x-slot>

    <x-slot name="content">
        <div class="mt-4">
            <div>
                <x-tall-crud-label>Select Relationship</x-tall-crud-label>
                <x-tall-crud-select class="block mt-1 w-1/2" wire:model.live="belongsToManyRelation.name">
                    <option value="">-Please Select-</option>
                    @if (Arr::exists($allRelations, 'belongsToMany'))
                    @foreach ($allRelations['belongsToMany'] as $c)
                    <option value="{{$c['name']}}">{{$c['name']}}</option>
                    @endforeach
                    @endif
                </x-tall-crud-select>
                <x-tall-crud-error-message x-text="nameBtm == '' ? 'Please select a Relation' : belongsToManyRelations.find(r => r.relationName == nameBtm) ? 'Relation Already Defined.' : ''">
</x-tall-crud-error-message>
            </div>

            <div class="mt-4 p-4 rounded border border-gray-300"
            x-show="isValidbelongsToManyRelation">
                @if ($this->addFeature)
                    <x-tall-crud-label class="mt-2">
                        Show in Add Form:
                        <x-tall-crud-checkbox class="ml-2" wire:model="belongsToManyRelation.inAdd" />
                    </x-tall-crud-label>
                @endif
                @if ($this->editFeature)
                    <x-tall-crud-label class="mt-2">
                        Show in Edit Form:
                        <x-tall-crud-checkbox class="ml-2" wire:model="belongsToManyRelation.inEdit" />
                    </x-tall-crud-label>
                @endif
                <x-tall-crud-label class="mt-2">
                    Display as Multi-Select (Default is Checkboxes):
                    <x-tall-crud-checkbox class="ml-2" wire:model="belongsToManyRelation.isMultiSelect" />
                </x-tall-crud-label>

                <div class="mt-4">
                    <x-tall-crud-label>Display Column</x-tall-crud-label>
                    <x-tall-crud-select class="block mt-1 w-1/2"
                        wire:model="belongsToManyRelation.displayColumn">
                        <option value="">-Please Select-</option>
                        <template x-if="belongsToManyRelationColumns.contains(column)" x-for="column in belongsToManyRelationColumns">
                    <option x-bind:value="column" x-text="column"></option>
             </template>
                    </x-tall-crud-select>
               
             <x-tall-crud-error-message  x-text="nameBtmDisplayColumn == '' ? ' displayColumn required '  : ''">
                </x-tall-crud-error-message> 
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-tall-crud-button wire:click="$set('confirmingBelongsToMany', false)">Cancel
        </x-tall-crud-button>
        <x-tall-crud-button mode="add" wire:click="addBelongsToManyRelation()">Save
        </x-tall-crud-button>
    </x-slot>
</x-tall-crud-dialog-modal>
@endif

@if ($this->addFeature || $this->editFeature)
        <x-tall-crud-accordion-header tab="3">
            Belongs To Many
            <x-slot name="help">
                Display BelongsToMany Relation Field in Add and Edit Form
            </x-slot>
        </x-tall-crud-accordion-header>

        <x-tall-crud-accordion-wrapper ref="advancedTab3" tab="3">
            <x-tall-crud-show-relations-table type="belongsToManyRelations"></x-tall-crud-show-relations-table>
        </x-tall-crud-accordion-wrapper>
        @endif

@if ($this->addFeature || $this->editFeature)
<x-tall-crud-dialog-modal wire:model.live="confirmingBelongsTo">
    <x-slot name="title">
        Add a Belongs to Relationship
    </x-slot>

    <x-slot name="content">
        <div class="mt-4">
            <div>
                <x-tall-crud-label>Select Relationship</x-tall-crud-label>
                <x-tall-crud-select class="block mt-1 w-1/2" wire:model.lazy="belongsToRelation.name">
                    <option value="">-Please Select-</option>
                    @if (Arr::exists($allRelations, 'belongsTo'))
                    @foreach ($allRelations['belongsTo'] as $c)
                    <option value="{{$c['name']}}">{{$c['name']}}</option>
                    @endforeach
                    @endif
                </x-tall-crud-select>
                <x-tall-crud-error-message x-text="nameBt == '' ? 'Please select a Relation' : belongsToRelations.find(r => r.relationName == nameBt) ? 'Relation Already Defined.' : ''">
</x-tall-crud-error-message>
            </div>

 
            <div class="mt-4 p-4 rounded border border-gray-300" x-show="isValidbelongsToRelation">
                @if ($this->addFeature)
                    <x-tall-crud-label class="mt-2">
                        Show in Add Form:
                        <x-tall-crud-checkbox class="ml-2" wire:model="belongsToRelation.inAdd" />
                    </x-tall-crud-label>
                @endif
                @if ($this->editFeature)
                    <x-tall-crud-label class="mt-2">
                        Show in Edit Form:
                        <x-tall-crud-checkbox class="ml-2" wire:model="belongsToRelation.inEdit" />
                    </x-tall-crud-label>
                @endif
                <div class="mt-4">
                    <x-tall-crud-label>Display Column</x-tall-crud-label>
                    <x-tall-crud-select class="block mt-1 w-1/4"
                        wire:model="belongsToRelation.displayColumn">
                        <option value="">-Please Select-</option>
                        <template x-for="column in belongsToRelationColumns">
                    <option x-bind:value="column" x-text="column"></option>
             </template>
                    </x-tall-crud-select>
                    <x-tall-crud-error-message  x-text="nameBtDisplayColumn == '' ? ' displayColumn required '  : ''">
                </x-tall-crud-error-message> 
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-tall-crud-button wire:click="$set('confirmingBelongsTo', false)">Cancel
        </x-tall-crud-button>
        <x-tall-crud-button mode="add" wire:click="addBelongsToRelation()">Save
        </x-tall-crud-button>
    </x-slot>
</x-tall-crud-dialog-modal>
@endif

@if ($this->addFeature || $this->editFeature)
        <x-tall-crud-accordion-header tab="4">
            Belongs To
            <x-slot name="help">
                Display BelongsTo Relation Field in Add and Edit Form
            </x-slot>
        </x-tall-crud-accordion-header>

        <x-tall-crud-accordion-wrapper ref="advancedTab4" tab="4">
            <x-tall-crud-show-relations-table type="belongsToRelations"></x-tall-crud-show-relations-table>
        </x-tall-crud-accordion-wrapper>
 @endif
  


</div>
</div>