<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Total penjualan hari ini
        $todaySales = Sale::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        // Jumlah transaksi hari ini
        $todayTransactions = Sale::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        // Jumlah produk
        $totalProducts = Product::where('is_active', true)->count();

        // Produk dengan stok menipis
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('is_active', true)
            ->count();

        // Omzet bulan ini
        $monthSales = Sale::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Rata-rata transaksi hari ini
        $avgTransaction = $todayTransactions > 0 ? $todaySales / $todayTransactions : 0;

        // Produk terlaris hari ini (5 teratas)
        $topProductsToday = SaleItem::whereHas('sale', function ($q) use ($today) {
                $q->whereDate('created_at', $today)->where('status', 'completed');
            })
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Transaksi terbaru
        $recentSales = Sale::with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        // Data untuk grafik penjualan 7 hari terakhir
        $chartData = Sale::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing days for chart (last 7 days)
        $chartFull = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $found = $chartData->firstWhere('date', $date);
            $chartFull->push([
                'date' => $date,
                'total' => $found ? $found->total : 0,
                'count' => $found ? $found->count : 0,
            ]);
        }

        // Produk dengan stok menipis (list)
        $lowStockList = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'todayTransactions',
            'totalProducts',
            'lowStockProducts',
            'monthSales',
            'avgTransaction',
            'topProductsToday',
            'recentSales',
            'chartFull',
            'lowStockList'
        ));
    }
}
