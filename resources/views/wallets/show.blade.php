<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('wallets.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $wallet->name }}
                </h2>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('wallets.edit', $wallet) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                    Edit Dompet
                </a>
                <a href="{{ route('transactions.create', ['wallet_id' => $wallet->id]) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                    Tambah Transaksi
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Wallet Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-sm text-gray-500">Saldo Awal</div>
                            <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($wallet->opening_balance, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500">Saldo Saat Ini</div>
                            <div class="text-3xl font-bold {{ $wallet->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($wallet->balance, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500">Total Transaksi</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $transactions->total() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Transaksi</h3>
                        <div class="flex space-x-2">
                            <select onchange="window.location.href = this.value" class="rounded-md border-gray-300 text-sm">
                                <option value="{{ route('wallets.show', $wallet) }}">Semua</option>
                                <option value="{{ route('wallets.show', ['wallet' => $wallet, 'type' => 'income']) }}" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="{{ route('wallets.show', ['wallet' => $wallet, 'type' => 'expense']) }}" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                    </div>

                    @if($transactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($transactions as $transaction)
                                <div class="flex justify-between items-center py-3 border-b last:border-b-0">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $transaction->category->color ?? '#6B7280' }}"></div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $transaction->category->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->occurred_at->format('d M Y') }}
                                                @if($transaction->merchant)
                                                    • {{ $transaction->merchant }}
                                                @endif
                                                @if($transaction->note)
                                                    • {{ Str::limit($transaction->note, 50) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <a href="{{ route('transactions.edit', $transaction) }}" class="text-blue-600 hover:underline">Edit</a>
                                            <span class="mx-1">•</span>
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada transaksi</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambah transaksi pertama untuk dompet ini.</p>
                            <div class="mt-6">
                                <a href="{{ route('transactions.create', ['wallet_id' => $wallet->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Tambah Transaksi
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>