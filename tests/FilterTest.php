<?php

namespace WAZIRITALLCRUDGENERATOR\Tests;

use WAZIRITALLCRUDGENERATOR\Http\GenerateCode\ComponentCode;
use WAZIRITALLCRUDGENERATOR\Http\GenerateCode\TallProperties;
use WAZIRITALLCRUDGENERATOR\Http\GenerateCode\Template;
use WAZIRITALLCRUDGENERATOR\Http\GenerateCode\ViewCode;
use WAZIRITALLCRUDGENERATOR\Http\Livewire\TallCrudGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Livewire\Livewire;

class FilterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
        $this->component = Livewire::test(TallCrudGenerator::class)
            ->finishStep1()
            ->pressNext()
            ->setStandardFields()
            ->pressNext(3);
    }

    public function test_type_dropdown_is_wired()
    {
        $this->component
            ->call('createNewFilter')
            ->assertPropertyWired('filter.type');
    }

    public function test_type_dropdown_is_required()
    {
        $this->component
            ->call('createNewFilter')
            ->call('addFilter')
            ->assertHasErrors('filter.type')
            ->assertSee('Please select a value.');
    }

    public function test_self_filter_shows_other_fields()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'None')
            ->assertPropertyWired('filter.column')
            ->assertPropertyWired('filter.options')
            ->assertCount('filter.columns', 7)
            ->assertSet('filter.options', '{"": "Any", "0" : "No", "1": "Yes"}');
    }

    public function test_column_is_required_for_self_filter()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'None')
            ->call('addFilter')
            ->assertHasErrors('filter.column')
            ->assertSee('Please select a value.');
    }

    public function test_self_filter_can_be_added()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'None')
            ->set('filter.column', 'name')
            ->call('addFilter')
            ->assertViewHas('filters')
            ->assertCount('filters', 1)
            ->assertMethodWired('deleteFilter')
            ->assertSeeInOrder(['None', 'name']);
    }

    public function test_duplicate_self_filter_can_not_be_added()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'None')
            ->set('filter.column', 'name')
            ->call('addFilter')
            ->call('createNewFilter')
            ->set('filter.type', 'None')
            ->set('filter.column', 'name')
            ->assertHasErrors('filter.column')
            ->assertSee('Filter Already Defined.')
            ->call('addFilter')
            ->assertViewHas('filters')
            ->assertCount('filters', 1);
    }

    public function test_invalid_self_filter_options()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'None')
            ->set('filter.column', 'status')
            ->set('filter.options', '{"": "Any", "0" : "No", "1": "Yes", 33}')
            ->call('addFilter')
            ->assertViewHas('filters')
            ->assertCount('filters', 1)
            ->pressNext()
            ->generateFiles();

        $tallProperties = App::make(TallProperties::class);
        $props = $this->component->get('props');

        $this->assertTrue($tallProperties->isFilterEnabled());
        $this->assertNotEmpty($props['code']['filter']['vars']);
        $this->assertNotEmpty($props['code']['filter']['query']);
        $this->assertNotEmpty($props['code']['filter']['method']);
    }

    public function test_belongs_to_filter_shows_other_fields()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsTo')
            ->assertPropertyWired('filter.relation')
            ->assertCount('allRelations.belongsTo', 1);
    }

    public function test_relation_is_required_for_belongs_to()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsTo')
            ->call('addFilter')
            ->assertHasErrors('filter.relation')
            ->assertSee('Please select a value.');
    }

    public function test_column_is_populated_and_required_for_belongs_to()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsTo')
            ->set('filter.relation', 'brand')
            ->assertPropertyWired('filter.column')
            ->assertCount('filter.columns', 4)
            ->call('addFilter')
            ->assertHasErrors('filter.column')
            ->assertSee('Please select a value.');
    }

    public function test_belongs_to_filter_can_be_added()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsTo')
            ->set('filter.relation', 'brand')
            ->set('filter.column', 'name')
            ->call('addFilter')
            ->assertMethodWired('deleteFilter')
            ->assertSeeInOrder(['BelongsTo', 'brand.name']);
    }

    public function test_duplicate_belongs_to_filter_can_not_be_added()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsTo')
            ->set('filter.relation', 'brand')
            ->set('filter.column', 'name')
            ->call('addFilter')
            ->set('filter.type', 'BelongsTo')
            ->set('filter.relation', 'brand')
            ->assertHasErrors('filter.relation')
            ->assertSee('Filter Already Defined.')
            ->call('addFilter')
            ->assertViewHas('filters')
            ->assertCount('filters', 1);
    }

    public function test_belongs_to_many_filter_shows_other_fields()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsToMany')
            ->assertPropertyWired('filter.relation')
            ->assertCount('allRelations.belongsToMany', 3);
    }

    public function test_relation_is_required_for_belongs_to_many()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsToMany')
            ->call('addFilter')
            ->assertHasErrors('filter.relation')
            ->assertSee('Please select a value.');
    }

    public function test_column_is_populated_and_required_for_belongs_to_many()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsToMany')
            ->set('filter.relation', 'categories')
            ->assertPropertyWired('filter.column')
            ->assertCount('filter.columns', 4)
            ->call('addFilter')
            ->assertHasErrors('filter.column')
            ->assertSee('Please select a value.');
    }

    public function test_belongs_to_many_filter_can_be_added()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsToMany')
            ->set('filter.relation', 'categories')
            ->set('filter.column', 'name')
            ->call('addFilter')
            ->assertMethodWired('deleteFilter')
            ->assertSeeInOrder(['BelongsToMany', 'categories.name']);
    }

    public function test_duplicate_belongs_to_many_filter_can_not_be_added()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsToMany')
            ->set('filter.relation', 'categories')
            ->set('filter.column', 'name')
            ->call('addFilter')
            ->set('filter.type', 'BelongsToMany')
            ->set('filter.relation', 'categories')
            ->assertHasErrors('filter.relation')
            ->assertSee('Filter Already Defined.')
            ->call('addFilter')
            ->assertViewHas('filters')
            ->assertCount('filters', 1);
    }

    public function test_filter_can_be_deleted()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'BelongsToMany')
            ->set('filter.relation', 'categories')
            ->set('filter.column', 'name')
            ->call('addFilter')
            ->assertMethodWired('deleteFilter')
            ->call('deleteFilter', 0)
            ->assertCount('filters', 0);
    }

    public function test_multiple_filter_can_be_added()
    {
        $this->component
            ->setStandardFilters()

            ->assertViewHas('filters')
            ->assertCount('filters', 3)
            ->assertSeeInOrder(['None', 'name', 'BelongsTo', 'brand.name', 'BelongsToMany', 'categories.name']);
    }

    public function test_other_models_code_is_added()
    {
        $this->component
            ->setStandardFilters()
            ->pressNext()
            ->generateFiles();
        
        $tallProperties = App::make(TallProperties::class);
        $componentCode = App::make(ComponentCode::class);
        $otherModels = $tallProperties->getOtherModels()->toArray();
        $this->assertCount(2, $otherModels);
        $this->assertEquals(
            [
                'WAZIRITALLCRUDGENERATOR\Tests\Models\Brand',
                'WAZIRITALLCRUDGENERATOR\Tests\Models\Category',
            ],
            $otherModels
        );

        // $this->assertEquals("\nuse App\Model\Brand;", $componentCode->getUseModelCode('App\Model\Brand'));
    }

    public function test_filter_dropdown_is_added_to_view()
    {
        $this->component
            ->setStandardFilters()
            ->pressNext()
            ->generateFiles();
        
        $viewCode = App::make(ViewCode::class);
        $this->assertEquals(Template::getFilterDropdownTemplate(), $viewCode->getFilterDropdown());
    }

    public function test_filter_code_is_added_to_component()
    {
        $this->component
            ->setStandardFilters()
            ->pressNext()
            ->generateFiles();
        
        $tallProperties = App::make(TallProperties::class);
        $componentCode = App::make(ComponentCode::class);
        $props = $this->component->get('props');

        $this->assertTrue($tallProperties->isFilterEnabled());
        
        $this->assertNotEmpty($props['code']['filter']['vars']);
        $this->assertStringContainsString('public $filters = [];', $props['code']['filter']['vars']);
        $this->assertStringContainsString('public $selectedFilters = [];', $props['code']['filter']['vars']);

        $this->assertNotEmpty($props['code']['filter']['init']);
        $this->assertCount(1, $tallProperties->selfFilters);
        $firstFilter = $tallProperties->selfFilters->first();
        $this->assertEquals('name', $componentCode->getFilterColumnName($firstFilter));
        $this->assertEquals('Name', $componentCode->getFilterLabelName($firstFilter));

        $selfFilterOptions = [
            '0' => "['key' => '', 'label' => 'Any'],",
            '1' => "['key' => '0', 'label' => 'No'],",
            '2' => "['key' => '1', 'label' => 'Yes'],",
        ];
        $this->assertEquals(
            $selfFilterOptions,
            $componentCode->generateFilterOptionsFromJson($firstFilter)->toArray()
        );

        $btmFilter = $tallProperties->btmFilters->first();
        $this->assertEquals('categories', Str::plural($btmFilter['relation']));
        $this->assertEquals('Category', $tallProperties->getModelName($btmFilter['modelPath']));
        $this->assertEquals('name', $btmFilter['column']);
        $this->assertEquals('id', $componentCode->getFilterOwnerKey($btmFilter));
        $this->assertEquals('categories_id', $componentCode->getFilterForeignKey($btmFilter));
        $this->assertEquals('Categories', $componentCode->getFilterLabelName($btmFilter));

        $belongsToFilter = $tallProperties->belongsToFilters->first();
        $this->assertEquals('brands', Str::plural($belongsToFilter['relation']));
        $this->assertEquals('Brand', $tallProperties->getModelName($belongsToFilter['modelPath']));
        $this->assertEquals('name', $belongsToFilter['column']);
        $this->assertEquals('id', $componentCode->getFilterOwnerKey($belongsToFilter));
        $this->assertEquals('brand_id', $componentCode->getFilterForeignKey($belongsToFilter));
        $this->assertEquals('Brand', $componentCode->getFilterLabelName($belongsToFilter));

        $this->assertNotEmpty($props['code']['filter']['query']);
        $selfFilterQuery = <<<'EOT'
            ->when($this->isFilterSet('name'), function($query) {
                return $query->where('name', $this->selectedFilters['name']);
            })
EOT;
        $belongsToFilterQuery = <<<'EOT'
            ->when($this->isFilterSet('brand_id'), function($query) {
                return $query->where('brand_id', $this->selectedFilters['brand_id']);
            })
EOT;
        $btmFilterQuery = <<<'EOT'
            ->when($this->isFilterSet('categories_id'), function($query) {
                return $query->whereHas('categories', function($query) {
                    return $query->where('categories.id', $this->selectedFilters['categories_id']);
                });
            })
EOT;
        $this->assertStringContainsString($selfFilterQuery, $props['code']['filter']['query']);
        $this->assertStringContainsString($belongsToFilterQuery, $props['code']['filter']['query']);
        $this->assertStringContainsString($btmFilterQuery, $props['code']['filter']['query']);

        $this->assertNotEmpty($props['code']['filter']['method']);
    }

    public function test_multi_filters()
    {
        $this->component
            ->setStandardFilters(true)
            ->pressNext()
            ->generateFiles();
        
        $props = $this->component->get('props');

        $selfFilterQuery = <<<'EOT'
            ->when($this->isFilterSet('name'), function($query) {
                return $query->where('name', $this->selectedFilters['name']);
            })
EOT;
        $belongsToFilterQuery = <<<'EOT'
            ->when($this->isFilterSet('brand_id'), function($query) {
                return $query->whereIn('brand_id', $this->selectedFilters['brand_id']);
            })
EOT;
        $btmFilterQuery = <<<'EOT'
            ->when($this->isFilterSet('categories_id'), function($query) {
                return $query->whereHas('categories', function($query) {
                    return $query->whereIn('categories.id', $this->selectedFilters['categories_id']);
                });
            })
EOT;
        $this->assertStringContainsString($selfFilterQuery, $props['code']['filter']['query']);
        $this->assertStringContainsString($belongsToFilterQuery, $props['code']['filter']['query']);
        $this->assertStringContainsString($btmFilterQuery, $props['code']['filter']['query']);
    }

    public function test_date_filter_shows_other_fields()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'Date')
            ->assertPropertyWired('filter.column')
            ->assertPropertyWired('filter.label')
            ->assertPropertyWired('filter.operator')
            ->assertCount('filter.columns', 7)
            ->assertSet('filter.operator', '>=');
    }

    public function test_column_is_required_for_filter()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'Date')
            ->call('addFilter')
            ->assertHasErrors('filter.column')
            ->assertSee('Please select a value.');
    }

    public function test_date_filter_can_be_added()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'Date')
            ->set('filter.column', 'created_at')
            ->set('filter.label', 'Created From')
            ->call('addFilter')
            ->assertMethodWired('deleteFilter')
            ->assertSeeInOrder(['Date', 'created_at']);
    }

    public function test_duplicate_date_filter_can_be_added()
    {
        $this->component
            ->call('createNewFilter')
            ->set('filter.type', 'Date')
            ->set('filter.column', 'created_at')
            ->set('filter.label', 'Created From')
            ->call('addFilter')

            ->call('createNewFilter')
            ->set('filter.type', 'Date')
            ->set('filter.column', 'created_at')
            ->set('filter.label', 'Created Till')
            ->set('filter.operator', '<=')
            ->call('addFilter')

            ->assertViewHas('filters')
            ->assertCount('filters', 2);
    }

    public function test_date_filter_code_is_added_to_component()
    {
        $this->component
            ->setStandardDateFilters()
            ->pressNext()
            ->generateFiles();
        
        $tallProperties = App::make(TallProperties::class);
        $componentCode = App::make(ComponentCode::class);
        $props = $this->component->get('props');

        $this->assertTrue($tallProperties->isFilterEnabled());
        
        $this->assertNotEmpty($props['code']['filter']['vars']);
        $this->assertStringContainsString('public $filters = [];', $props['code']['filter']['vars']);
        $this->assertStringContainsString('public $selectedFilters = [];', $props['code']['filter']['vars']);

        $this->assertNotEmpty($props['code']['filter']['init']);
        $initCode1 = <<<'EOT'
$this->filters['created_at_0']['label'] = 'Created From';
EOT;
        $initCode2 = <<<'EOT'
$this->filters['created_at_0']['type'] = 'date';
EOT;
        $initCode3 = <<<'EOT'
$this->filters['created_at_1']['label'] = 'Created Till';
EOT;
        $initCode4 = <<<'EOT'
$this->filters['created_at_1']['type'] = 'date';
EOT;

        $this->assertStringContainsString($initCode1, $props['code']['filter']['init']);
        $this->assertStringContainsString($initCode2, $props['code']['filter']['init']);
        $this->assertStringContainsString($initCode3, $props['code']['filter']['init']);
        $this->assertStringContainsString($initCode4, $props['code']['filter']['init']);

        $this->assertCount(2, $tallProperties->dateFilters);

        $this->assertNotEmpty($props['code']['filter']['query']);
        $dateFilterQuery1 = <<<'EOT'
            ->when($this->isFilterSet('created_at_0'), function($query) {
                return $query->where('created_at', '>=', $this->selectedFilters['created_at_0']);
            })
EOT;

        $dateFilterQuery2 = <<<'EOT'
            ->when($this->isFilterSet('created_at_1'), function($query) {
                return $query->where('created_at', '<=', $this->selectedFilters['created_at_1']);
            })
EOT;
        $this->assertStringContainsString($dateFilterQuery1, $props['code']['filter']['query']);
        $this->assertStringContainsString($dateFilterQuery2, $props['code']['filter']['query']);

        $this->assertNotEmpty($props['code']['filter']['method']);
    }
}
