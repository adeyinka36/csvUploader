<?php

namespace Tests\Feature;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonCrudTest extends TestCase
{
    use RefreshDatabase;

    private array $formData = [
        'title' => 'Mr',
        'first_name' => 'John',
        'initial' => 'K',
        'last_name' => 'Doe',
    ];

    public function test_can_create_user()
    {


        $this->post(route('persons.store'), $this->formData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id', 'title', 'first_name', 'initial', 'last_name'
            ]);
    }

    public function test_can_update_user()
    {
        $person = Person::create($this->formData);

        $this->put(route('persons.update', $person->id), $this->formData)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'title', 'first_name', 'initial', 'last_name'
            ]);
    }

    public function test_can_show_user()
    {
        $person = Person::create($this->formData);
        $this->get(route('persons.show', $person->id))
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'title', 'first_name', 'initial', 'last_name'
            ]);
    }

    public function test_can_delete_user()
    {
        $person = Person::create($this->formData);

        $this->delete(route('persons.destroy', $person->id))
            ->assertStatus(204);
    }

    public function test_can_list_users()
    {
        $this->get(route('persons.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                "_links" => [
                    "_self" => [
                        "href"
                    ],
                    "next" => [
                        "href"
                    ],
                    "previous" => [
                        "href"
                    ]
                ],
                "count",
                "total",
                "data" => [
                    '*' => [
                        "id",
                        "title",
                        "first_name",
                        "initial",
                        "last_name"
                    ]
                ]
            ]);
    }
}
