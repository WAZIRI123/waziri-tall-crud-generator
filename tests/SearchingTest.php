<?php

namespace WAZIRITALLCRUDGENERATOR\Tests;

use WAZIRITALLCRUDGENERATOR\Http\GenerateCode\ComponentCode;
use WAZIRITALLCRUDGENERATOR\Http\GenerateCode\TallProperties;
use WAZIRITALLCRUDGENERATOR\Http\GenerateCode\Template;
use WAZIRITALLCRUDGENERATOR\Http\Livewire\TallCrudGenerator;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;

class SearchingTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    public function test_component_is_generated()
    {
        $this->component = Livewire::test(TallCrudGenerator::class)
            ->finishStep1()
            ->disableModals()
            ->pressNext()
            ->call('addField')
            ->set('fields.0.column', 'name')
            ->set('fields.0.searchable', true)
            ->pressNext(3)
            ->disableFlashMessage()
            ->disablePaginationDropdown()
            ->pressNext()
            ->generateFiles()
            ->assertSet('exitCode', 0)
            ->assertSet('isComplete', true);
    }

    public function test_search_is_setup()
    {
        $this->component = Livewire::test(TallCrudGenerator::class)
            ->finishStep1()
            ->disableModals()
            ->pressNext()
            ->call('addField')
            ->set('fields.0.column', 'name')
            ->set('fields.0.searchable', true)
            ->pressNext(3)
            ->disableFlashMessage()
            ->disablePaginationDropdown()
            ->pressNext()
            ->generateFiles()
            ->assertSet('exitCode', 0)
            ->assertSet('isComplete', true);

        $tallProperties = App::make(TallProperties::class);
        $componentCode = App::make(ComponentCode::class);
        $searchCode = $componentCode->getSearchCode();

        $this->assertTrue($tallProperties->isSearchingEnabled());
        $this->assertEquals(['name'], $tallProperties->getSearchableColumns()->toArray());
        $this->assertEquals(Template::getSearchVariables(), $componentCode->getSearchVars());
        $this->assertEquals(Template::getSearchMethod(), $componentCode->getSearchMethod());
        $whereClauseStr = <<<'EOT'
$query->where('name', 'like', '%' . $this->q . '%')
EOT;
        $this->assertEquals($whereClauseStr, $componentCode->getSearchWhereClause()->toArray()[0]);
        $this->assertStringContainsString($whereClauseStr, $searchCode['query']);
        $this->assertNotEmpty($searchCode['query']);
    }
}
