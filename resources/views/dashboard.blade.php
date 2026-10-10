@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Ringkasan Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Greeting Card -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-brand-600 via-indigo-600 to-indigo-800 text-white shadow-xl shadow-brand-500/10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white uppercase tracking-wider">
                    {{ auth()->user()->role === 'admin' ? 'Hak Akses Administrator' : 'Hak Akses Staff Inventory' }}
                </span>
                <span class="text-xs text-indigo-200">• Online</span>
            </div>
            <h1 class="text-2xl font-bold mt-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-indigo-100 text-sm mt-1 max-w-xl">
                Pantau pergerakan arus stok barang, ketersediaan inventaris logistik, serta transaksi barang masuk dan keluar secara real-time.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('stock-in.create') }}" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl shadow transition duration-150 flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Input Barang Masuk</span>
            </a>
            <a href="{{ route('stock-out.create') }}" class="px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl shadow transition duration-150 flex items-center space-x-2">
                <i class="fa-solid fa-minus"></i>
                <span>Input Barang Keluar</span>
            </a>
        </div>
    </div>

    <!-- Low Stock Warning Alert Banner -->
    @if($lowStockCount > 0)
        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-300/60 text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-950">Peringatan: {{ $lowStockCount }} Barang Memiliki Stok Kritis (≤ 10 Unit)!</h4>
                    <p class="text-xs text-amber-800">Segera lakukan pengadaan atau input barang masuk sebelum kehabisan stok inventaris.</p>
                </div>
            </div>
            <a href="{{ route('products.index', ['stock_filter' => 'low']) }}" class="self-start sm:self-center px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg transition whitespace-nowrap shadow-sm">
                Lihat Barang Kritis
            </a>
        </div>
    @endif

    <!-- Metric Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Produk -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Produk</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalProducts) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Dalam {{ $totalCategories }} kategori</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('products.index') }}" class="text-brand-600 hover:text-brand-700 font-semibold flex items-center">
                    <span>Lihat Produk</span>
                    <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Card 2: Total Stok Unit Saat Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Stok Fisik</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalStockCurrent) }} <span class="text-xs font-normal text-slate-500">Unit</span></h3>
                    <p class="text-[11px] text-slate-400 mt-1">Akumulasi sisa gudang</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-warehouse"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Stok Aman</span>
                <span class="inline-flex items-center text-emerald-600 font-semibold">
                    <i class="fa-solid fa-check text-[10px] mr-1"></i> Terpantau
                </span>
            </div>
        </div>

        <!-- Card 3: Total Barang Masuk -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Masuk</p>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1">+{{ number_format($totalStockIn) }} <span class="text-xs font-normal text-slate-500">Unit</span></h3>
                    <p class="text-[11px] text-slate-400 mt-1">Total riwayat penerimaan</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-arrow-down-to-bracket"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('stock-in.index') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold flex items-center">
                    <span>Riwayat Masuk</span>
                    <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Card 4: Total Barang Keluar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Keluar</p>
                    <h3 class="text-2xl font-bold text-rose-600 mt-1">-{{ number_format($totalStockOut) }} <span class="text-xs font-normal text-slate-500">Unit</span></h3>
                    <p class="text-[11px] text-slate-400 mt-1">Total riwayat pengeluaran</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('stock-out.index') }}" class="text-rose-600 hover:text-rose-700 font-semibold flex items-center">
                    <span>Riwayat Keluar</span>
                    <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart Section & Low Stock Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Pergerakan 7 Hari (2 Kolom) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Pergerakan Stok (7 Hari Terakhir)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Komparasi volume unit barang masuk dan keluar per hari</p>
                </div>
                <div class="flex items-center space-x-3 text-xs">
                    <span class="inline-flex items-center font-medium text-emerald-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block mr-1.5"></span> Masuk
                    </span>
                    <span class="inline-flex items-center font-medium text-rose-600">
                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block mr-1.5"></span> Keluar
                    </span>
                </div>
            </div>
            <div class="h-64 sm:h-72">
                <canvas id="stockMovementChart"></canvas>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Stok Kritis (1 Kolom) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800 text-base">Stok Kritis / Menipis</h3>
                    <span class="px-2 py-0.5 text-xs font-semibold bg-rose-100 text-rose-700 rounded-full">
                        {{ $lowStockProducts->count() }} Item
                    </span>
                </div>
                <p class="text-xs text-slate-500 mb-4">Barang dengan jumlah stok tersisa 10 unit atau lebih sedikit.</p>

                <div class="space-y-3">
                    @forelse($lowStockProducts as $low)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition">
                            <div class="min-w-0 pr-2">
                                <p class="text-xs font-semibold text-slate-800 truncate">{{ $low->name }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $low->category->name ?? 'Uncategorized' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $low->stock == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $low->stock }} Unit
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-2 text-xl">
                                <i class="fa-solid fa-check-double"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-700">Semua Stok Aman</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Tidak ada produk yang memiliki stok di bawah 10 unit.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('stock-in.create') }}" class="w-full py-2 px-3 bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-semibold rounded-xl text-center block transition">
                    + Restock Sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 5 Transaksi Barang Masuk Terkini -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-arrow-down-to-bracket"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Barang Masuk Terkini</h3>
                </div>
                <a href="{{ route('stock-in.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                    Lihat Semua
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentStockIns as $in)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                        <div class="min-w-0 pr-3">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $in->product->name ?? 'Barang Terhapus' }}</p>
                            <div class="flex items-center space-x-2 text-[11px] text-slate-400 mt-0.5">
                                <span><i class="fa-regular fa-clock mr-1"></i>{{ $in->created_at->diffForHumans() }}</span>
                                <span>•</span>
                                <span>Oleh: {{ $in->user->name ?? 'Sistem' }}</span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                +{{ $in->quantity }} Unit
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">Belum ada riwayat transaksi barang masuk.</div>
                @endforelse
            </div>
        </div>

        <!-- 5 Transaksi Barang Keluar Terkini -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Barang Keluar Terkini</h3>
                </div>
                <a href="{{ route('stock-out.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                    Lihat Semua
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentStockOuts as $out)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                        <div class="min-w-0 pr-3">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $out->product->name ?? 'Barang Terhapus' }}</p>
                            <div class="flex items-center space-x-2 text-[11px] text-slate-400 mt-0.5">
                                <span><i class="fa-regular fa-clock mr-1"></i>{{ $out->created_at->diffForHumans() }}</span>
                                <span>•</span>
                                <span>Oleh: {{ $out->user->name ?? 'Sistem' }}</span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                -{{ $out->quantity }} Unit
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">Belum ada riwayat transaksi barang keluar.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('stockMovementChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartDates) !!},
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: {!! json_encode($chartStockIn) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.85)',
                        borderColor: '#10b981',
                        borderRadius: 6,
                        borderWidth: 1,
                    },
                    {
                        label: 'Barang Keluar',
                        data: {!! json_encode($chartStockOut) !!},
                        backgroundColor: 'rgba(244, 63, 94, 0.85)',
                        borderColor: '#f43f5e',
                        borderRadius: 6,
                        borderWidth: 1,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
