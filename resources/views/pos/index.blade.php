<x-pos-layout>
    <div class="h-full flex flex-col lg:flex-row" x-data="posApp()" x-init="init()">

        <!-- ==================== LEFT PANEL: PRODUCTS ==================== -->
        <div class="flex-1 flex flex-col min-h-0 lg:h-full">
            <!-- Search & Filters -->
            <div class="bg-white border-b border-gray-200 px-4 py-3 shrink-0">
                <div class="flex items-center gap-3">
                    <!-- Search Bar -->
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="search-input"
                            x-model="searchQuery"
                            @input="searchProducts()"
                            placeholder="Cari produk (nama, SKU, atau scan barcode)..."
                            class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center" x-show="searchQuery.length > 0">
                            <button @click="searchQuery = ''; searchProducts()" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Item Count Badge -->
                    <div class="hidden sm:flex items-center gap-1.5 text-sm text-gray-500 shrink-0">
                        <span class="font-medium text-gray-700" x-text="filteredProducts.length"></span>
                        <span>produk</span>
                    </div>
                </div>

                <!-- Category Pills -->
                <div class="mt-3 flex gap-2 overflow-x-auto pb-1 scrollbar-thin">
                    <button
                        @click="filterCategory(null)"
                        :class="selectedCategory === null ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all shrink-0"
                    >
                        Semua
                    </button>
                    @foreach($categories as $category)
                        <button
                            @click="filterCategory({{ $category->id }})"
                            :class="selectedCategory === {{ $category->id }} ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all shrink-0"
                        >
                            {{ $category->name }}
                            <span class="ml-1 opacity-70">{{ $category->products_count }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto p-3 lg:p-4">
                <!-- Empty State -->
                <div x-show="filteredProducts.length === 0" class="flex flex-col items-center justify-center h-full text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">Produk tidak ditemukan</p>
                    <p class="text-gray-400 text-sm mt-1">Coba kata kunci lain atau pilih kategori berbeda</p>
                </div>

                <!-- Product Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 gap-2.5 lg:gap-3">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <button
                            @click="addToCart(product)"
                            class="group bg-white rounded-xl border border-gray-200 p-3 text-left hover:border-indigo-300 hover:shadow-md active:scale-[0.97] transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                        >
                            <div class="flex items-start justify-between gap-1 mb-2">
                                <div class="text-xs font-medium text-gray-500 bg-gray-50 px-1.5 py-0.5 rounded" x-text="product.sku"></div>
                                <div class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full"
                                     :class="product.stock <= product.min_stock ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'">
                                    Stok: <span x-text="product.stock"></span>
                                </div>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-800 leading-tight mb-1 line-clamp-2 min-h-[2.5rem] group-hover:text-indigo-700 transition-colors" x-text="product.name"></h3>
                            <div class="text-xs text-gray-400 mb-2" x-text="product.category?.name || ''"></div>
                            <div class="text-base font-bold text-indigo-600">
                                Rp<span x-text="formatNumber(product.sell_price)"></span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- ==================== RIGHT PANEL: CART ==================== -->
        <div class="w-full lg:w-[380px] xl:w-[420px] bg-white border-t lg:border-t-0 lg:border-l border-gray-200 flex flex-col shrink-0"
             :class="{ 'h-[50vh] sm:h-[45vh] lg:h-full': cart.length > 0, 'h-auto lg:h-full': cart.length === 0 }">

            <!-- Cart Header -->
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Keranjang</h2>
                        <p class="text-[11px] text-gray-400">
                            <span x-text="cart.reduce((s, i) => s + i.quantity, 0)"></span> item
                        </p>
                    </div>
                </div>
                <button
                    x-show="cart.length > 0"
                    @click="if(confirm('Yakin ingin mengosongkan keranjang?')) clearCart()"
                    class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1 rounded-lg hover:bg-red-50 transition-colors"
                >
                    Kosongkan
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto min-h-0 bg-gray-50/50">
                <!-- Empty Cart -->
                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full text-center px-4 py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 text-sm font-medium">Belum ada item</p>
                    <p class="text-gray-300 text-xs mt-1">Pilih produk dari daftar sebelah kiri</p>
                </div>

                <!-- Cart Item List -->
                <div class="p-2 space-y-2">
                    <template x-for="(item, index) in cart" :key="item.product.id">
                        <div class="bg-white rounded-xl p-3 border border-gray-100 shadow-sm">
                            <div class="flex items-start justify-between gap-2">
                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-800 truncate leading-tight" x-text="item.product.name"></h4>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Rp<span x-text="formatNumber(item.product.sell_price)"></span> / <span x-text="item.product.unit"></span>
                                    </p>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-sm font-bold text-indigo-600 shrink-0">
                                    Rp<span x-text="formatNumber(item.subtotal)"></span>
                                </div>
                            </div>

                            <!-- Quantity Controls & Delete -->
                            <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-gray-100">
                                <div class="flex items-center gap-0 bg-gray-50 rounded-lg p-0.5">
                                    <button
                                        @click="decrementQty(index)"
                                        class="w-8 h-8 flex items-center justify-center rounded-md bg-white text-gray-600 hover:bg-gray-100 active:scale-95 transition-all text-lg font-bold shadow-sm"
                                    >-</button>
                                    <input
                                        type="number"
                                        x-model.number="item.quantity"
                                        @change="updateSubtotal(index)"
                                        min="1"
                                        :max="item.product.stock"
                                        class="w-12 h-8 text-center text-sm font-semibold border-0 bg-transparent focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    >
                                    <button
                                        @click="incrementQty(index)"
                                        class="w-8 h-8 flex items-center justify-center rounded-md bg-white text-gray-600 hover:bg-gray-100 active:scale-95 transition-all text-lg font-bold shadow-sm"
                                        :class="{ 'opacity-30 cursor-not-allowed': item.quantity >= item.product.stock }"
                                    >+</button>
                                </div>
                                <button
                                    @click="removeFromCart(index)"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ==================== PAYMENT SECTION ==================== -->
            <div class="border-t border-gray-200 bg-white lg:shrink-0" x-show="cart.length > 0">
                <!-- Summary -->
                <div class="px-4 pt-3 pb-2 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium text-gray-700">Rp <span x-text="formatNumber(subtotal)"></span></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Diskon</span>
                        <div class="flex items-center gap-1">
                            <span class="text-gray-400 text-xs">Rp</span>
                            <input
                                type="number"
                                x-model.number="discount"
                                @input="calculateTotal()"
                                min="0"
                                :max="subtotal"
                                class="w-24 text-right text-sm border border-gray-200 rounded-lg px-2 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white"
                            >
                        </div>
                    </div>
                    <div class="flex justify-between items-baseline pt-2 border-t border-gray-100">
                        <span class="text-base font-bold text-gray-800">Total</span>
                        <span class="text-2xl font-extrabold text-indigo-600">Rp <span x-text="formatNumber(total)"></span></span>
                    </div>
                </div>

                <!-- Payment Method Toggle -->
                <div class="px-4 pt-3 pb-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5 block">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="setPaymentMethod('cash')"
                                :class="paymentMethod === 'cash' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Tunai
                        </button>
                        <button @click="setPaymentMethod('qris')"
                                :class="paymentMethod === 'qris' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            QRIS
                        </button>
                    </div>
                </div>

                <!-- Payment Input (Cash only) -->
                <div class="px-4 pb-3 space-y-2 sm:space-y-3" x-show="paymentMethod === 'cash'">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5 block">Pembayaran</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm font-medium">Rp</span>
                            <input
                                type="number"
                                id="payment-input"
                                x-model.number="payment"
                                @input="calculateChange()"
                                min="0"
                                placeholder="0"
                                class="block w-full pl-10 pr-4 py-2.5 sm:py-3 text-lg font-bold border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            >
                        </div>
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div class="grid grid-cols-4 gap-1.5">
                        <button @click="setPaymentExact()" class="px-2 py-1.5 sm:py-2 text-[11px] sm:text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 active:scale-95 transition-all border border-indigo-100">
                            Uang Pas
                        </button>
                        <button @click="setPayment(50000)" class="px-2 py-1.5 sm:py-2 text-[11px] sm:text-xs font-semibold bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 active:scale-95 transition-all border border-gray-200">
                            50.000
                        </button>
                        <button @click="setPayment(100000)" class="px-2 py-1.5 sm:py-2 text-[11px] sm:text-xs font-semibold bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 active:scale-95 transition-all border border-gray-200">
                            100.000
                        </button>
                        <button @click="setPayment(200000)" class="px-2 py-1.5 sm:py-2 text-[11px] sm:text-xs font-semibold bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 active:scale-95 transition-all border border-gray-200">
                            200.000
                        </button>
                    </div>

                    <!-- Change & Pay Button -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <!-- Change Display -->
                        <div class="flex-1 text-center py-2 rounded-xl"
                             :class="payment >= total && payment > 0 ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200'">
                            <div class="text-[10px] uppercase tracking-wide font-semibold"
                                 :class="payment >= total && payment > 0 ? 'text-green-600' : 'text-gray-400'">
                                Kembalian
                            </div>
                            <div class="text-base sm:text-lg font-extrabold"
                                 :class="payment >= total && payment > 0 ? 'text-green-600' : 'text-gray-400'">
                                Rp <span x-text="formatNumber(change)"></span>
                            </div>
                        </div>

                        <!-- Pay Button -->
                        <button
                            @click="checkout()"
                            :disabled="cart.length === 0 || payment < total || processing"
                            class="flex-1 py-3 sm:py-4 px-4 sm:px-6 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-extrabold text-base sm:text-lg rounded-xl transition-all active:scale-[0.97] shadow-lg shadow-green-600/20 disabled:shadow-none flex items-center justify-center gap-2"
                        >
                            <template x-if="processing">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </template>
                            <span x-text="processing ? 'Memproses...' : 'BAYAR'"></span>
                        </button>
                    </div>
                </div>

                <!-- QRIS Payment (QR Code display) -->
                <div class="px-3 sm:px-4 pb-3 sm:pb-4 space-y-2 sm:space-y-3" x-show="paymentMethod === 'qris'">
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-2.5 sm:p-4 text-center">
                        <div class="text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5 sm:mb-3">Scan QRIS</div>
                        <div class="inline-block p-1.5 sm:p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                            <div x-show="total > 0" x-ref="qrCode" class="w-[120px] h-[120px] sm:w-[180px] sm:h-[180px] flex items-center justify-center">
                                <span class="text-gray-400 text-xs sm:text-sm">Memuat QR...</span>
                            </div>
                            <div x-show="total <= 0" class="w-[120px] h-[120px] sm:w-[180px] sm:h-[180px] flex items-center justify-center bg-gray-50 rounded-lg">
                                <span class="text-gray-400 text-xs sm:text-sm">Tambah produk</span>
                            </div>
                        </div>
                        <div class="mt-1.5 sm:mt-3 text-[11px] sm:text-xs text-gray-400">
                            <span class="font-semibold text-gray-600">{{ config('qris.merchant_name', 'POS App') }}</span>
                        </div>
                        <div class="text-base sm:text-lg font-extrabold text-blue-600 mt-0.5 sm:mt-1">
                            Rp <span x-text="formatNumber(total)"></span>
                        </div>
                    </div>

                    <!-- Confirm Payment Button -->
                    <button
                        @click="checkout()"
                        :disabled="cart.length === 0 || processing"
                        class="w-full py-3 sm:py-4 px-4 sm:px-6 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-extrabold text-sm sm:text-lg rounded-xl transition-all active:scale-[0.97] shadow-lg shadow-blue-600/20 disabled:shadow-none flex items-center justify-center gap-2"
                    >
                        <template x-if="processing">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </template>
                        <span x-text="processing ? 'Memproses...' : 'Konfirmasi Pembayaran QRIS'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ==================== RECEIPT MODAL ==================== -->
        <div
            x-show="showReceipt"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            @keydown.escape.window="closeReceipt()"
        >
            <div
                x-show="showReceipt"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden"
            >
                <!-- Success Header -->
                <div class="bg-green-50 px-6 py-8 text-center relative">
                    <button @click="closeReceipt()" class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-full bg-white/60 hover:bg-white text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 success-checkmark">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Transaksi Berhasil!</h3>
                    <p class="text-sm text-gray-500 mt-1" x-text="receiptData?.transaction_number"></p>
                </div>

                <!-- Receipt Content -->
                <div class="px-6 py-4 max-h-[50vh] overflow-y-auto" id="receipt-content">
                    <div class="text-center mb-3">
                        <div class="font-bold text-base">{{ config('app.name', 'POS') }}</div>
                        <div class="text-xs text-gray-400" x-text="receiptData?.created_at ? new Date(receiptData.created_at).toLocaleString('id-ID') : ''"></div>
                    </div>

                    <div class="text-xs text-gray-500 mb-1">Kasir: <span class="font-medium text-gray-700" x-text="receiptData?.user?.name"></span></div>

                    <div class="border-t border-dashed border-gray-200 my-3"></div>

                    <div class="space-y-1.5">
                        <template x-for="item in receiptData?.items || []" :key="item.id">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">
                                    <span x-text="item.product_name"></span>
                                    <span class="text-gray-400" x-text="' x' + item.quantity"></span>
                                </span>
                                <span class="font-medium text-gray-800" x-text="'Rp ' + formatNumber(item.subtotal)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-dashed border-gray-200 my-3"></div>

                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-700" x-text="'Rp ' + formatNumber(receiptData?.subtotal)"></span>
                        </div>
                        <div class="flex justify-between" x-show="receiptData?.discount > 0">
                            <span class="text-gray-500">Diskon</span>
                            <span class="text-red-500" x-text="'- Rp ' + formatNumber(receiptData?.discount)"></span>
                        </div>
                        <div class="flex justify-between font-bold text-sm pt-1 border-t border-gray-100">
                            <span>Total</span>
                            <span class="text-indigo-600" x-text="'Rp ' + formatNumber(receiptData?.total)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tunai</span>
                            <span class="text-gray-700" x-text="'Rp ' + formatNumber(receiptData?.payment)"></span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span class="text-gray-500">Kembali</span>
                            <span class="text-green-600" x-text="'Rp ' + formatNumber(receiptData?.change_amount)"></span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-200 my-3"></div>
                    <p class="text-center text-[11px] text-gray-400">Terima kasih atas kunjungan Anda!</p>
                </div>

                <!-- Actions -->
                <div class="px-6 pb-6 flex gap-3">
                    <button @click="printReceipt()" class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Struk
                    </button>
                    <button @click="closeReceipt()" class="flex-1 py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors">
                        Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function posApp() {
            return {
                products: @json($products),
                filteredProducts: @json($products),
                cart: [],
                searchQuery: '',
                selectedCategory: null,
                subtotal: 0,
                discount: 0,
                total: 0,
                payment: 0,
                change: 0,
                paymentMethod: 'cash',
                showReceipt: false,
                receiptData: null,
                lastReceipt: null,
                processing: false,

                init() {
                    this.calculateTotal();
                    this.$nextTick(() => {
                        document.getElementById('search-input')?.focus();
                    });

                    // Keyboard shortcuts
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'F2') {
                            e.preventDefault();
                            document.getElementById('search-input')?.focus();
                        }
                        if (e.key === 'F9') {
                            e.preventDefault();
                            document.getElementById('payment-input')?.focus();
                        }
                        if (e.key === 'Escape' && !this.showReceipt) {
                            this.searchQuery = '';
                            this.searchProducts();
                            document.getElementById('search-input')?.focus();
                        }
                    });
                },

                searchProducts() {
                    let query = this.searchQuery.toLowerCase().trim();
                    this.filteredProducts = this.products.filter(p => {
                        let matchSearch = !query ||
                            p.name.toLowerCase().includes(query) ||
                            p.sku.toLowerCase().includes(query) ||
                            (p.barcode && p.barcode.includes(query));
                        let matchCategory = !this.selectedCategory || p.category_id == this.selectedCategory;
                        return matchSearch && matchCategory && p.stock > 0;
                    });
                },

                filterCategory(categoryId) {
                    this.selectedCategory = categoryId;
                    this.searchProducts();
                },

                setPaymentMethod(method) {
                    this.paymentMethod = method;
                    if (method === 'qris') {
                        this.payment = this.total;
                        this.change = 0;
                        this.fetchQrCode();
                    } else {
                        this.payment = 0;
                        this.change = 0;
                    }
                },

                fetchQrCode() {
                    if (this.total > 0 && this.$refs.qrCode) {
                        fetch(`/pos/qr-code?amount=${this.total}`)
                            .then(r => r.text())
                            .then(svg => {
                                this.$refs.qrCode.innerHTML = svg;
                            })
                            .catch(() => {
                                this.$refs.qrCode.innerHTML = '<span class="text-red-400 text-sm">Gagal memuat QR</span>';
                            });
                    }
                },

                addToCart(product) {
                    const existingIndex = this.cart.findIndex(item => item.product.id === product.id);
                    if (existingIndex >= 0) {
                        if (this.cart[existingIndex].quantity < product.stock) {
                            this.cart[existingIndex].quantity++;
                            this.cart[existingIndex].subtotal = this.cart[existingIndex].quantity * product.sell_price;
                            showToast(`${product.name} x${this.cart[existingIndex].quantity}`, 'info');
                        } else {
                            showToast('Stok tidak mencukupi!', 'error');
                        }
                    } else {
                        this.cart.push({
                            product: product,
                            quantity: 1,
                            subtotal: product.sell_price
                        });
                        showToast(`${product.name} ditambahkan`, 'success');
                    }
                    this.calculateTotal();
                },

                removeFromCart(index) {
                    const name = this.cart[index].product.name;
                    this.cart.splice(index, 1);
                    this.calculateTotal();
                    showToast(`${name} dihapus`, 'info');
                },

                clearCart() {
                    if (this.cart.length === 0) return;
                    this.cart = [];
                    this.discount = 0;
                    this.payment = 0;
                    this.paymentMethod = 'cash';
                    this.calculateTotal();
                    showToast('Keranjang dikosongkan', 'info');
                },

                incrementQty(index) {
                    const item = this.cart[index];
                    if (item.quantity < item.product.stock) {
                        item.quantity++;
                        item.subtotal = item.quantity * item.product.sell_price;
                        this.calculateTotal();
                    }
                },

                decrementQty(index) {
                    const item = this.cart[index];
                    if (item.quantity > 1) {
                        item.quantity--;
                        item.subtotal = item.quantity * item.product.sell_price;
                        this.calculateTotal();
                    }
                },

                updateSubtotal(index) {
                    const item = this.cart[index];
                    if (item.quantity < 1) item.quantity = 1;
                    if (item.quantity > item.product.stock) {
                        item.quantity = item.product.stock;
                        showToast('Stok tidak mencukupi!', 'error');
                    }
                    item.subtotal = item.quantity * item.product.sell_price;
                    this.calculateTotal();
                },

                calculateTotal() {
                    this.subtotal = this.cart.reduce((sum, item) => sum + item.subtotal, 0);
                    this.total = Math.max(0, this.subtotal - this.discount);
                    this.calculateChange();
                    if (this.paymentMethod === 'qris' && this.total > 0) {
                        this.fetchQrCode();
                    }
                },

                calculateChange() {
                    this.change = Math.max(0, (this.payment || 0) - this.total);
                },

                setPayment(amount) {
                    this.payment = amount;
                    this.calculateChange();
                },

                setPaymentExact() {
                    this.payment = this.total;
                    this.calculateChange();
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num || 0);
                },

                async checkout() {
                    if (this.processing) return;
                    if (this.cart.length === 0) {
                        showToast('Keranjang kosong!', 'error');
                        return;
                    }
                    if (this.discount > this.subtotal) {
                        showToast('Diskon tidak boleh melebihi subtotal!', 'error');
                        return;
                    }
                    // For cash, check payment amount; for QRIS, always valid
                    if (this.paymentMethod === 'cash' && this.payment < this.total) {
                        showToast('Pembayaran tidak mencukupi!', 'error');
                        return;
                    }

                    this.processing = true;
                    try {
                        const response = await fetch('{{ route("pos.checkout") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                items: this.cart.map(item => ({
                                    product_id: item.product.id,
                                    quantity: item.quantity
                                })),
                                discount: this.discount,
                                payment: this.paymentMethod === 'qris' ? this.total : this.payment,
                                payment_method: this.paymentMethod
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.receiptData = data.sale;
                            this.showReceipt = true;
                            showToast('Transaksi berhasil diselesaikan!', 'success');

                            // Reset cart
                            this.cart = [];
                            this.subtotal = 0;
                            this.discount = 0;
                            this.total = 0;
                            this.payment = 0;
                            this.change = 0;
                            this.paymentMethod = 'cash';

                            // Reload products for updated stock
                            const productsResponse = await fetch('{{ route("pos.search") }}');
                            const products = await productsResponse.json();
                            this.products = products;
                            this.searchProducts();
                        } else {
                            showToast(data.message || 'Transaksi gagal', 'error');
                        }
                    } catch (error) {
                        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        console.error(error);
                    } finally {
                        this.processing = false;
                    }
                },

                printReceipt() {
                    const receiptContent = document.getElementById('receipt-content').innerHTML;
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(`
                        <html>
                        <head>
                            <title>Struk</title>
                            <style>
                                body { font-family: 'Courier New', monospace; font-size: 11px; width: 200px; margin: 0 auto; padding: 10px 0; color: #000; }
                                .text-center { text-align: center; }
                                .font-bold { font-weight: bold; }
                                .mb-1 { margin-bottom: 4px; }
                                .mb-3 { margin-bottom: 12px; }
                                .my-3 { margin: 12px 0; }
                                .pt-1 { padding-top: 4px; }
                                .border-t { border-top: 1px dashed #999; }
                                .border-gray-100 { border-color: #f3f4f6; }
                                .flex { display: flex; }
                                .justify-between { justify-content: space-between; }
                                .text-gray-500 { color: #6b7280; }
                                .text-gray-700 { color: #374151; }
                                .text-indigo-600 { color: #4f46e5; }
                                .text-green-600 { color: #16a34a; }
                                .text-red-500 { color: #ef4444; }
                                .text-sm { font-size: 12px; }
                                .text-xs { font-size: 10px; }
                                .space-y-1 > * + * { margin-top: 4px; }
                                .pb-1 { padding-bottom: 4px; }
                            </style>
                        </head>
                        <body>
                            ${receiptContent}
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                    printWindow.print();
                },

                closeReceipt() {
                    this.showReceipt = false;
                    this.lastReceipt = this.receiptData;
                    this.receiptData = null;
                    this.$nextTick(() => {
                        document.getElementById('search-input')?.focus();
                    });
                }
            }
        }
    </script>
    @endpush
</x-pos-layout>
