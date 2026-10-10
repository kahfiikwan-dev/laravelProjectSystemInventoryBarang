@extends('layouts.app')

@section('title', 'Tambah Barang Baru')
@section('page-title', 'Tambah Barang')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Formulir Tambah Barang</h2>
            <p class="text-xs text-slate-500">Daftarkan item barang baru ke dalam sistem inventaris</p>
        </div>
        <a href="{{ route('products.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition flex items-center space-x-1.5 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Barang -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Nama Barang <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           placeholder="Contoh: Laptop Lenovo ThinkPad L14 Gen 4"
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Kategori Barang <span class="text-rose-500">*</span></label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('category_id') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150 bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Satuan -->
                <div>
                    <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-semibold text-xs">
                            Rp
                        </div>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" step="1"
                               placeholder="150000"
                               class="w-full pl-12 pr-4 py-2.5 rounded-xl border {{ $errors->has('price') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150">
                    </div>
                    @error('price')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok Awal -->
                <div>
                    <label for="stock" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Stok Awal <span class="text-rose-500">*</span></label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required min="0"
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('stock') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150">
                    @error('stock')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Barang -->
                <div>
                    <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Foto Produk (Opsional)</label>
                    <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</p>
                    @error('image')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror

                    <!-- Preview Container -->
                    <div id="image-preview-container" class="mt-3 hidden">
                        <img id="image-preview" src="#" alt="Preview" class="w-24 h-24 object-cover rounded-xl border border-slate-200 shadow-sm">
                    </div>
                </div>

                <!-- Deskripsi Barang -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi / Spesifikasi Barang</label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="Masukkan catatan spesifikasi, nomor seri, atau keterangan barang..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-brand-200 focus:border-brand-500 text-sm focus:outline-none focus:ring-4 transition duration-150">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs rounded-xl shadow-lg shadow-brand-500/20 transition flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Barang</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const container = document.getElementById('image-preview-container');
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
