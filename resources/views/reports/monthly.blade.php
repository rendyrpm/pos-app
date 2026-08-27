<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Laporan Bulanan</h2>
                <p class="mt-1 text-sm text-gray-500">Ringkasan penjualan per bulan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Month Selector -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <form method="GET" action="{{ route('reports.monthly') }}" class="flex items-center justify-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Bulan:</label>
                        <select name="month" class="border-gray-300 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Tahun:</label>
                        <select name="year" class="border-gray-300 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition">
                        Tampilkan
                    </button>
                </form>
            </div>

            <!-- Month Header -->
            <div class="text-center">
                <span class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-full text-blue-700 font-semibold text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                </span>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">QRIS</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Tunai</span>
                                @endif
                                <span class="text-sm text-gray-600">{{ $pm->count }} transaksi</span>
                            </div>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($pm->total, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada transaksi bulan ini</p>
                    @endforelse
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Produk Terlaris Bulan Ini</h3>
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
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada penjualan bulan ini</p>
                    @endforelse
                </div>
            </div>

            <!-- Daily Sales Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Penjualan Harian</h3>
                @if($dailySales->count() > 0)
                    @php
                        $maxDaily = $dailySales->max('total');
                    @endphp
                    <div class="flex items-end gap-1 h-48">
                        @for($day = 1; $day <= $endDate->daysInMonth; $day++)
                            @php
                                $dateStr = $startDate->copy()->day($day)->format('Y-m-d');
                                $dayData = $dailySales->firstWhere('date', $dateStr);
                                $height = $maxDaily > 0 ? ($dayData ? ($dayData->total / $maxDaily) * 100 : 0) : 0;
                            @endphp
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-blue-500 rounded-t hover:bg-blue-600 transition cursor-pointer relative group"
                                     style="height: {{ max($height, 2) }}%">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">
                                        {{ $day }}: Rp {{ $dayData ? number_format($dayData->total, 0, ',', '.') : '0' }}
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-8">Belum ada data penjualan</p>
                @endif
            </div>

            <!-- Daily Breakdown Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Penjualan Harian</h3>
                </div>
                @if($dailyBreakdown->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Transaksi</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Omzet</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($dailyBreakdown as $db)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                            {{ \Carbon\Carbon::parse($db->date)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-center">{{ $db->transactions }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-right text-emerald-600">
                                            Rp {{ number_format($db->revenue, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-right">
                                            Rp {{ $db->transactions > 0 ? number_format($db->revenue / $db->transactions, 0, ',', '.') : '0' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 font-semibold">
                                    <td class="px-6 py-4 text-sm text-gray-800">Total</td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ number_format($dailyBreakdown->sum('transactions')) }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-emerald-600">
                                        Rp {{ number_format($dailyBreakdown->sum('revenue'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-800">
                                        {{ $dailyBreakdown->sum('transactions') > 0
                                            ? 'Rp ' . number_format($dailyBreakdown->sum('revenue') / $dailyBreakdown->sum('transactions'), 0, ',', '.')
                                            : 'Rp 0' }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500">Belum ada data penjualan bulan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
