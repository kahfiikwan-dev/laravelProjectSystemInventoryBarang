@extends('layouts.guest')

@section('title', 'Masuk Akun')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Masuk ke Sistem</h2>
    <p class="text-xs text-slate-500 mt-1">Silakan masukkan email dan password akun Anda</p>
</div>

<!-- Quick Demo Account Fillers -->
<div class="mb-6 p-3 rounded-xl bg-slate-50 border border-slate-200">
    <p class="text-[11px] font-semibold text-slate-600 uppercase tracking-wider mb-2 flex items-center">
        <i class="fa-solid fa-bolt text-amber-500 mr-1.5"></i> Akun Uji Coba Cepat (Demo):
    </p>
    <div class="grid grid-cols-2 gap-2">
        <button type="button" onclick="fillAccount('admin@inventory.test', 'password')" class="text-xs py-1.5 px-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium rounded-lg border border-indigo-200 transition text-center flex items-center justify-center space-x-1">
            <i class="fa-solid fa-shield-halved text-[10px]"></i>
            <span>Login sbg Admin</span>
        </button>
        <button type="button" onclick="fillAccount('staff@inventory.test', 'password')" class="text-xs py-1.5 px-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium rounded-lg border border-emerald-200 transition text-center flex items-center justify-center space-x-1">
            <i class="fa-solid fa-user-tag text-[10px]"></i>
            <span>Login sbg Staff</span>
        </button>
    </div>
</div>

<form action="{{ route('login') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-regular fa-envelope text-sm"></i>
            </div>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150"
                   placeholder="nama@email.com">
        </div>
        @error('email')
            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-lock text-sm"></i>
            </div>
            <input type="password" name="password" id="password" required
                   class="w-full pl-10 pr-10 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-300 focus:ring-brand-200 focus:border-brand-500' }} text-sm focus:outline-none focus:ring-4 transition duration-150"
                   placeholder="••••••••">
            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                <i class="fa-regular fa-eye text-sm" id="eye-icon"></i>
            </button>
        </div>
        @error('password')
            <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between text-sm">
        <label class="flex items-center text-slate-600 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
            <span class="ml-2 text-xs font-medium">Ingat Saya</span>
        </label>
    </div>

    <button type="submit" class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-brand-500/30 transition duration-150 flex items-center justify-center space-x-2">
        <span>Masuk ke Akun</span>
        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
    </button>
</form>

<div class="mt-6 pt-6 border-t border-slate-100 text-center">
    <p class="text-xs text-slate-500">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:text-brand-700 underline">Daftar sekarang</a>
    </p>
</div>

<script>
    function fillAccount(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }

    function togglePasswordVisibility() {
        const pass = document.getElementById('password');
        const eye = document.getElementById('eye-icon');
        if (pass.type === 'password') {
            pass.type = 'text';
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        } else {
            pass.type = 'password';
            eye.classList.remove('fa-eye-slash');
            eye.classList.add('fa-eye');
        }
    }
</script>
@endsection
