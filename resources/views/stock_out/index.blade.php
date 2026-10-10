@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Riwayat Barang Keluar')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <form action="{{ route('stock-out.index') }}" method="GET" class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang atau keterangan..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-4 focus:ring-brand-100 focus:border-brand-500 text-sm transition">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </div>
                @if($search)
                    <a href="{{ route('stock-out.index') }}" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>

        <a href="{{ route('stock-out.create') }}" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-rose-500/20 transition flex items-center justify-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Catat Barang Keluar</span>
        </a>
    </div>

    <!-- Stock Out Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-xs uppercase font-bold text-slate-600">
                    <tr>
                        <th class="py-4 px-6 w-14">No</th>
                        <th class="py-4 px-6">Waktu & Tanggal</th>
                        <th class="py-4 px-6">Nama Barang</th>
                        <th class="py-4 px-6">Jumlah Keluar</th>
                        <th class="py-4 px-6">Pencatat</th>
                        <th class="py-4 px-6">Tujuan / Keterangan</th>
                        <th class="py-4 px-6 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($stockOuts as $index => $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-medium text-slate-400">
                                {{ $stockOuts->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-slate-800">{{ $item->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">
                                    {{ $item->product->name ?? 'Barang Dihapus' }}
                                </div>
                                <div class="text-xs text-indigo-600">
                                    {{ $item->product->category->name ?? '-' }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                    -{{ number_format($item->quantity) }} Unit
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center text-xs font-medium text-slate-700">
                                    <i class="fa-regular fa-user mr-1.5 text-slate-400 text-[11px]"></i>
                                    {{ $item->user->name ?? 'Sistem' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500 max-w-xs">
                                {{ $item->notes ?: '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('stock-out.destroy', $item) }}" method="POST" onsubmit="return confirm('Batalkan transaksi pengeluaran ini? Stok barang akan dikembalikan (+{{ $item->quantity }} unit).')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Batalkan Transaksi (Kembalikan Stok)">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-2xl text-slate-400">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                </div>
                                <p class="text-sm font-medium">Belum ada data barang keluar.</p>
                                @if($search)
                                    <a href="{{ route('stock-out.index') }}" class="text-xs text-brand-600 underline mt-1 inline-block">Reset filter</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stockOuts->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $stockOuts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
