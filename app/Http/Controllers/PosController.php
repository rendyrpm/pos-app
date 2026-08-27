<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->withCount('products')->get();
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->get();

        return view('pos.index', compact('categories', 'products'));
    }

    public function search(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('stock', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->with('category')->get();

        return response()->json($products);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'payment' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,qris',
        ]);

        $maxRetries = 3;

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                DB::beginTransaction();

                // Hitung subtotal
                $subtotal = 0;
                $items = [];

                foreach ($request->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    // Validasi stok
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi. Stok tersisa: {$product->stock}");
                    }

                    $itemSubtotal = $product->sell_price * $item['quantity'];
                    $subtotal += $itemSubtotal;

                    $items[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $product->sell_price,
                        'subtotal' => $itemSubtotal,
                    ];

                    // Kurangi stok
                    $product->decrement('stock', $item['quantity']);
                }

                $discount = $request->discount ?? 0;
                $total = $subtotal - $discount;
                $payment = $request->payment;
                $changeAmount = $payment - $total;

                if ($payment < $total) {
                    throw new \Exception("Pembayaran tidak mencukupi.");
                }

                // Buat transaksi
                $sale = Sale::create([
                    'transaction_number' => Sale::generateTransactionNumber(),
                    'user_id' => auth()->id(),
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'payment' => $payment,
                    'change_amount' => $changeAmount,
                    'payment_method' => $request->payment_method ?? 'cash',
                    'status' => 'completed',
                ]);

                // Buat detail transaksi
                foreach ($items as $item) {
                    $sale->items()->create($item);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil!',
                    'sale' => $sale->load('items', 'user'),
                ]);

            } catch (QueryException $e) {
                DB::rollBack();
                // Retry jika nomor transaksi duplikat (error code 1062)
                if ($e->errorInfo[1] == 1062 && $attempt < $maxRetries - 1) {
                    continue;
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan database. Silakan coba lagi.',
                ], 400);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal memproses transaksi setelah beberapa percobaan.',
        ], 400);
    }

    public function qrCode(Request $request)
    {
        $amount = $request->filled('amount') ? (float) $request->amount : 0;

        $data = json_encode([
            'merchant' => config('qris.merchant_name', 'POS App'),
            'amount' => $amount,
            'currency' => 'IDR',
        ]);

        $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate($data);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }
}
