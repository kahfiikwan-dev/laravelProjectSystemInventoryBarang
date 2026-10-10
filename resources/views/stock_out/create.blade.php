@extends('layouts.app')

@section('title', 'Input Barang Keluar')
@section('page-title', 'Pencatatan Barang Keluar')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Formulir Barang Keluar</h2>
            <p class="text-xs text-slate-500">Mencatat pengeluaran stok barang untuk divisi, cabang, atau konsumen</p>
        </div>
        <a href="{{ route('stock-out.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition flex items-center space-x-1.5 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('stock-out.store') }}" method="POST" id="form-stock-out" class="space-y-5">
            @csrf

            <!-- Pilih Produk -->
            <div>
                <label for="product_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Pilih Barang yang Dikeluarkan <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <select name="product_id" id="product_id" required onchange="checkMaxStock(this)"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('product_id') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150 bg-white">
                        <option value="">-- Pilih Barang (Hanya yang memiliki stok) --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" data-stock="{{ $p->stock }}" {{ old('product_id', request('product_id')) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} — Tersedia: {{ $p->stock }} unit
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('product_id')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Stock Info Pill -->
            <div id="stock-info" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs {{ old('product_id', request('product_id')) ? '' : 'hidden' }}">
                <span class="text-slate-500 font-medium">Batas Maksimum Pengeluaran:</span>
                <span id="max-stock-val" class="font-bold text-slate-800">0 Unit</span>
            </div>

            <!-- Jumlah Keluar -->
            <div>
                <label for="quantity" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Barang Keluar (Unit) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" required min="1" oninput="validateQuantityInput()"
                           placeholder="Contoh: 5"
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('quantity') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150">
                </div>
                <div id="stock-error-warning" class="hidden mt-1.5 p-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span id="stock-error-text">Jumlah keluar tidak boleh melebihi sisa stok yang tersedia!</span>
                </div>
                @error('quantity')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan / Tujuan Pengeluaran -->
            <div>
                <label for="notes" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Tujuan Pengeluaran / Divisi / Keterangan</label>
                <textarea name="notes" id="notes" rows="3"
                          placeholder="Contoh: Permintaan divisi Keuangan untuk staf baru, Ref No: OUT/2026/04..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-brand-200 focus:border-brand-500 text-sm focus:outline-none focus:ring-4 transition duration-150">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('stock-out.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" id="btn-submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-lg shadow-rose-500/20 transition flex items-center space-x-2">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    <span>Simpan Barang Keluar</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    let currentAvailableStock = 0;

    function checkMaxStock(select) {
        const option = select.options[select.selectedIndex];
        const infoBox = document.getElementById('stock-info');
        const maxVal = document.getElementById('max-stock-val');
        const qtyInput = document.getElementById('quantity');

        if (option && option.value) {
            currentAvailableStock = parseInt(option.dataset.stock) || 0;
            maxVal.textContent = currentAvailableStock + ' Unit Tersedia';
            qtyInput.max = currentAvailableStock;
            infoBox.classList.remove('hidden');
        } else {
            currentAvailableStock = 0;
            infoBox.classList.add('hidden');
        }

        validateQuantityInput();
    }

    function validateQuantityInput() {
        const qtyInput = document.getElementById('quantity');
        const errorBox = document.getElementById('stock-error-warning');
        const btnSubmit = document.getElementById('btn-submit');
        const requested = parseInt(qtyInput.value) || 0;

        if (currentAvailableStock > 0 && requested > currentAvailableStock) {
            errorBox.classList.remove('hidden');
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            errorBox.classList.add('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sel = document.getElementById('product_id');
        if (sel && sel.value) {
            checkMaxStock(sel);
        }
    });
</script>
@endsection
