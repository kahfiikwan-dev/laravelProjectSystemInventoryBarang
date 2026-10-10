<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    /**
     * Tampilkan riwayat barang keluar.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $stockOuts = StockOut::with(['product.category', 'user'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock_out.index', compact('stockOuts', 'search'));
    }

    /**
     * Tampilkan form input barang keluar.
     */
    public function create()
    {
        // Hanya tampilkan produk yang stoknya > 0
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('stock_out.create', compact('products'));
    }

    /**
     * Simpan transaksi barang keluar dan kurangi stok produk (Atomic Transaction).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'product_id.required' => 'Pilih barang yang keluar.',
            'product_id.exists' => 'Barang tidak ditemukan.',
            'quantity.required' => 'Jumlah barang keluar wajib diisi.',
            'quantity.min' => 'Jumlah barang minimal 1.',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Validasi ketersediaan stok
        if ($product->stock < $validated['quantity']) {
            return back()->withInput()->withErrors([
                'quantity' => 'Stok tidak mencukupi! Stok saat ini untuk ' . $product->name . ' hanya tersedia ' . $product->stock . ' unit.',
            ]);
        }

        DB::transaction(function () use ($validated, $product) {
            StockOut::create([
                'product_id' => $validated['product_id'],
                'user_id' => auth()->id(),
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'],
            ]);

            // Kurangi stok produk
            $product->decrement('stock', $validated['quantity']);
        });

        return redirect()->route('stock-out.index')
            ->with('success', 'Transaksi barang keluar berhasil dicatat dan stok produk telah berkurang!');
    }

    /**
     * Batalkan / Hapus catatan barang keluar (Rollback stok).
     */
    public function destroy(StockOut $stockOut)
    {
        $product = $stockOut->product;

        DB::transaction(function () use ($stockOut, $product) {
            if ($product) {
                // Kembalikan stok ke produk
                $product->increment('stock', $stockOut->quantity);
            }
            $stockOut->delete();
        });

        return redirect()->route('stock-out.index')
            ->with('success', 'Catatan barang keluar berhasil dibatalkan dan stok produk telah dikembalikan.');
    }
}
