<div>


    <div wire:ignore.self x-data="{ selected : @entangle('selected').live,
        
        filters: @entangle('filters').live,
        
        isvalidFilter: @entangle('filter.isValid').live,
       
            filterColumns: @entangle('filter.columns').live,
            
            modelProps: @entangle('modelProps.columns').live,

            confirmingFilter: @entangle('confirmingFilter').live,

            advancedSettingsTableSettingsBulkActions: @entangle('advancedSettings.table_settings.bulkActions').live,
            
            filterColumn: @entangle('filter.column').live,

            filterType: @entangle('filter.type').live,

            allRelations: @entangle('allRelations').live,


            filterRelation: @entangle('filter.relation').live
        
        }">

        <x-tall-crud-dialog-modal wire:model.live="confirmingFilter">
    <x-slot name="title">
        Add a New Filter 
    </x-slot>

    <x-slot name="content">
        <div class="mt-4">
            <div>
                <x-tall-crud-label>Select Type</x-tall-crud-label>
                <x-tall-crud-select class="block mt-1 w-1/2" wire:model.lazy="filter.type">
                    <option value="">-Please Select-</option>
                    <option value="None">None</option>
                    <option value="BelongsTo">Belongs To</option>
                    <option value="BelongsToMany">Belongs To Many</option>
                    <option value="Date">Date Filter</option>
                </x-tall-crud-select>
                <x-tall-crud-error-message  x-text="filterType == '' ? ' filter Type required '  : ''">
                </x-tall-crud-error-message> 
               
            </div>

            <div class="mt-4 p-4 rounded border border-gray-300" x-show="isvalidFilter">
                @if ( $filter['type'] == 'None' || $filter['type'] == 'Date')
                <div class="mt-4">
                    <x-tall-crud-label>
                        Column
                    </x-tall-crud-label>
                    <x-tall-crud-select class="block mt-1 w-1/2"
                        wire:model.lazy="filter.column">
                        <option value="">-Please Select-</option>
                        <template x-if="filterColumns.contains(filterColumn)" x-for="column in filterColumns">
                    <option x-bind:value="column" x-text="column"></option>
             </template>
                    </x-tall-crud-select>
                    
                    <x-tall-crud-error-message x-text="filterColumn == '' ? 'Please select a Filter' : filterColumns.find(r => r.column == filterColumn) ? 'Filter Already Defined.' : ''">
                </x-tall-crud-error-message> 
                </div>
                @endif

               
                <div class="mt-4" x-show="filterType == 'Date'">
                    <x-tall-crud-label>Label</x-tall-crud-label>
                    <x-tall-crud-input class="block mt-1 w-1/2" type="text" wire:model="filter.label" />
                </div>
                <div class="mt-4">
                    <x-tall-crud-label>Operator</x-tall-crud-label>
                    <x-tall-crud-select class="block mt-1 w-1/6" wire:model.lazy="filter.operator">
                        <option value=">=">>=</option>
                        <option value=">">></option>
                        <option value="<"><</option>
                        <option value="<="><=</option>
                    </x-tall-crud-select>
                </div>
         

                <div class="mt-4" x-show="filterType == 'None'">
                    <x-tall-crud-label>Select Options (add as JSON)</x-tall-crud-label>
                    <x-tall-crud-input class="block mt-1 w-full" type="text" wire:model="filter.options" />
                </div>
                <!-- ||  -->

                <div class="mt-4" x-show="filterType == 'BelongsTo'">
                    <x-tall-crud-label>
                        Relationship
                    </x-tall-crud-label>
                    <x-tall-crud-select class="block mt-1 w-1/2"
                        wire:model.lazy="filter.relation">

                        <option value="">-Please Select-</option>

                        <template x-show="(allRelations.contains('belongsTo') && filterType == 'BelongsTo')" x-for="column in allRelations.belongsTo">
                    <option x-bind:value="column.name" x-text="column.name"></option>
             </template>

   
                    </x-tall-crud-select>
                    <x-tall-crud-error-message  x-text="filterRelation == '' ? ' filterRelation required '  : ''">
                </x-tall-crud-error-message> 
                </div>


                <div class="mt-4" x-show="filterType == 'BelongsToMany'">
                    <x-tall-crud-label>
                        Relationship
                    </x-tall-crud-label>
                    <x-tall-crud-select class="block mt-1 w-1/2"
                        wire:model.lazy="filter.relation">

                        <option value="">-Please Select-</option>

                        
                <template x-show="(allRelations.contains('belongsToMany') && filterType == 'BelongsToMany')" x-for="column in allRelations.belongsToMany">
                    <option x-bind:value="column.name" x-text="column.name"></option>
             </template>

   
                    </x-tall-crud-select>
                    <x-tall-crud-error-message  x-text="filterRelation == '' ? ' filterRelation required '  : ''">
                </x-tall-crud-error-message> 
                </div>
              


                <div class="mt-4" x-show="filterRelation !=''">
                    <x-tall-crud-label>
                        Column
                    </x-tall-crud-label>
                    <x-tall-crud-select class="block mt-1 w-1/2"
                        wire:model.lazy="filter.column">
                        <option value="">-Please Select-</option>
                        
                        <template x-if="filterColumns.contains('columns')" x-for="column in filterColumns">
                    <option x-bind:value="column" x-text="column"></option>
             </template>
                       
                    </x-tall-crud-select>
                    <x-tall-crud-error-message  x-text="filterColumn == '' ? ' displayColumn required '  : ''">
                </x-tall-crud-error-message> 
                </div>
                
                <x-tall-crud-label class="mt-4">
                    Filter Multiple Values
                    <x-tall-crud-checkbox class="ml-2" wire:model="filter.isMultiple" />
                </x-tall-crud-label>
             
            </div>
 
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-tall-crud-button wire:click="$set('confirmingFilter', false)">Cancel</x-tall-crud-button>
        <x-tall-crud-button mode="add" wire:click="addFilter()">Save</x-tall-crud-button>
    </x-slot>
</x-tall-crud-dialog-modal>





        <x-tall-crud-accordion-header tab="1">
            Customize Text
            <x-slot name="help">
                Customize the Text of Buttons, Links and Headings.
            </x-slot>
        </x-tall-crud-accordion-header>

        <x-tall-crud-accordion-wrapper ref="advancedTab1" tab="1">
            @foreach ($advancedSettings['text'] as $key => $text)
            <div class="mt-4">
                <x-tall-crud-label>
                    {{ $this->getAdvancedSettingLabel($key)}}
                </x-tall-crud-label>
                <x-tall-crud-input class="block mt-1 w-1/4" type="text"
                    wire:model="advancedSettings.text.{{$key}}" />
            </div>
            @endforeach
        </x-tall-crud-accordion-wrapper>

        <x-tall-crud-accordion-header tab="2">
            Flash Messages
            <x-slot name="help">
                Enable / Disable Flash Messages & Customize their Text.
            </x-slot>
        </x-tall-crud-accordion-header>

        <x-tall-crud-accordion-wrapper ref="advancedTab2" tab="2">
            <x-tall-crud-label class="mt-2">
                Enable Flash Messages:
                <x-tall-crud-checkbox class="ml-2" wire:model="flashMessages.enable" />
            </x-tall-crud-label>

            @foreach (['add', 'edit', 'delete'] as $key)
            <div class="mt-4">
                <x-tall-crud-label>{{ Str::title($key)}}:</x-tall-crud-label>
                <x-tall-crud-input type="text" class="mt-1 block w-1/2"
                    wire:model="flashMessages.text.{{$key}}" />
            </div>
            @endforeach
        </x-tall-crud-accordion-wrapper>

        <x-tall-crud-accordion-header tab="3">
            Table Settings
            <x-slot name="help">
                Customize the Properties of Table displaying the Listing
            </x-slot>
        </x-tall-crud-accordion-header>
        <x-tall-crud-accordion-wrapper ref="advancedTab3" tab="3">
            <x-tall-crud-label class="mt-2">
                Show Pagination Dropdown:
                <x-tall-crud-checkbox class="ml-2" wire:model="advancedSettings.table_settings.showPaginationDropdown" />
            </x-tall-crud-label>
            <x-tall-crud-checkbox-wrapper class="mt-4">
                <x-tall-crud-label>Records Per Page: </x-tall-crud-label>
                <x-tall-crud-select class="block mt-1 w-1/6 ml-2"
                    wire:model.live="advancedSettings.table_settings.recordsPerPage">
                    @foreach ([10, 15, 20, 50] as $p)
                    <option value="{{$p}}">{{$p}}</option>
                    @endforeach
                </x-tall-crud-select>
            </x-tall-crud-checkbox-wrapper>
            <x-tall-crud-label class="mt-4">
                Allow User to Hide Column in Listing <span class="italic">(only works with Alpine v3):</span>
                <x-tall-crud-checkbox class="ml-2" wire:model="advancedSettings.table_settings.showHideColumns" />
            </x-tall-crud-label>
            <x-tall-crud-label class="mt-4">
                Enable Bulk Actions
                <x-tall-crud-checkbox class="ml-2" wire:model="advancedSettings.table_settings.bulkActions" />
            </x-tall-crud-label>
           
            <x-tall-crud-checkbox-wrapper x-show="advancedSettingsTableSettingsBulkActions">
                <x-tall-crud-label>Column to Change on Bulk Action: </x-tall-crud-label>
                <x-tall-crud-select class="block mt-1 w-1/6 ml-2"
                    wire:model.live="advancedSettings.table_settings.bulkActionColumn">
                    <option value="">-Select Column-</option>

                    <template x-if="modelProps.contains('columns')" x-for="column in modelProps">
                    <option x-bind:value="column" x-text="column"></option>
             </template>

                </x-tall-crud-select>
            </x-tall-crud-checkbox-wrapper>
            
            <div class="mt-4">The Table uses Blue Theme. You can change the theme by changing <span class="font-bold text-blue-700">blue</span> classes to other class. Check <a href="https://v2.tailwindcss.com/docs/customizing-colors" target="_blank" class="text-blue-300 cursor-pointer">v2</a> or <a class="text-blue-300 cursor-pointer" target="_blank" href="https://tailwindcss.com/docs/customizing-colors">v3</a> for other classes.</div>
            <div class="mt-4">
                <x-tall-crud-label>Class on th:</x-tall-crud-label>
                <x-tall-crud-input type="text" class="mt-1 block w-1/4"
                    wire:model="advancedSettings.table_settings.classes.th" />
            </div>
            <div class="mt-4">
                <x-tall-crud-label>Hover Class on tr:</x-tall-crud-label>
                <x-tall-crud-input type="text" class="mt-1 block w-1/4"
                    wire:model="advancedSettings.table_settings.classes.trHover" />
            </div>
            <div class="mt-4">
                <x-tall-crud-label>Even Row Class:</x-tall-crud-label>
                <x-tall-crud-input type="text" class="mt-1 block w-1/4"
                    wire:model="advancedSettings.table_settings.classes.trEven" />
            </div>
            <div class="mt-4">
                <x-tall-crud-label>Table Row Divide Class:</x-tall-crud-label>
                <x-tall-crud-input type="text" class="mt-1 block w-1/4"
                    wire:model="advancedSettings.table_settings.classes.trBottomBorder" />
            </div>
            <div class="mt-4">
                <x-tall-crud-label>Class on td:</x-tall-crud-label>
                <x-tall-crud-input type="text" class="mt-1 block w-1/4"
                    wire:model="advancedSettings.table_settings.classes.td" />
            </div>
        </x-tall-crud-accordion-wrapper>
        <x-tall-crud-accordion-header tab="4">
            Filters <h1 x-text="confirmingFilter"></h1>
            <x-slot name="help">
                Define Filters so that Users can Search throuth the data from your Listing 
            </x-slot>
        </x-tall-crud-accordion-header>

        <x-tall-crud-accordion-wrapper ref="advancedTab4" tab="4">
            <x-tall-crud-button class="mt-4" wire:click="createNewFilter">Add
            </x-tall-crud-button>
            <x-tall-crud-table class="mt-4">
                <x-slot name="header">
                    <x-tall-crud-table-column>Type</x-tall-crud-table-column>
                    <x-tall-crud-table-column>Column</x-tall-crud-table-column>
                    <x-tall-crud-table-column>Actions</x-tall-crud-table-column>
                </x-slot>
           
                <template  x-for="(v,i) in filters" :key="v">
                <tr>
                    <x-tall-crud-table-column x-text="v.type"></x-tall-crud-table-column>
                    <x-tall-crud-table-column x-text="
  v.type === 'None' || v.type === 'Date' ? (
    v.type === 'Date' ? v.operator + ' ' : ''
  ) + v.column
  : v.type === 'BelongsTo' || v.type === 'BelongsToMany' ? v.relation + '.' + v.column
  : ''
" >
                        
                    </x-tall-crud-table-column>
                    <x-tall-crud-table-column>
                        <x-tall-crud-button wire:click.prevent="deleteFilter(i)" mode="delete">
                            Delete
                        </x-tall-crud-button>
                    </x-tall-crud-table-column>
                </tr>
                </template>
            </x-tall-crud-table>
        </x-tall-crud-accordion-wrapper>

    </div>
</div>