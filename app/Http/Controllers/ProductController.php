<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar barang dengan filter kategori, pencarian, dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $stockFilter = $request->query('stock_filter');

        $query = Product::with('category');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($stockFilter === 'low') {
            $query->where('stock', '<=', 10)->where('stock', '>', 0);
        } elseif ($stockFilter === 'empty') {
            $query->where('stock', '<=', 0);
        } elseif ($stockFilter === 'available') {
            $query->where('stock', '>', 0);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'stockFilter'));
    }

    /**
     * Tampilkan form tambah barang (Hanya Admin).
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Simpan barang baru ke database (Hanya Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'name.required' => 'Nama barang wajib diisi.',
            'stock.required' => 'Stok awal barang wajib diisi.',
            'stock.min' => 'Stok tidak boleh bernilai negatif.',
            'price.required' => 'Harga barang wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Barang baru berhasil ditambahkan ke inventaris!');
    }

    /**
     * Tampilkan detail barang beserta riwayat keluar/masuk.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'stockIns.user', 'stockOuts.user']);

        $stockIns = $product->stockIns()->latest()->take(10)->get();
        $stockOuts = $product->stockOuts()->latest()->take(10)->get();

        return view('products.show', compact('product', 'stockIns', 'stockOuts'));
    }

    /**
     * Tampilkan form edit barang (Hanya Admin).
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Perbarui data barang (Hanya Admin).
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'name.required' => 'Nama barang wajib diisi.',
            'stock.required' => 'Stok barang wajib diisi.',
            'price.required' => 'Harga barang wajib diisi.',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Hapus barang (Hanya Admin).
     */
    public function destroy(Product $product)
    {
        // Hapus file gambar jika ada
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Barang berhasil dihapus dari inventaris!');
    }
}
