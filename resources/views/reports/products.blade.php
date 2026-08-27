<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Laporan Penjualan Produk</h2>
                <p class="mt-1 text-sm text-gray-500">Detail penjualan per produk</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Date Range Filter -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <form method="GET" action="{{ route('reports.products') }}" class="flex items-center justify-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Dari:</label>
                        <input type="date" name="date_from" value="{{ $dateFrom->format('Y-m-d') }}"
                               class="border-gray-300 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Sampai:</label>
                        <input type="date" name="date_to" value="{{ $dateTo->format('Y-m-d') }}"
                               class="border-gray-300 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition">
                        Tampilkan
                    </button>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Unit Terjual</p>
                            <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($totalQty) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Omzet Produk</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Produk Terjual</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $dateFrom->format('d M Y') }} — {{ $dateTo->format('d M Y') }}
                    </p>
                </div>
                @if($productSales->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Produk</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Qty Terjual</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total Omzet</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">% Omzet</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">% Qty</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($productSales as $index => $ps)
                                    @php
                                        $revenuePercent = $totalRevenue > 0 ? ($ps->total_revenue / $totalRevenue) * 100 : 0;
                                        $qtyPercent = $totalQty > 0 ? ($ps->total_qty / $totalQty) * 100 : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 font-bold text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-800">{{ $ps->product_name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                {{ $ps->total_qty }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-right text-emerald-600">
                                            Rp {{ number_format($ps->total_revenue, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $revenuePercent }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-600 w-12 text-right">{{ number_format($revenuePercent, 1) }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $qtyPercent }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-600 w-12 text-right">{{ number_format($qtyPercent, 1) }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 font-semibold">
                                    <td class="px-6 py-4" colspan="2">
                                        <span class="text-sm text-gray-800">Total {{ $productSales->count() }} Produk</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            {{ number_format($totalQty) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-emerald-600">
                                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs text-gray-600">100%</td>
                                    <td class="px-6 py-4 text-right text-xs text-gray-600">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p class="text-gray-500">Tidak ada data penjualan produk untuk periode ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
