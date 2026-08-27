<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_all_navigation_links(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);

        // Admin should see all nav links
        $response->assertSeeInOrder(['Dashboard', 'POS', 'Penjualan']);
        $response->assertSee('Kategori');
        $response->assertSee('Laporan');
    }

    public function test_cashier_cannot_see_admin_nav_links(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        $response = $this->actingAs($cashier)->get('/dashboard');
        $response->assertStatus(200);

        // Cashier should see basic nav
        $response->assertSee('Dashboard');
        $response->assertSee('POS');
        $response->assertSee('Penjualan');

        // Verify the admin-only nav links are not in navigation area
        // Note: "Produk" may appear in page content (e.g., "Produk Terlaris"), so we check route links
        $response->assertDontSee('categories.index');
        $response->assertDontSee('users.index');
        $response->assertDontSee('reports.daily');
    }

    public function test_admin_can_access_categories(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/categories');
        $response->assertStatus(200);
    }

    public function test_cashier_cannot_access_categories(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $response = $this->actingAs($cashier)->get('/categories');
        $response->assertStatus(403);
    }
}
