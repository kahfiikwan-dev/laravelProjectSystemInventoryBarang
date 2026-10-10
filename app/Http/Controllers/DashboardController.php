<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama.
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalStockIn = StockIn::sum('quantity') ?? 0;
        $totalStockOut = StockOut::sum('quantity') ?? 0;
        $totalStockCurrent = Product::sum('stock') ?? 0;
        $totalUsers = User::count();

        // Produk dengan stok menipis (<= 10 unit)
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        $lowStockCount = Product::where('stock', '<=', 10)->count();

        // 5 Transaksi Barang Masuk Terkini
        $recentStockIns = StockIn::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 5 Transaksi Barang Keluar Terkini
        $recentStockOuts = StockOut::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Data Grafik 7 Hari Terakhir
        $chartDates = [];
        $chartStockIn = [];
        $chartStockOut = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->format('d M');
            $chartDates[] = $label;

            $inQty = StockIn::whereDate('created_at', $date)->sum('quantity');
            $outQty = StockOut::whereDate('created_at', $date)->sum('quantity');

            $chartStockIn[] = (int) $inQty;
            $chartStockOut[] = (int) $outQty;
        }

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalStockIn',
            'totalStockOut',
            'totalStockCurrent',
            'totalUsers',
            'lowStockProducts',
            'lowStockCount',
            'recentStockIns',
            'recentStockOuts',
            'chartDates',
            'chartStockIn',
            'chartStockOut'
        ));
    }
}
