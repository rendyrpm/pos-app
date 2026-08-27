<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
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

    public function test_guest_cannot_access_sales(): void
    {
        $response = $this->get('/sales');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_sales_index(): void
    {
        Sale::factory()->count(3)->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get('/sales');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Penjualan');
    }

    public function test_cashier_can_view_sales_index(): void
    {
        Sale::factory()->count(2)->create(['user_id' => $this->cashier->id]);

        $response = $this->actingAs($this->cashier)->get('/sales');
        $response->assertStatus(200);
    }

    public function test_admin_can_view_sale_detail(): void
    {
        $sale = Sale::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get("/sales/{$sale->id}");
        $response->assertStatus(200);
        $response->assertSee($sale->transaction_number);
    }

    public function test_cashier_can_view_sale_detail(): void
    {
        $sale = Sale::factory()->create(['user_id' => $this->cashier->id]);

        $response = $this->actingAs($this->cashier)->get("/sales/{$sale->id}");
        $response->assertStatus(200);
    }

    public function test_admin_can_view_receipt(): void
    {
        $sale = Sale::factory()->create(['user_id' => $this->admin->id]);
        SaleItem::factory()->create(['sale_id' => $sale->id]);

        $response = $this->actingAs($this->admin)->get("/sales/{$sale->id}/receipt");
        $response->assertStatus(200);
        $response->assertSee($sale->transaction_number);
    }

    public function test_sales_search_by_transaction_number(): void
    {
        Sale::factory()->create([
            'user_id' => $this->admin->id,
            'transaction_number' => 'TRX-20260827-00001',
        ]);
        Sale::factory()->create([
            'user_id' => $this->admin->id,
            'transaction_number' => 'TRX-20260827-00002',
        ]);

        $response = $this->actingAs($this->admin)->get('/sales?search=00001');
        $response->assertStatus(200);
        $response->assertSee('TRX-20260827-00001');
    }

    public function test_sale_detail_shows_items(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $sale = Sale::factory()->create(['user_id' => $this->admin->id]);
        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'price' => $product->sell_price,
        ]);

        $response = $this->actingAs($this->admin)->get("/sales/{$sale->id}");
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_sale_shows_payment_method(): void
    {
        $sale = Sale::factory()->create([
            'user_id' => $this->admin->id,
            'payment_method' => 'qris',
        ]);

        $response = $this->actingAs($this->admin)->get("/sales/{$sale->id}");
        $response->assertStatus(200);
        $response->assertSee('QRIS');
    }
}
