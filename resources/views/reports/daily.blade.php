<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Laporan Harian</h2>
                <p class="mt-1 text-sm text-gray-500">Ringkasan penjualan per hari</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.daily', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </a>
                <form method="GET" action="{{ route('reports.daily') }}" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
                           class="border-gray-300 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition">
                        Lihat
                    </button>
                </form>
                <a href="{{ route('reports.daily', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 transition">
                    Selanjutnya
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Date Header -->
            <div class="text-center">
                <span class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-full text-blue-700 font-semibold text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $date->translatedFormat('l, d F Y') }}
                </span>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Transaksi -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($salesData->total_transactions) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Omzet -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Omzet</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($salesData->total_revenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Diskon -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Diskon</p>
                            <p class="text-2xl font-bold text-orange-600 mt-1">Rp {{ number_format($salesData->total_discount, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata per Transaksi -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Rata-rata/Transaksi</p>
                            <p class="text-2xl font-bold text-purple-600 mt-1">
                                Rp {{ $salesData->total_transactions > 0 ? number_format($salesData->total_revenue / $salesData->total_transactions, 0, ',', '.') : '0' }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Payment Methods -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Metode Pembayaran</h3>
                    @forelse($paymentMethods as $pm)
                        <div class="flex items-center justify-between p-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-center gap-3">
                                @if($pm->payment_method === 'qris')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        QRIS
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Tunai
                                    </span>
                                @endif
                                <span class="text-sm text-gray-600">{{ $pm->count }} transaksi</span>
                            </div>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($pm->total, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada transaksi hari ini</p>
                    @endforelse
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Produk Terlaris Hari Ini</h3>
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-center gap-3 p-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-700 font-bold text-sm">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $product->product_name }}</p>
                                <p class="text-sm text-gray-500">{{ $product->total_qty }} terjual</p>
                            </div>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada penjualan hari ini</p>
                    @endforelse
                </div>
            </div>

            <!-- Hourly Sales Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Penjualan per Jam</h3>
                @if($hourlySales->count() > 0)
                    <div class="flex items-end gap-1 h-48">
                        @php
                            $maxTotal = $hourlySales->max('total');
                        @endphp
                        @for($hour = 0; $hour <= 23; $hour++)
                            @php
                                $hourData = $hourlySales->firstWhere('hour', $hour);
                                $height = $maxTotal > 0 ? ($hourData ? ($hourData->total / $maxTotal) * 100 : 0) : 0;
                            @endphp
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-blue-500 rounded-t hover:bg-blue-600 transition cursor-pointer relative group"
                                     style="height: {{ max($height, 2) }}%">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                        Rp {{ $hourData ? number_format($hourData->total, 0, ',', '.') : '0' }}
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="flex gap-1 mt-2">
                        @for($hour = 0; $hour <= 23; $hour++)
                            <div class="flex-1 text-center text-xs text-gray-500">
                                @if($hour % 3 === 0)
                                    {{ sprintf('%02d', $hour) }}
                                @endif
                            </div>
                        @endfor
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-8">Belum ada data penjualan</p>
                @endif
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
                </div>
                @if($recentSales->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No. Transaksi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Metode</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentSales as $sale)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-medium text-gray-800">{{ $sale->transaction_number }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->created_at->format('H:i') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->user->name }}</td>
                                        <td class="px-6 py-4">
                                            @if($sale->payment_method === 'qris')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">QRIS</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Tunai</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-right text-emerald-600">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500">Belum ada transaksi hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
