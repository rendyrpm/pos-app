<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Dashboard</h2>
                <p class="mt-1 text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('pos.index') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 border border-transparent rounded-xl text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Buka POS
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Penjualan Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Penjualan Hari Ini</p>
                            <p class="text-xl font-bold text-emerald-600 mt-0.5 truncate">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Transaksi</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">{{ $todayTransactions }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Produk -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Produk</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">{{ $totalProducts }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stok Menipis -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition {{ $lowStockProducts > 0 ? 'ring-2 ring-amber-200' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Stok Menipis</p>
                            <p class="text-xl font-bold {{ $lowStockProducts > 0 ? 'text-amber-600' : 'text-gray-900' }} mt-0.5">{{ $lowStockProducts }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Omzet Bulan Ini</p>
                            <p class="text-lg font-bold text-blue-600 mt-1">Rp {{ number_format($monthSales, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rata-rata/Transaksi</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik Penjualan 7 Hari -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Grafik Penjualan</h3>
                            <p class="text-xs text-gray-500 mt-0.5">7 hari terakhir</p>
                        </div>
                    </div>
                    @php
                        $maxTotal = $chartFull->max('total') ?: 1;
                    @endphp
                    @if($chartFull->sum('total') > 0)
                        <div class="flex items-end gap-2 h-48">
                            @foreach($chartFull as $day)
                                @php
                                    $height = $maxTotal > 0 ? ($day['total'] / $maxTotal) * 100 : 0;
                                    $date = \Carbon\Carbon::parse($day['date']);
                                    $isToday = $date->isToday();
                                @endphp
                                <div class="flex-1 flex flex-col items-center justify-end h-full">
                                    <div class="text-[10px] font-semibold {{ $isToday ? 'text-blue-600' : 'text-gray-500' }} mb-1 opacity-0 hover:opacity-100 transition">
                                        Rp {{ number_format($day['total'], 0, ',', '.') }}
                                    </div>
                                    <div class="w-full rounded-t-lg transition-all duration-500 cursor-pointer {{ $isToday ? 'bg-blue-500 hover:bg-blue-600' : 'bg-blue-300 hover:bg-blue-400' }}"
                                         style="height: {{ max($height, 3) }}%"></div>
                                    <div class="text-[11px] {{ $isToday ? 'font-bold text-blue-600' : 'text-gray-400' }} mt-2">
                                        {{ $date->format('D') }}
                                    </div>
                                    <div class="text-[10px] {{ $isToday ? 'text-blue-600' : 'text-gray-400' }}">
                                        {{ $date->format('d/m') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-48 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <p class="text-sm text-gray-400">Belum ada data penjualan</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Produk Terlaris Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Produk Terlaris Hari Ini</h3>
                    @forelse($topProductsToday as $index => $product)
                        <div class="flex items-center gap-3 {{ !$loop->last ? 'mb-3' : '' }}">
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-700 font-bold text-sm shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $product->product_name }}</p>
                                <p class="text-xs text-gray-500">{{ $product->total_qty }} terjual</p>
                            </div>
                            <span class="text-sm font-semibold text-emerald-600 whitespace-nowrap">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <p class="text-sm text-gray-400">Belum ada penjualan hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Transaksi Terbaru -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
                        <a href="{{ route('sales.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Semua →</a>
                    </div>
                    @if($recentSales->isEmpty())
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-gray-500">Belum ada transaksi</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach($recentSales as $sale)
                                <a href="{{ route('sales.show', $sale) }}" class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 transition">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                                        @if($sale->payment_method === 'qris')
                                            <span class="text-xs font-bold text-blue-600">QR</span>
                                        @else
                                            <span class="text-xs font-bold text-green-600">TX</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $sale->transaction_number }}</p>
                                        <p class="text-xs text-gray-500">{{ $sale->user->name }} · {{ $sale->created_at->format('H:i') }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-sm font-semibold text-emerald-600">Rp {{ number_format($sale->total, 0, ',', '.') }}</p>
                                        @if($sale->payment_method === 'qris')
                                            <span class="text-[10px] font-semibold text-blue-600">QRIS</span>
                                        @else
                                            <span class="text-[10px] font-semibold text-green-600">Tunai</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Stok Menipis -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Stok Menipis</h3>
                        <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Kelola Produk →</a>
                    </div>
                    @if($lowStockList->isEmpty())
                        <div class="px-6 py-12 text-center">
                            <div class="w-12 h-12 mx-auto bg-emerald-100 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-gray-500 font-medium">Semua stok aman</p>
                            <p class="text-sm text-gray-400 mt-1">Tidak ada produk yang perlu restok</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach($lowStockList as $product)
                                <div class="flex items-center gap-4 px-6 py-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                                        <span class="text-xs font-bold text-amber-600">
                                            {{ $product->stock }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">Min: {{ $product->min_stock }} · {{ $product->unit }}</p>
                                    </div>
                                    <div class="shrink-0">
                                        @if($product->stock === 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">Habis</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">Menipis</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
