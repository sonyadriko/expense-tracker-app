<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Hari Ini</h3>
                        <div class="space-y-1">
                            <div class="flex justify-between">
                                <span class="text-green-600">Pemasukan:</span>
                                <span class="font-medium">Rp {{ number_format($todayIncome, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-600">Pengeluaran:</span>
                                <span class="font-medium">Rp {{ number_format($todayExpense, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="flex justify-between">
                                <span class="font-semibold">Saldo:</span>
                                <span class="font-bold {{ ($todayIncome - $todayExpense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($todayIncome - $todayExpense, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- This Week -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Minggu Ini</h3>
                        <div class="space-y-1">
                            <div class="flex justify-between">
                                <span class="text-green-600">Pemasukan:</span>
                                <span class="font-medium">Rp {{ number_format($weekIncome, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-600">Pengeluaran:</span>
                                <span class="font-medium">Rp {{ number_format($weekExpense, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="flex justify-between">
                                <span class="font-semibold">Saldo:</span>
                                <span class="font-bold {{ ($weekIncome - $weekExpense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($weekIncome - $weekExpense, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- This Month -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Bulan Ini</h3>
                        <div class="space-y-1">
                            <div class="flex justify-between">
                                <span class="text-green-600">Pemasukan:</span>
                                <span class="font-medium">Rp {{ number_format($monthIncome, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-600">Pengeluaran:</span>
                                <span class="font-medium">Rp {{ number_format($monthExpense, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="flex justify-between">
                                <span class="font-semibold">Saldo:</span>
                                <span class="font-bold {{ ($monthIncome - $monthExpense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($monthIncome - $monthExpense, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Categories -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Kategori Bulan Ini</h3>
                        @if($topCategories->count() > 0)
                            <div class="space-y-3">
                                @foreach($topCategories as $category)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                                            <span class="text-gray-700">{{ $category->name }}</span>
                                        </div>
                                        <span class="font-medium text-gray-900">Rp {{ number_format($category->total, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada transaksi bulan ini.</p>
                        @endif
                    </div>
                </div>

                <!-- Wallet Balances -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Saldo Dompet</h3>
                        @if($wallets->count() > 0)
                            <div class="space-y-3">
                                @foreach($wallets as $wallet)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">{{ $wallet['name'] }}</span>
                                        <span class="font-medium {{ $wallet['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            Rp {{ number_format($wallet['balance'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada dompet. <a href="{{ route('wallets.create') }}" class="text-blue-600 hover:underline">Buat dompet</a></p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
                        <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:underline text-sm">Lihat Semua</a>
                    </div>
                    @if($recentTransactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentTransactions as $transaction)
                                <div class="flex justify-between items-center py-2 border-b last:border-b-0">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $transaction->category->color ?? '#6B7280' }}"></div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $transaction->category->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->wallet->name }} • {{ $transaction->occurred_at->format('d M Y') }}
                                                @if($transaction->merchant)
                                                    • {{ $transaction->merchant }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="font-medium {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Belum ada transaksi. <a href="{{ route('transactions.create') }}" class="text-blue-600 hover:underline">Tambah transaksi</a></p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('transactions.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-center transition">
                            Tambah Transaksi
                        </a>
                        <a href="{{ route('wallets.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-center transition">
                            Buat Dompet
                        </a>
                        <a href="{{ route('categories.create') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-center transition">
                            Buat Kategori
                        </a>
                        <a href="{{ route('reports.monthly') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center transition">
                            Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
