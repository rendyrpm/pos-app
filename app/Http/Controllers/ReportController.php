<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Redirect to daily report by default
        return redirect()->route('reports.daily');
    }

    public function daily(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();

        // Summary stats for the selected date
        $salesData = Sale::whereDate('created_at', $date)
            ->selectRaw('
                COUNT(*) as total_transactions,
                COALESCE(SUM(subtotal), 0) as total_subtotal,
                COALESCE(SUM(discount), 0) as total_discount,
                COALESCE(SUM(total), 0) as total_revenue,
                COALESCE(SUM(payment), 0) as total_payment,
                COALESCE(SUM(change_amount), 0) as total_change
            ')
            ->first();

        // Payment method breakdown
        $paymentMethods = Sale::whereDate('created_at', $date)
            ->selectRaw('payment_method, COUNT(*) as count, COALESCE(SUM(total), 0) as total')
            ->groupBy('payment_method')
            ->get();

        // Hourly sales data for chart
        $hourlySales = Sale::whereDate('created_at', $date)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count, COALESCE(SUM(total), 0) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Recent transactions for the day
        $recentSales = Sale::whereDate('created_at', $date)
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Top 5 products sold today
        $topProducts = SaleItem::whereHas('sale', function ($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('reports.daily', compact(
            'date', 'salesData', 'paymentMethods', 'hourlySales', 'recentSales', 'topProducts'
        ));
    }

    public function monthly(Request $request)
    {
        $year = $request->filled('year') ? (int) $request->year : Carbon::now()->year;
        $month = $request->filled('month') ? (int) $request->month : Carbon::now()->month;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Monthly summary
        $salesData = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_transactions,
                COALESCE(SUM(subtotal), 0) as total_subtotal,
                COALESCE(SUM(discount), 0) as total_discount,
                COALESCE(SUM(total), 0) as total_revenue,
                COALESCE(SUM(payment), 0) as total_payment,
                COALESCE(SUM(change_amount), 0) as total_change
            ')
            ->first();

        // Payment method breakdown
        $paymentMethods = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('payment_method, COUNT(*) as count, COALESCE(SUM(total), 0) as total')
            ->groupBy('payment_method')
            ->get();

        // Daily sales data for chart
        $dailySales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, COALESCE(SUM(total), 0) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Daily breakdown table
        $dailyBreakdown = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as transactions,
                COALESCE(SUM(total), 0) as revenue
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 10 products for the month
        $topProducts = SaleItem::whereHas('sale', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return view('reports.monthly', compact(
            'year', 'month', 'startDate', 'endDate', 'salesData', 'paymentMethods', 'dailySales', 'dailyBreakdown', 'topProducts'
        ));
    }

    public function products(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to) : Carbon::now();

        // Product sales report
        $productSales = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            })
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->get();

        // Total stats
        $totalQty = $productSales->sum('total_qty');
        $totalRevenue = $productSales->sum('total_revenue');

        return view('reports.products', compact(
            'dateFrom', 'dateTo', 'productSales', 'totalQty', 'totalRevenue'
        ));
    }
}
