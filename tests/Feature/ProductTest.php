<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cashier;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->cashier = User::factory()->create(['role' => 'cashier']);
        $this->category = Category::factory()->create();
    }

    public function test_cashier_cannot_access_products(): void
    {
        $response = $this->actingAs($this->cashier)->get('/products');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_products_index(): void
    {
        Product::factory()->count(3)->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->admin)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Produk');
    }

    public function test_admin_can_view_create_product_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/products/create');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_product(): void
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name' => 'Indomie Goreng',
            'sku' => 'IND-001',
            'barcode' => '8991234567890',
            'category_id' => $this->category->id,
            'buy_price' => 2500,
            'sell_price' => 3500,
            'stock' => 100,
            'unit' => 'pcs',
            'min_stock' => 10,
            'description' => 'Indomie goreng original',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'Indomie Goreng', 'sku' => 'IND-001']);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'name' => 'Updated Product',
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'category_id' => $this->category->id,
            'buy_price' => $product->buy_price,
            'sell_price' => $product->sell_price,
            'stock' => $product->stock,
            'unit' => $product->unit,
            'min_stock' => $product->min_stock,
            'description' => 'Updated description',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Product']);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->admin)->delete("/products/{$product->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_view_product_detail(): void
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $response = $this->actingAs($this->admin)->get("/products/{$product->id}");
        $response->assertStatus(200);
    }

    public function test_product_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name' => '',
            'sku' => '',
            'buy_price' => '',
            'sell_price' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'sku', 'buy_price', 'sell_price']);
    }

    public function test_admin_can_search_products(): void
    {
        Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Indomie Goreng',
        ]);
        Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Teh Botol',
        ]);

        $response = $this->actingAs($this->admin)->get('/products?search=Indomie');
        $response->assertStatus(200);
        $response->assertSee('Indomie Goreng');
    }
}
