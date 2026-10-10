<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@inventory.test'],
            [
                'name' => 'Admin Gudang',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@inventory.test'],
            [
                'name' => 'Staff Inventory',
                'password' => bcrypt('password'),
                'role' => 'staff',
            ]
        );

        // Categories
        $catElectronics = \App\Models\Category::firstOrCreate(['name' => 'Elektronik & Gadget']);
        $catStationery = \App\Models\Category::firstOrCreate(['name' => 'Alat Tulis Kantor']);
        $catFurniture = \App\Models\Category::firstOrCreate(['name' => 'Perabotan']);
        $catComputer = \App\Models\Category::firstOrCreate(['name' => 'Aksesoris Komputer']);

        // Products
        $p1 = \App\Models\Product::firstOrCreate(
            ['name' => 'Laptop ThinkPad T14'],
            [
                'category_id' => $catElectronics->id,
                'stock' => 15,
                'price' => 14500000,
                'description' => 'Laptop kantor spek Core i7 RAM 16GB SSD 512GB',
            ]
        );

        $p2 = \App\Models\Product::firstOrCreate(
            ['name' => 'Mouse Wireless Logitech M331'],
            [
                'category_id' => $catComputer->id,
                'stock' => 45,
                'price' => 210000,
                'description' => 'Silent click mouse wireless ergonomis',
            ]
        );

        $p3 = \App\Models\Product::firstOrCreate(
            ['name' => 'Kertas HVS A4 80gr PaperOne'],
            [
                'category_id' => $catStationery->id,
                'stock' => 3, // Low stock sample!
                'price' => 52000,
                'description' => '1 Rim isi 500 lembar warna putih bersih',
            ]
        );

        $p4 = \App\Models\Product::firstOrCreate(
            ['name' => 'Kursi Kerja Ergonomis'],
            [
                'category_id' => $catFurniture->id,
                'stock' => 8, // Low stock sample!
                'price' => 1250000,
                'description' => 'Kursi jaring hidrolik sandaran kepala adjustable',
            ]
        );

        $p5 = \App\Models\Product::firstOrCreate(
            ['name' => 'Keyboard Mechanical Tenkeyless'],
            [
                'category_id' => $catComputer->id,
                'stock' => 20,
                'price' => 450000,
                'description' => 'Mechanical keyboard red switch dengan RGB backlight',
            ]
        );

        // Initial Stock Transactions
        \App\Models\StockIn::create([
            'product_id' => $p1->id,
            'user_id' => $admin->id,
            'quantity' => 15,
            'notes' => 'Pengadaan awal laptop kantor divisi IT',
        ]);

        \App\Models\StockIn::create([
            'product_id' => $p2->id,
            'user_id' => $staff->id,
            'quantity' => 50,
            'notes' => 'Restock dari distributor Logitech',
        ]);

        \App\Models\StockOut::create([
            'product_id' => $p2->id,
            'user_id' => $staff->id,
            'quantity' => 5,
            'notes' => 'Distribusi mouse untuk karyawan baru',
        ]);
    }
}
