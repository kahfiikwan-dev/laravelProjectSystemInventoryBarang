@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Formulir Pengguna Baru</h2>
            <p class="text-xs text-slate-500">Daftarkan akun Admin atau Staff baru ke dalam sistem</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition flex items-center space-x-1.5 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       placeholder="Contoh: Budi Santoso"
                       class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       placeholder="budi@inventory.test"
                       class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150">
                @error('email')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Role / Hak Akses <span class="text-rose-500">*</span></label>
                <select name="role" id="role" required
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('role') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150 bg-white">
                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff Gudang (Lihat Barang & Keluar/Masuk)</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh Seluruh Sistem)</option>
                </select>
                @error('role')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi (Min. 6 Karakter) <span class="text-rose-500">*</span></label>
                <input type="password" name="password" id="password" required
                       placeholder="••••••••"
                       class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150">
                @error('password')
                    <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs rounded-xl shadow-lg shadow-brand-500/20 transition flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Pengguna</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
