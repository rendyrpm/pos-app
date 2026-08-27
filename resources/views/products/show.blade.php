<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Produk
            </h2>
            <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Edit Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Product Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Nama Produk</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $product->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Kategori</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $product->category->name ?? '-' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">SKU</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $product->sku }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Barcode</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $product->barcode ?? '-' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Harga Beli / HPP</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Harga Jual</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Stok</h3>
                            <p class="mt-1 text-lg font-semibold {{ $product->isLowStock() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $product->stock }} {{ $product->unit }}
                                @if($product->isLowStock())
                                    <span class="text-xs font-normal text-red-500">(Stok menipis)</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Minimum Stok</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $product->min_stock }} {{ $product->unit }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($product->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($product->description)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500">Deskripsi</h3>
                        <p class="mt-1 text-gray-900">{{ $product->description }}</p>
                    </div>
                    @endif

                    <!-- Back Button -->
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
