<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $sale->transaction_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 12px; background: #f5f5f5; padding: 20px; }
        .receipt { width: 280px; margin: 0 auto; background: #fff; padding: 20px; border: 1px solid #ddd; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .my-3 { margin: 12px 0; }
        .pt-2 { padding-top: 8px; }
        .border-t { border-top: 1px dashed #999; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .text-gray-500 { color: #6b7280; }
        .text-gray-700 { color: #374151; }
        .text-indigo-600 { color: #4f46e5; }
        .text-green-600 { color: #16a34a; }
        .text-red-500 { color: #ef4444; }
        .text-sm { font-size: 11px; }
        .space-y-1 > * + * { margin-top: 4px; }
        .btn-print { display: block; width: 280px; margin: 16px auto 0; padding: 10px; background: #4f46e5; color: #fff; text-align: center; border: none; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; }
        .btn-print:hover { background: #4338ca; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { border: none; box-shadow: none; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="text-center mb-3">
            <div class="font-bold text-lg">POS App</div>
            <div class="text-sm text-gray-500">Jl. Contoh No. 123</div>
            <div class="text-sm text-gray-500">Telp: 08123456789</div>
        </div>

        <div class="border-t my-3"></div>

        <div class="text-sm mb-1">
            <span class="text-gray-500">No:</span>
            <span class="font-bold">{{ $sale->transaction_number }}</span>
        </div>
        <div class="text-sm mb-1">
            <span class="text-gray-500">Tanggal:</span>
            <span>{{ $sale->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="text-sm mb-1">
            <span class="text-gray-500">Kasir:</span>
            <span>{{ $sale->user->name }}</span>
        </div>
        <div class="text-sm mb-3">
            <span class="text-gray-500">Metode:</span>
            <span class="font-bold">{{ $sale->payment_method === 'qris' ? 'QRIS' : 'Tunai' }}</span>
        </div>

        <div class="border-t my-3"></div>

        <div class="space-y-1 mb-3">
            @foreach($sale->items as $item)
            <div class="flex justify-between text-sm">
                <span class="text-gray-700">
                    {{ $item->product_name }}
                    <span class="text-gray-500">x{{ $item->quantity }}</span>
                </span>
                <span class="font-bold">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <div class="border-t my-3"></div>

        <div class="space-y-1">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span>{{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($sale->discount > 0)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Diskon</span>
                <span class="text-red-500">- {{ number_format($sale->discount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between font-bold text-sm pt-2 border-t">
                <span>TOTAL</span>
                <span class="text-indigo-600">{{ number_format($sale->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm pt-1">
                <span class="text-gray-500">Tunai</span>
                <span>{{ number_format($sale->payment, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold text-sm">
                <span class="text-gray-500">Kembali</span>
                <span class="text-green-600">{{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="border-t my-3"></div>

        <div class="text-center text-sm text-gray-500">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p class="mt-1">Barang yang sudah dibeli</p>
            <p>tidak dapat dikembalikan.</p>
        </div>
    </div>

    <button class="btn-print" onclick="window.print()">Cetak Struk</button>
</body>
</html>
