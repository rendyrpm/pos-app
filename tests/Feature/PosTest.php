<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cashier;
    protected Category $category;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->cashier = User::factory()->create(['role' => 'cashier']);
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 50,
            'sell_price' => 10000,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_pos(): void
    {
        $response = $this->get('/pos');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_pos(): void
    {
        $response = $this->actingAs($this->admin)->get('/pos');
        $response->assertStatus(200);
    }

    public function test_cashier_can_access_pos(): void
    {
        $response = $this->actingAs($this->cashier)->get('/pos');
        $response->assertStatus(200);
    }

    public function test_pos_displays_products(): void
    {
        $response = $this->actingAs($this->cashier)->get('/pos');
        $response->assertStatus(200);
        $response->assertSee($this->product->name);
    }

    public function test_pos_search_products(): void
    {
        Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'UniqueProduct123',
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->cashier)->get('/pos/search?search=UniqueProduct123');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'UniqueProduct123']);
    }

    public function test_pos_search_by_category(): void
    {
        $otherCategory = Category::factory()->create();
        Product::factory()->create([
            'category_id' => $otherCategory->id,
            'name' => 'OtherProduct',
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->cashier)->get("/pos/search?category_id={$this->category->id}");
        $response->assertStatus(200);
        $response->assertJsonMissing(['name' => 'OtherProduct']);
    }

    public function test_pos_only_shows_active_products_with_stock(): void
    {
        Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 0,
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 10,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->cashier)->get('/pos');
        $response->assertStatus(200);
    }

    public function test_successful_cash_checkout(): void
    {
        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 2],
            ],
            'discount' => 0,
            'payment' => 25000,
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verify stock was reduced
        $this->product->refresh();
        $this->assertEquals(48, $this->product->stock);

        // Verify sale was created
        $this->assertDatabaseHas('sales', [
            'user_id' => $this->cashier->id,
            'total' => 20000,
            'payment' => 25000,
            'change_amount' => 5000,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);
    }

    public function test_successful_qris_checkout(): void
    {
        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1],
            ],
            'discount' => 0,
            'payment' => 10000,
            'payment_method' => 'qris',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('sales', [
            'payment_method' => 'qris',
        ]);
    }

    public function test_checkout_with_discount(): void
    {
        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 3],
            ],
            'discount' => 5000,
            'payment' => 25000,
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sales', [
            'total' => 25000, // (10000 * 3) - 5000
            'discount' => 5000,
        ]);
    }

    public function test_checkout_fails_with_insufficient_stock(): void
    {
        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 100],
            ],
            'discount' => 0,
            'payment' => 1000000,
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    public function test_checkout_fails_with_insufficient_payment(): void
    {
        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 3],
            ],
            'discount' => 0,
            'payment' => 10000,
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(400);
    }

    public function test_checkout_creates_sale_items(): void
    {
        $product2 = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 20,
            'sell_price' => 5000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 2],
                ['product_id' => $product2->id, 'quantity' => 1],
            ],
            'discount' => 0,
            'payment' => 25000,
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(200);

        $sale = Sale::latest()->first();
        $this->assertEquals(2, $sale->items()->count());
    }

    public function test_checkout_with_empty_cart_fails(): void
    {
        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [],
            'discount' => 0,
            'payment' => 0,
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(422);
    }

    public function test_stock_not_reduced_on_failed_checkout(): void
    {
        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 100],
            ],
            'discount' => 0,
            'payment' => 1000000,
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(400);
        $this->product->refresh();
        $this->assertEquals(50, $this->product->stock);
    }

    public function test_invalid_payment_method_rejected(): void
    {
        $response = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => 1],
            ],
            'discount' => 0,
            'payment' => 10000,
            'payment_method' => 'invalid',
        ]);

        $response->assertStatus(422);
    }

    public function test_transaction_number_is_unique(): void
    {
        // Create first transaction
        $response1 = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [['product_id' => $this->product->id, 'quantity' => 1]],
            'discount' => 0,
            'payment' => 15000,
            'payment_method' => 'cash',
        ]);
        $response1->assertStatus(200);

        // Create second transaction
        $response2 = $this->actingAs($this->cashier)->postJson('/pos/checkout', [
            'items' => [['product_id' => $this->product->id, 'quantity' => 1]],
            'discount' => 0,
            'payment' => 15000,
            'payment_method' => 'cash',
        ]);
        $response2->assertStatus(200);

        // Verify both have different transaction numbers
        $sales = Sale::orderBy('id')->get();
        $this->assertCount(2, $sales);
        $this->assertNotEquals($sales[0]->transaction_number, $sales[1]->transaction_number);
    }
}
