@extends('layouts.app')

@section('title', 'Input Barang Masuk')
@section('page-title', 'Pencatatan Barang Masuk')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Formulir Barang Masuk</h2>
            <p class="text-xs text-slate-500">Mencatat penambahan stok barang dari supplier atau pengadaan baru</p>
        </div>
        <a href="{{ route('stock-in.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition flex items-center space-x-1.5 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('stock-in.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Pilih Produk -->
            <div>
                <label for="product_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Pilih Barang <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <select name="product_id" id="product_id" required onchange="updateStockPreview(this)"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('product_id') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150 bg-white">
                        <option value="">-- Pilih Barang yang Diterima --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" data-stock="{{ $p->stock }}" data-price="{{ $p->price }}" {{ old('product_id', request('product_id')) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} (Sisa Stok: {{ $p->stock }} unit)
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('product_id')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Stock Info Pill -->
            <div id="stock-info" class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs {{ old('product_id', request('product_id')) ? '' : 'hidden' }}">
                <span class="text-slate-500 font-medium">Stok Fisik Saat Ini:</span>
                <span id="current-stock-val" class="font-bold text-slate-800">0 Unit</span>
            </div>

            <!-- Jumlah Masuk -->
            <div>
                <label for="quantity" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Barang Masuk (Unit) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" required min="1"
                           placeholder="Contoh: 10"
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('quantity') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Stok produk otomatis bertambah sesuai jumlah yang Anda masukkan.</p>
                @error('quantity')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan / Supplier -->
            <div>
                <label for="notes" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Keterangan / Supplier / No. Surat Jalan</label>
                <textarea name="notes" id="notes" rows="3"
                          placeholder="Contoh: Pengadaan dari PT. Sumber Berkah, No. PO: 1209/INV/2026..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-brand-200 focus:border-brand-500 text-sm focus:outline-none focus:ring-4 transition duration-150">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('stock-in.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center space-x-2">
                    <i class="fa-solid fa-arrow-down-to-bracket"></i>
                    <span>Simpan Barang Masuk</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function updateStockPreview(select) {
        const option = select.options[select.selectedIndex];
        const infoBox = document.getElementById('stock-info');
        const stockVal = document.getElementById('current-stock-val');

        if (option && option.value) {
            stockVal.textContent = option.dataset.stock + ' Unit';
            infoBox.classList.remove('hidden');
        } else {
            infoBox.classList.add('hidden');
        }
    }

    // Trigger on page load if preselected
    document.addEventListener('DOMContentLoaded', function() {
        const sel = document.getElementById('product_id');
        if (sel && sel.value) {
            updateStockPreview(sel);
        }
    });
</script>
@endsection
