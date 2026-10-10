<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    /**
     * Tampilkan riwayat barang masuk.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $stockIns = StockIn::with(['product.category', 'user'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock_in.index', compact('stockIns', 'search'));
    }

    /**
     * Tampilkan form input barang masuk.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('stock_in.create', compact('products'));
    }

    /**
     * Simpan transaksi barang masuk dan update stok produk (Atomic Transaction).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'product_id.required' => 'Pilih barang yang masuk.',
            'product_id.exists' => 'Barang tidak ditemukan.',
            'quantity.required' => 'Jumlah barang masuk wajib diisi.',
            'quantity.min' => 'Jumlah barang minimal 1.',
        ]);

        DB::transaction(function () use ($validated) {
            StockIn::create([
                'product_id' => $validated['product_id'],
                'user_id' => auth()->id(),
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'],
            ]);

            // Tambahkan stok pada produk
            Product::where('id', $validated['product_id'])
                ->increment('stock', $validated['quantity']);
        });

        return redirect()->route('stock-in.index')
            ->with('success', 'Transaksi barang masuk berhasil dicatat dan stok produk telah bertambah!');
    }

    /**
     * Batalkan / Hapus catatan barang masuk (Rollback stok).
     */
    public function destroy(StockIn $stockIn)
    {
        $product = $stockIn->product;

        // Validasi apakah stok mencukupi jika ditarik kembali
        if ($product && $product->stock < $stockIn->quantity) {
            return back()->with('error', 'Tidak dapat membatalkan transaksi ini karena sisa stok barang saat ini lebih kecil dari jumlah yang akan ditarik!');
        }

        DB::transaction(function () use ($stockIn, $product) {
            if ($product) {
                $product->decrement('stock', $stockIn->quantity);
            }
            $stockIn->delete();
        });

        return redirect()->route('stock-in.index')
            ->with('success', 'Catatan barang masuk berhasil dibatalkan dan stok telah disesuaikan kembali.');
    }
}
