<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cashier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->cashier = User::factory()->create(['role' => 'cashier']);
    }

    public function test_cashier_cannot_access_categories(): void
    {
        $response = $this->actingAs($this->cashier)->get('/categories');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_categories_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/categories');
        $response->assertStatus(200);
        $response->assertSee('Kategori');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/categories', [
            'name' => 'Makanan',
            'description' => 'Kategori makanan',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Makanan']);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin)->put("/categories/{$category->id}", [
            'name' => 'New Name',
            'description' => $category->description,
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);
    }

    public function test_admin_can_delete_category_without_products(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/categories/{$category->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_name_is_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/categories', [
            'name' => '',
            'description' => 'Test',
            'is_active' => '1',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_category_detail(): void
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->admin)->get("/categories/{$category->id}");
        $response->assertStatus(200);
    }

    public function test_admin_can_view_category_edit_form(): void
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->admin)->get("/categories/{$category->id}/edit");
        $response->assertStatus(200);
    }
}
