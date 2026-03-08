<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_todo_crud_success_flow(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $createResponse = $this->postJson('/api/v1/todos', [
            'title' => 'Prepare sprint meeting',
        ]);

        $createResponse
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Prepare sprint meeting');

        $todoId = $createResponse->json('data.id');

        $showResponse = $this->getJson('/api/v1/todos/' . $todoId);

        $showResponse
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $todoId);

        $updateResponse = $this->putJson('/api/v1/todos/' . $todoId, [
            'title' => 'Prepare sprint retrospective',
            'completed' => true,
        ]);

        $updateResponse
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.completed', true)
            ->assertJsonPath('data.title', 'Prepare sprint retrospective');

        $deleteResponse = $this->deleteJson('/api/v1/todos/' . $todoId);

        $deleteResponse->assertStatus(204);

        $this->assertDatabaseMissing('todos', ['id' => $todoId]);
    }

    public function test_validation_returns_422_with_expected_structure(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/todos', [
            'title' => '',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Validation error')
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => ['title'],
            ]);
    }

    public function test_protected_todo_routes_require_authentication(): void
    {
        $todo = Todo::create([
            'title' => 'Unauthorized access test',
            'completed' => false,
        ]);

        $this->getJson('/api/v1/todos')
            ->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Unauthenticated');

        $this->postJson('/api/v1/todos', [
            'title' => 'Cannot create without auth',
        ])->assertStatus(401);

        $this->putJson('/api/v1/todos/' . $todo->id, [
            'title' => 'Cannot update without auth',
            'completed' => true,
        ])->assertStatus(401);

        $this->deleteJson('/api/v1/todos/' . $todo->id)
            ->assertStatus(401);
    }

    public function test_todos_endpoint_supports_pagination(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        for ($i = 1; $i <= 12; $i++) {
            Todo::create([
                'title' => 'Task ' . $i,
                'completed' => $i % 2 === 0,
            ]);
        }

        $response = $this->getJson('/api/v1/todos?page=1');

        $response
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.pagination.current_page', 1)
            ->assertJsonPath('data.pagination.per_page', 10)
            ->assertJsonPath('data.pagination.total', 12);

        $this->assertCount(10, $response->json('data.items'));
    }

    public function test_todos_endpoint_supports_completed_filter(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Todo::create(['title' => 'Done item 1', 'completed' => true]);
        Todo::create(['title' => 'Done item 2', 'completed' => true]);
        Todo::create(['title' => 'Open item 1', 'completed' => false]);

        $response = $this->getJson('/api/v1/todos?completed=true');

        $response->assertStatus(200)->assertJsonPath('success', true);

        foreach ($response->json('data.items') as $item) {
            $this->assertTrue((bool) $item['completed']);
        }
    }

    public function test_todos_endpoint_supports_search_filter(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Todo::create(['title' => 'meeting with product team', 'completed' => false]);
        Todo::create(['title' => 'prepare meeting notes', 'completed' => true]);
        Todo::create(['title' => 'buy groceries', 'completed' => false]);

        $response = $this->getJson('/api/v1/todos?search=meeting');

        $response->assertStatus(200)->assertJsonPath('success', true);

        foreach ($response->json('data.items') as $item) {
            $this->assertStringContainsString('meeting', strtolower($item['title']));
        }
    }
}
