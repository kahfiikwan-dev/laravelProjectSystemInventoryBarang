@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)
@section('page-title', 'Detail Barang & Riwayat Stok')

@section('content')
<div class="space-y-6">

    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                </span>
                <span class="text-xs text-slate-400">ID: #PRD-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 mt-1">{{ $product->name }}</h1>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('products.edit', $product) }}" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition flex items-center space-x-1.5 shadow-sm">
                    <i class="fa-regular fa-pen-to-square"></i>
                    <span>Edit Barang</span>
                </a>
            @endif
            <a href="{{ route('products.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition flex items-center space-x-1.5 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke List</span>
            </a>
        </div>
    </div>

    <!-- Product Overview Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            
            <!-- Product Photo -->
            <div class="w-full aspect-square rounded-2xl bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center shadow-inner">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="text-center p-6 text-slate-300">
                        <i class="fa-solid fa-box-open text-6xl"></i>
                        <p class="text-xs font-medium text-slate-400 mt-2">Tidak ada foto produk</p>
                    </div>
                @endif
            </div>

            <!-- Product Data Specs -->
            <div class="md:col-span-2 space-y-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <!-- Sisa Stok -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                        <p class="text-xs font-semibold text-slate-500 uppercase">Sisa Stok Fisik</p>
                        <p class="text-2xl font-bold mt-1 {{ $product->stock <= 0 ? 'text-rose-600' : ($product->stock <= 10 ? 'text-amber-600' : 'text-emerald-600') }}">
                            {{ number_format($product->stock) }} <span class="text-xs font-normal text-slate-500">Unit</span>
                        </p>
                        @if($product->stock <= 10)
                            <span class="inline-block mt-1 text-[11px] font-semibold text-rose-500"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Perlu Restock</span>
                        @else
                            <span class="inline-block mt-1 text-[11px] font-semibold text-emerald-600"><i class="fa-solid fa-check mr-1"></i> Stok Aman</span>
                        @endif
                    </div>

                    <!-- Harga Satuan -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                        <p class="text-xs font-semibold text-slate-500 uppercase">Harga Satuan</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1">Estimasi nilai unit</p>
                    </div>

                    <!-- Total Estimasi Aset -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 col-span-2 sm:col-span-1">
                        <p class="text-xs font-semibold text-slate-500 uppercase">Nilai Aset Stok</p>
                        <p class="text-2xl font-bold text-brand-600 mt-1">
                            Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1">Total nilai stok berjalan</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Produk</h3>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-sm text-slate-700 leading-relaxed">
                        {{ $product->description ?: 'Belum ada catatan deskripsi atau spesifikasi untuk produk ini.' }}
                    </div>
                </div>

                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <a href="{{ route('stock-in.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-plus"></i>
                        <span>Input Barang Masuk</span>
                    </a>
                    <a href="{{ route('stock-out.create') }}" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-minus"></i>
                        <span>Input Barang Keluar</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Movement History Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Riwayat Barang Masuk Produk Ini -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-arrow-down-to-bracket"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Riwayat Barang Masuk</h3>
                </div>
                <span class="text-xs text-slate-400">10 Catatan Terakhir</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($stockIns as $in)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                        <div>
                            <p class="text-xs font-semibold text-slate-800">{{ $in->notes ?: 'Penerimaan barang' }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                {{ $in->created_at->translatedFormat('d M Y, H:i') }} • Dicatat oleh: {{ $in->user->name ?? 'Sistem' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                +{{ $in->quantity }} Unit
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">Belum ada riwayat barang masuk untuk produk ini.</div>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Barang Keluar Produk Ini -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Riwayat Barang Keluar</h3>
                </div>
                <span class="text-xs text-slate-400">10 Catatan Terakhir</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($stockOuts as $out)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                        <div>
                            <p class="text-xs font-semibold text-slate-800">{{ $out->notes ?: 'Pengeluaran barang' }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                {{ $out->created_at->translatedFormat('d M Y, H:i') }} • Dicatat oleh: {{ $out->user->name ?? 'Sistem' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                -{{ $out->quantity }} Unit
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">Belum ada riwayat barang keluar untuk produk ini.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
