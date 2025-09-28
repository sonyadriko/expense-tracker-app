<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('wallets.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Dompet') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('wallets.store') }}">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Dompet</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror" 
                                   placeholder="Contoh: Kas, Bank BCA, E-Wallet" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="currency_code" class="block text-sm font-medium text-gray-700">Mata Uang</label>
                            <select name="currency_code" id="currency_code" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('currency_code') border-red-300 @enderror">
                                <option value="IDR" {{ old('currency_code', 'IDR') === 'IDR' ? 'selected' : '' }}>IDR (Indonesian Rupiah)</option>
                                <option value="USD" {{ old('currency_code') === 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                                <option value="EUR" {{ old('currency_code') === 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                            </select>
                            @error('currency_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="opening_balance" class="block text-sm font-medium text-gray-700">Saldo Awal</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="opening_balance" id="opening_balance" value="{{ old('opening_balance', 0) }}" 
                                       class="block w-full pl-12 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('opening_balance') border-red-300 @enderror" 
                                       placeholder="0" min="0" step="0.01">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Masukkan saldo awal dompet ini. Kosongkan atau isi 0 jika tidak ada saldo awal.</p>
                            @error('opening_balance')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('wallets.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                                Simpan Dompet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>