<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Transaksi
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('sales.receipt', $sale) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Cetak Struk
                </a>
                @if(auth()->user()->role === 'admin')
                    <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi {{ $sale->transaction_number }}? Stok produk akan dikembalikan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Hapus Transaksi
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Transaction Info -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">No. Transaksi</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $sale->transaction_number }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tanggal</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Kasir</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $sale->user->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Metode Pembayaran</h3>
                            <p class="mt-1">
                                @if($sale->payment_method === 'qris')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">QRIS</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tunai</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Pembelian</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sale->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->product_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="font-medium">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    @if($sale->discount > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Diskon</span>
                                        <span class="font-medium text-red-600">- Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between text-lg font-bold border-t pt-3">
                                        <span>Total</span>
                                        <span class="text-indigo-600">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Pembayaran</span>
                                        <span class="font-medium">Rp {{ number_format($sale->payment, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Kembalian</span>
                                        <span class="font-medium">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="mt-6">
                        <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
