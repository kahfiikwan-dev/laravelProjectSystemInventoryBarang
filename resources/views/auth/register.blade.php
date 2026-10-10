@extends('layouts.guest')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Daftar Akun Baru</h2>
    <p class="text-xs text-slate-500 mt-1">Lengkapi formulir di bawah ini untuk membuat akun pengguna</p>
</div>

<form action="{{ route('register') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-regular fa-user text-sm"></i>
            </div>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150"
                   placeholder="John Doe">
        </div>
        @error('name')
            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-regular fa-envelope text-sm"></i>
            </div>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150"
                   placeholder="nama@email.com">
        </div>
        @error('email')
            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Role Pengguna</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-id-badge text-sm"></i>
            </div>
            <select name="role" id="role" required
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('role') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150 bg-white">
                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff (Lihat Barang & Catat Keluar/Masuk)</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Akses Penuh Seluruh Sistem)</option>
            </select>
        </div>
        @error('role')
            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi (Min 6 Karakter)</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-lock text-sm"></i>
            </div>
            <input type="password" name="password" id="password" required
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150"
                   placeholder="••••••••">
        </div>
        @error('password')
            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-lock-open text-sm"></i>
            </div>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-brand-200 focus:border-brand-500 text-sm focus:outline-none focus:ring-4 transition duration-150"
                   placeholder="••••••••">
        </div>
    </div>

    <button type="submit" class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-brand-500/30 transition duration-150 flex items-center justify-center space-x-2">
        <span>Daftar Akun</span>
        <i class="fa-solid fa-user-plus text-xs"></i>
    </button>
</form>

<div class="mt-6 pt-6 border-t border-slate-100 text-center">
    <p class="text-xs text-slate-500">
        Sudah memiliki akun? 
        <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700 underline">Masuk di sini</a>
    </p>
</div>
@endsection
