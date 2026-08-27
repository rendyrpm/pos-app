<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('transaction_number', 'like', "%{$search}%");
        }

        $sales = $query->latest()->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load('items.product', 'user');
        return view('sales.show', compact('sale'));
    }

    public function receipt(Sale $sale)
    {
        $sale->load('items.product', 'user');
        return view('sales.receipt', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            // Kembalikan stok produk
            foreach ($sale->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            // Hapus item transaksi
            $sale->items()->delete();

            // Hapus transaksi
            $sale->delete();
        });

        return redirect()->route('sales.index')
            ->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan.');
    }
}
