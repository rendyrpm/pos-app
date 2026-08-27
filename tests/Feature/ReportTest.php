<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
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

    public function test_cashier_cannot_access_reports(): void
    {
        $response = $this->actingAs($this->cashier)->get('/reports');
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_reports(): void
    {
        $response = $this->get('/reports');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_reports_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports');
        $response->assertRedirect(route('reports.daily'));
    }

    public function test_admin_can_access_daily_report(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports/daily');
        $response->assertStatus(200);
        $response->assertSee('Laporan Harian');
    }

    public function test_admin_can_access_monthly_report(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports/monthly');
        $response->assertStatus(200);
        $response->assertSee('Laporan Bulanan');
    }

    public function test_admin_can_access_products_report(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports/products');
        $response->assertStatus(200);
        $response->assertSee('Laporan Penjualan Produk');
    }

    public function test_daily_report_shows_sales_data(): void
    {
        $sale = Sale::factory()->create([
            'user_id' => $this->admin->id,
            'total' => 50000,
            'payment' => 50000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get('/reports/daily?date=' . now()->format('Y-m-d'));
        $response->assertStatus(200);
        $response->assertSee('Rp 50.000');
    }

    public function test_daily_report_filter_by_date(): void
    {
        Sale::factory()->create([
            'user_id' => $this->admin->id,
            'total' => 10000,
            'status' => 'completed',
            'created_at' => now()->subDays(5),
        ]);

        $response = $this->actingAs($this->admin)->get('/reports/daily?date=' . now()->format('Y-m-d'));
        $response->assertStatus(200);
    }

    public function test_monthly_report_shows_data(): void
    {
        Sale::factory()->create([
            'user_id' => $this->admin->id,
            'total' => 100000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get('/reports/monthly?month=' . now()->month . '&year=' . now()->year);
        $response->assertStatus(200);
        $response->assertSee('Rp 100.000');
    }

    public function test_products_report_shows_data(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Test Product',
        ]);
        $sale = Sale::factory()->create([
            'user_id' => $this->admin->id,
            'status' => 'completed',
        ]);
        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'product_name' => 'Test Product',
            'quantity' => 5,
            'subtotal' => 50000,
        ]);

        $response = $this->actingAs($this->admin)->get('/reports/products');
        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    public function test_products_report_filter_by_date(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports/products?date_from=' . now()->startOfMonth()->format('Y-m-d') . '&date_to=' . now()->format('Y-m-d'));
        $response->assertStatus(200);
    }
}
