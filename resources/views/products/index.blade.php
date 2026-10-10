@extends('layouts.app')

@section('title', auth()->user()->role === 'admin' ? 'Kelola Barang' : 'Lihat Barang')
@section('page-title', auth()->user()->role === 'admin' ? 'Kelola Master Barang' : 'Katalog & Stok Barang')

@section('content')
<div class="space-y-6">

    <!-- Filters & Action Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            
            <!-- Search & Filters Form -->
            <form action="{{ route('products.index') }}" method="GET" class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <!-- Search input -->
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang..."
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-4 focus:ring-brand-100 focus:border-brand-500 text-xs sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="relative">
                    <select name="category_id" onchange="this.form.submit()"
                            class="w-full py-2 px-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-4 focus:ring-brand-100 focus:border-brand-500 text-xs sm:text-sm bg-white">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Status Filter -->
                <div class="flex items-center gap-2">
                    <select name="stock_filter" onchange="this.form.submit()"
                            class="w-full py-2 px-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-4 focus:ring-brand-100 focus:border-brand-500 text-xs sm:text-sm bg-white">
                        <option value="">Semua Status Stok</option>
                        <option value="available" {{ $stockFilter === 'available' ? 'selected' : '' }}>Stok Tersedia (> 0)</option>
                        <option value="low" {{ $stockFilter === 'low' ? 'selected' : '' }}>Stok Kritis (≤ 10)</option>
                        <option value="empty" {{ $stockFilter === 'empty' ? 'selected' : '' }}>Stok Habis (0)</option>
                    </select>

                    <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-900 transition flex items-center">
                        <i class="fa-solid fa-filter mr-1.5"></i> Filter
                    </button>
                    
                    @if($search || $categoryId || $stockFilter)
                        <a href="{{ route('products.index') }}" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition text-xs flex items-center justify-center border border-rose-200" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Admin Action Button -->
            @if(auth()->user()->role === 'admin')
                <div class="flex-shrink-0">
                    <a href="{{ route('products.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-lg shadow-brand-500/20 transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Barang Baru</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-xs uppercase font-bold text-slate-600">
                    <tr>
                        <th class="py-4 px-6 w-14">No</th>
                        <th class="py-4 px-6">Foto & Nama Barang</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6">Harga Satuan</th>
                        <th class="py-4 px-6">Sisa Stok</th>
                        <th class="py-4 px-6 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $index => $product)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-medium text-slate-400">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200/80 overflow-hidden flex items-center justify-center flex-shrink-0">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-box text-slate-400 text-lg"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $product) }}" class="font-bold text-slate-800 hover:text-brand-600 transition truncate block">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->description)
                                            <p class="text-xs text-slate-400 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-brand-700 border border-indigo-100">
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                @if($product->stock <= 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                        <i class="fa-solid fa-circle-xmark mr-1.5 text-[10px]"></i> Habis (0)
                                    </span>
                                @elseif($product->stock <= 10)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800" title="Stok Kritis">
                                        <i class="fa-solid fa-triangle-exclamation mr-1.5 text-[10px]"></i> {{ $product->stock }} Unit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-check mr-1.5 text-[10px]"></i> {{ $product->stock }} Unit
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <!-- Detail (Semua Role) -->
                                    <a href="{{ route('products.show', $product) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Lihat Detail Barang">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                        <!-- Edit (Admin) -->
                                        <a href="{{ route('products.edit', $product) }}" class="p-2 text-slate-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Edit Barang">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>

                                        <!-- Hapus (Admin) -->
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang {{ $product->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Barang">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-2xl text-slate-400">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </div>
                                <p class="text-sm font-medium">Tidak ada data barang yang sesuai kriteria.</p>
                                @if($search || $categoryId || $stockFilter)
                                    <a href="{{ route('products.index') }}" class="text-xs text-brand-600 underline mt-1 inline-block">Reset semua filter</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
