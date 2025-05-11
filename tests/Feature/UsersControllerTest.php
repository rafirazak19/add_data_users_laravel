<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        // Prepare test data
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'age' => 25,
            'password' => 'secret123' // Optional if defaulted
        ];

        // Send POST request to the API
        $response = $this->postJson('/api/users', $data);

        // Assert response
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'data' => [
                         'id', 'name', 'email', 'age', 'created_at', 'updated_at'
                     ]
                 ]);

        // Assert user saved in database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'age' => 25
        ]);
    }
}
