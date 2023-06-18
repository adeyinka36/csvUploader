<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\NameParser;

class NameParserTest extends TestCase
{
    use RefreshDatabase;

    private NameParser $parser;
    private string $testData = "homeowner,Mr John Smith,Mrs Jane Smith,Mr Tom Staff and Mr John Doe,Dr & Mrs Joe Bloggs,";

    protected function setUp(): void
    {
        $explodedData = explode(",", $this->testData);
        parent::setUp();
        $this->parser = new NameParser($explodedData);
    }

    public function testParseNames()
    {
        // Test case 1
        $this->parser->handle();

//        check if data is stored in persons table
        $this->assertDatabaseHas('persons', [
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Smith',
        ]);
        $this->assertDatabaseHas('persons', [
        'title' => 'Mrs',
        'first_name' => 'Jane',
        'initial' => null,
        'last_name' => 'Smith',
         ]);
        $this->assertDatabaseHas('persons', [
            'title' => 'Mr',
            'first_name' => 'Tom',
            'initial' => null,
            'last_name' => 'Staff',
        ]);
        $this->assertDatabaseHas('persons', [
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Doe',
        ]);
        $this->assertDatabaseHas('persons', [
            'title' => 'Mrs',
            'first_name' => 'Joe',
            'initial' => null,
            'last_name' => 'Bloggs',
        ]);
        $this->assertDatabaseHas('persons', [
            'title' => 'Dr',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Bloggs',
        ]);

    }
}
