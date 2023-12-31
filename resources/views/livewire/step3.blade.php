@if(count($fields) == 0)
<x-tall-crud-button wire:click.prevent="addAllFields">
    Add All Fields
</x-tall-crud-button>
@endif

<x-tall-crud-table class="mt-4">
    <x-slot name="header">
        <x-tall-crud-table-column>Column</x-tall-crud-table-column>
        <x-tall-crud-table-column>Label</x-tall-crud-table-column>
        @if (!$this->addAndEditDisabled)
        <x-tall-crud-table-column>Display In Listing</x-tall-crud-table-column>
        @endif
        @if ($this->addFeature)
        <x-tall-crud-table-column>Display In Create</x-tall-crud-table-column>
        @endif
        @if ($this->editFeature)
        <x-tall-crud-table-column>Display In Edit</x-tall-crud-table-column>
        @endif
        <x-tall-crud-table-column>Searchable</x-tall-crud-table-column>
        <x-tall-crud-table-column>Sortable</x-tall-crud-table-column>
        <x-tall-crud-table-column>Actions</x-tall-crud-table-column>
    </x-slot>
    @foreach ($fields as $i => $field)
    <tr>
        <x-tall-crud-table-column>
            <select wire:model="fields.{{$i}}.column" class="form-select rounded-md shadow-sm">
                <option value="">-Select Column-</option>
                @if (Arr::exists($this->modelProps, 'columns'))
                @foreach ($this->modelProps['columns'] as $column)
                <option value="{{$column}}">{{$column}}</option>
                @endforeach
                @endif
            </select>
        </x-tall-crud-table-column>
        <x-tall-crud-table-column>
            <x-tall-crud-input type="text" class="mt-1 block w-full" wire:model="fields.{{$i}}.label"
                placeholder="Label" />
        </x-tall-crud-table-column>
        @if (!$this->addAndEditDisabled)
        <x-tall-crud-table-column>
            <x-tall-crud-checkbox wire:model="fields.{{$i}}.inList" />
        </x-tall-crud-table-column>
        @endif
        @if ($this->addFeature)
        <x-tall-crud-table-column>
            <x-tall-crud-checkbox wire:model="fields.{{$i}}.inAdd" />
        </x-tall-crud-table-column>
        @endif
        @if ($this->editFeature)
        <x-tall-crud-table-column>
            <x-tall-crud-checkbox wire:model="fields.{{$i}}.inEdit" />
        </x-tall-crud-table-column>
        @endif
        <x-tall-crud-table-column>
            <x-tall-crud-checkbox wire:model="fields.{{$i}}.searchable" />
        </x-tall-crud-table-column>
        <x-tall-crud-table-column>
            <x-tall-crud-checkbox wire:model="fields.{{$i}}.sortable" />
        </x-tall-crud-table-column>
        <x-tall-crud-table-column>
            @if (!$this->addAndEditDisabled)
            <x-tall-crud-button wire:click.prevent="showFieldAttributes({{$i}})" mode="edit" class="mr-8 mt-4">
                Attributes
            </x-tall-crud-button>
            @endif
            <x-tall-crud-button wire:click.prevent="deleteField({{$i}})" mode="delete" class="mr-8 mt-4">
                Delete
            </x-tall-crud-button>
        </x-tall-crud-table-column>
    </tr>
    @endforeach
</x-tall-crud-table>

<div class="mt-4">
    <x-tall-crud-button mode="add" wire:click.prevent="addField">
        Add New Field
    </x-tall-crud-button>
    @error('fields') <x-tall-crud-error-message>{{$message}}</x-tall-crud-error-message> @enderror
</div>

@if (!$this->addAndEditDisabled)
<x-tall-crud-dialog-modal wire:model.live="confirmingFieldAttributes">
    <x-slot name="title">
        Attributes
    </x-slot>

    <x-slot name="content">
        <div class="mt-4">
            <div>
                <x-tall-crud-label>Enter Validations (Comma separated)
                    <x-tall-crud-tag wire:click="clearRules()">Clear Options</x-tall-crud-tag>
                </x-tall-crud-label>
                <x-tall-crud-input wire:model="fieldAttributes.rules" class="block mt-1 w-full"
                    type="text" />

                Popular Validations:
                <x-tall-crud-tag wire:click="addRule('required')">Required</x-tall-crud-tag>
                <x-tall-crud-tag wire:click="addRule('min:3')">Min</x-tall-crud-tag>
                <x-tall-crud-tag wire:click="addRule('max:50')">Max</x-tall-crud-tag>
                <x-tall-crud-tag wire:click="addRule('numeric')">Numeric</x-tall-crud-tag>
            </div>

            <div class="mt-4">
                <x-tall-crud-label>Field Type</x-tall-crud-label>
                <x-tall-crud-select class="block mt-1 w-1/4" wire:model.live="fieldAttributes.type">
                    <option value="input">Input</option>
                    <option value="select">Select</option>
                    <option value="checkbox">Checkbox</option>
                </x-tall-crud-select>
            </div>

            @if ($fieldAttributes['type'] == 'select')
            <x-tall-crud-label>Select Options (add as JSON)</x-tall-crud-label>
            <x-tall-crud-input class="block mt-1 w-full" type="text" wire:model="fieldAttributes.options" />
            @endif
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-tall-crud-button wire:click="$set('confirmingFieldAttributes', false)">Cancel
        </x-tall-crud-button>
        <x-tall-crud-button mode="add" wire:click="setFieldAttributes()">Save</x-tall-crud-button>
    </x-slot>
</x-tall-crud-dialog-modal>
@endif