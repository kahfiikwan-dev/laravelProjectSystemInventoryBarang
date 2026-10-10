# 📦 Sistem Manajemen Inventory Barang (InventPro) — Laravel

Aplikasi Web **Sistem Manajemen Inventaris Barang** yang dibangun menggunakan **Laravel 12**, dilengkapi dengan sistem multi-role (**Admin** & **Staff**), autentikasi manual, manajemen kategori, master produk, transaksi barang masuk & keluar yang terintegrasi secara otomatis dengan kalkulasi stok fisik, alert stok kritis, pencarian, filter, grafik interaktif, dan pagination.

---

## 👥 Hak Akses & Fitur Pengguna

### 🛡️ Admin (Akses Penuh)
1. **Login & Logout Manual**: Autentikasi aman berbasis session.
2. **Dashboard Interaktif**:
   - Ringkasan total produk, kategori, total barang masuk, total barang keluar, dan total stok fisik.
   - Peringatan stok kritis / menipis ($\le 10$ unit).
   - Grafik interaktif aktivitas barang masuk vs keluar 7 hari terakhir (Chart.js).
   - Daftar 5 transaksi terkini barang masuk dan keluar.
3. **Kelola User**:
   - Melihat daftar seluruh pengguna.
   - Menambah user baru dengan penentuan role (`admin` atau `staff`).
   - Mengedit data user & mereset password.
   - Menghapus akun user (dilengkapi proteksi agar tidak bisa menghapus akun sendiri yang sedang aktif).
4. **Kelola Kategori**:
   - CRUD lengkap (Create, Read, Update, Delete) kategori produk.
   - Proteksi penghapusan jika kategori masih memiliki produk terdaftar.
5. **Kelola Barang (Master Produk)**:
   - CRUD lengkap produk.
   - Upload foto produk (disimpan di storage publik).
   - Pengaturan harga satuan dan stok awal.
   - Detail produk beserta estimasi nilai aset inventaris.
6. **Barang Masuk**:
   - Mencatat penambahan stok barang dari supplier.
   - Stok barang otomatis bertambah secara real-time.
   - Pembatalan transaksi (rollback stok).
7. **Barang Keluar**:
   - Mencatat pengeluaran stok barang.
   - Validasi ketat: jumlah keluar tidak boleh melebihi sisa stok fisik.
   - Stok barang otomatis berkurang.
   - Pembatalan transaksi (rollback / pengembalian stok).

---

### 👷 Staff (Hak Akses Operasional)
1. **Login & Logout Manual**: Autentikasi berbasis session.
2. **Dashboard**: Memantau pergerakan stok harian dan daftar stok menipis.
3. **Lihat Barang**:
   - Melihat katalog produk, ketersediaan stok, harga, dan foto.
   - Pencarian nama barang dan filter kategori serta status stok.
   - Melihat detail spesifikasi produk.
   - *(Read-only: tidak memiliki akses tambah/edit/hapus master barang).*
4. **Barang Masuk**:
   - Mencatat penerimaan barang masuk ke gudang.
   - Melihat riwayat barang masuk.
5. **Barang Keluar**:
   - Mencatat pengeluaran barang dari gudang.
   - Validasi pencegahan stok minus.
   - Melihat riwayat barang keluar.

---

## 🗄️ Struktur Database & Skema Tabel

```
users
 ├── id (PK)
 ├── name
 ├── email (Unique)
 ├── password (Hashed)
 ├── role ('admin' | 'staff')
 └── created_at, updated_at

categories
 ├── id (PK)
 ├── name (Unique)
 └── created_at, updated_at

products
 ├── id (PK)
 ├── category_id (FK -> categories.id)
 ├── name
 ├── stock (Integer, Default 0)
 ├── price (Decimal 15,2)
 ├── image (String, Nullable)
 ├── description (Text, Nullable)
 └── created_at, updated_at

stock_ins
 ├── id (PK)
 ├── product_id (FK -> products.id)
 ├── user_id (FK -> users.id, Nullable)
 ├── quantity (Integer)
 ├── notes (Text, Nullable)
 └── created_at, updated_at

stock_outs
 ├── id (PK)
 ├── product_id (FK -> products.id)
 ├── user_id (FK -> users.id, Nullable)
 ├── quantity (Integer)
 ├── notes (Text, Nullable)
 └── created_at, updated_at
```

---

## 🚀 Urutan & Tahapan Pengerjaan

### Tahap 1 — Dasar Laravel
- Konfigurasi file routing di [routes/web.php](file:///c:/Users/Nama/Desktop/porto%20folio/project/laravelProjectSystemInventoryBarang/routes/web.php).
- Pembagian Controllers:
  - `AuthController`
  - `DashboardController`
  - `CategoryController`
  - `ProductController`
  - `StockInController`
  - `StockOutController`
  - `UserController`
- Layout Blade modular responsif menggunakan Tailwind CSS CDN, FontAwesome 6, dan Chart.js:
  - [resources/views/layouts/app.blade.php](file:///c:/Users/Nama/Desktop/porto%20folio/project/laravelProjectSystemInventoryBarang/resources/views/layouts/app.blade.php) (Sidebar & Topbar)
  - [resources/views/layouts/guest.blade.php](file:///c:/Users/Nama/Desktop/porto%20folio/project/laravelProjectSystemInventoryBarang/resources/views/layouts/guest.blade.php) (Halaman Auth)

### Tahap 2 — Database
- Migration skema tabel lengkap beserta relasi *Foreign Key* dan *Cascade Delete*.
- Model Eloquent: `User`, `Category`, `Product`, `StockIn`, `StockOut`.
- Database Seeder (`DatabaseSeeder.php`) dengan akun awal, kategori awal, produk sampel, dan transaksi awal.

### Tahap 3 — CRUD
- **Category CRUD**: Form tambah, edit, hapus, validasi keunikan nama, dan pengecekan produk aktif sebelum penghapusan.
- **Product CRUD**: Form tambah, edit, upload gambar, validasi format angka dan stok.

### Tahap 4 — Authentication Manual
- Form login dan registrasi manual menggunakan `Auth::attempt()`, `Hash::make()`.
- Session fixation protection dengan `$request->session()->regenerate()`.
- Logout aman dengan `$request->session()->invalidate()` & `$request->session()->regenerateToken()`.

### Tahap 5 — Role & Hak Akses
- Middleware custom `RoleMiddleware` terdaftar dengan alias `'role'`.
- Pemisahan rute sensitif (Admin-only routes) menggunakan `Route::middleware('role:admin')`.
- Pengondisian tampilan Blade menggunakan `@if(auth()->user()->role === 'admin')` untuk menyembunyikan tombol aksi dan menu khusus.

### Tahap 6 — Relationships
- `Category` hasMany `Product` / `Product` belongsTo `Category`.
- `Product` hasMany `StockIn` / `StockIn` belongsTo `Product`.
- `Product` hasMany `StockOut` / `StockOut` belongsTo `Product`.
- `User` hasMany `StockIn` & `StockOut` untuk tracking pencatat transaksi.
- Transaksi database atomik menggunakan `DB::transaction()` untuk menjamin sinkronisasi antara tabel stok dan jumlah fisik barang.

### Tahap 7 — Fitur Tambahan
- **Pencarian**: Pencarian cepat berdasarkan nama barang, kategori, atau nama pengguna.
- **Filter**: Filter status stok (Stok Tersedia, Stok Kritis $\le 10$, Stok Habis) dan filter kategori.
- **Pagination**: Pagination rapi mempertahankan filter query string (`withQueryString()`).
- **Upload Foto**: Penyimpanan foto produk di disk publik (`storage/app/public/products`) dengan preview langsung sebelum submit.
- **Dashboard**: Statistik ringkasan, grafik 7 hari, dan indikator warna status stok.

---

## 🔑 Akun Demo Bawaan

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@inventory.test` | `password` |
| **Staff** | `staff@inventory.test` | `password` |

*(Pada halaman login, tersedia tombol pintas 1-klik untuk otomatis mengisi akun demo di atas)*.

---

## 💻 Panduan Menjalankan Project

1. **Buka Terminal di Direktori Project**:
   ```bash
   cd "c:\Users\Nama\Desktop\porto folio\project\laravelProjectSystemInventoryBarang"
   ```

2. **Jalankan Migration & Seeder** *(jika ingin mereset data awal)*:
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Pastikan Storage Link Terhubung**:
   ```bash
   php artisan storage:link
   ```

4. **Jalankan Server Development Laravel**:
   ```bash
   php artisan serve
   ```

5. **Buka di Browser**:
   Buka URL: [http://127.0.0.1:8000](http://127.0.0.1:8000)

6. **Menjalankan Pengujian Otomatis (Automated Tests)**:
   ```bash
   php artisan test
   ```

---

## 💡 Rekomendasi Fitur Tambahan untuk Pengembangan Lanjutan

Berikut rekomendasi fitur unggulan yang sangat bernilai tinggi untuk ditambahkan pada versi berikutnya:

1. **Export Laporan (PDF & Excel / CSV)**:
   - Fitur unduh laporan mutasi barang bulanan/tahunan dalam format PDF atau Excel menggunakan library `barryvdh/laravel-dompdf` atau `maatwebsite/excel`.
2. **Sistem Barcode & QR Code**:
   - Pembuatan barcode otomatis untuk setiap produk (misal: Code128 / QR Code) sehingga staff dapat melakukan input barang masuk/keluar hanya dengan memindai scanner kamera HP atau barcode scanner fisik.
3. **Notifikasi Otomatis Stok Kritis (Email / Telegram Bot / WhatsApp)**:
   - Mengirim notifikasi otomatis ke nomor Telegram admin atau grup logistik ketika stok barang mencapai batas minimum pemesanan kembali (*Reorder Point*).
4. **Manajemen Multi-Gudang (Multi-Warehouse)**:
   - Jika perusahaan memiliki lebih dari 1 lokasi fisik gudang, tambahkan tabel `warehouses` dan fitur transfer stok antar gudang.
5. **Activity Log / Audit Trail**:
   - Mencatat histori perubahan data barang (siapa yang mengubah harga atau mengedit nama produk dan kapan waktunya) menggunakan paket `spatie/laravel-activitylog`.
6. **Master Supplier & Customer**:
   - Menghubungkan riwayat barang masuk langsung ke data Master Supplier, dan barang keluar langsung ke data Customer/Departemen penerima.
