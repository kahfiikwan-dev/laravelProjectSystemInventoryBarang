<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sistem Inventory Barang</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex min-h-screen">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- Sidebar -->
    <aside id="main-sidebar" class="fixed top-0 bottom-0 left-0 z-50 w-72 bg-slate-900 text-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-2xl">
        
        <!-- Logo & Brand Header -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-800 bg-slate-950/40">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-400 flex items-center justify-center shadow-lg shadow-brand-500/30">
                    <i class="fa-solid fa-boxes-stacked text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight tracking-wide text-white">Invent<span class="text-brand-400">Pro</span></h1>
                    <p class="text-xs text-slate-400 font-medium tracking-wider uppercase">Sistem Inventory</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- User Role Badge Profile in Sidebar -->
        <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-900/60">
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-full bg-slate-800 border-2 {{ auth()->user()->role === 'admin' ? 'border-indigo-500' : 'border-emerald-500' }} flex items-center justify-center font-bold text-white text-base shadow">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <div class="flex items-center mt-1">
                        @if(auth()->user()->role === 'admin')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                <i class="fa-solid fa-shield-halved text-[10px] mr-1.5"></i> ADMIN
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                <i class="fa-solid fa-user-tag text-[10px] mr-1.5"></i> STAFF
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-5 space-y-1.5 overflow-y-auto">
            <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Menu Utama</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center mr-3 text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->role === 'admin')
                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Kelola Master (Admin)</div>

                <!-- Kelola User -->
                <a href="{{ route('users.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('users.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center mr-3 text-base {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Kelola User</span>
                </a>

                <!-- Kelola Kategori -->
                <a href="{{ route('categories.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('categories.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <i class="fa-solid fa-tags w-5 text-center mr-3 text-base {{ request()->routeIs('categories.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Kelola Kategori</span>
                </a>
            @endif

            <div class="pt-4 px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Inventory & Stok</div>

            <!-- Kelola / Lihat Barang -->
            <a href="{{ route('products.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('products.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <i class="fa-solid fa-box-archive w-5 text-center mr-3 text-base {{ request()->routeIs('products.*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>{{ auth()->user()->role === 'admin' ? 'Kelola Barang' : 'Lihat Barang' }}</span>
            </a>

            <!-- Barang Masuk -->
            <a href="{{ route('stock-in.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('stock-in.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <i class="fa-solid fa-arrow-down-to-bracket w-5 text-center mr-3 text-base {{ request()->routeIs('stock-in.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Barang Masuk</span>
            </a>

            <!-- Barang Keluar -->
            <a href="{{ route('stock-out.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('stock-out.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <i class="fa-solid fa-arrow-up-from-bracket w-5 text-center mr-3 text-base {{ request()->routeIs('stock-out.*') ? 'text-white' : 'text-rose-400' }}"></i>
                <span>Barang Keluar</span>
            </a>
        </nav>

        <!-- Sidebar Footer / Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 text-sm font-medium text-rose-300 hover:text-white bg-rose-500/10 hover:bg-rose-600 rounded-xl transition duration-200">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 lg:ml-72">

        <!-- Top Header Navbar -->
        <header class="h-20 bg-white border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm backdrop-blur">
            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100 transition">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-500 hidden sm:block">Sistem Manajemen Persediaan & Logistik Barang</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-2 bg-slate-100 px-3 py-1.5 rounded-full text-xs font-medium text-slate-600">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <div class="h-8 w-px bg-slate-200 hidden md:block"></div>

                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 capitalize mt-1">{{ auth()->user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm shadow-sm border border-brand-200">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-4 sm:p-8">
            <!-- Flash Message Banners -->
            @if(session('success'))
                <div class="mb-6 flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-check text-sm"></i>
                        </div>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 flex items-center justify-between p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                        </div>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 p-1">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 flex items-center justify-between p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-info text-sm"></i>
                        </div>
                        <p class="text-sm font-medium">{{ session('info') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-800 p-1">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-sm"></i>
                        </div>
                        <p class="text-sm font-semibold">Terdapat beberapa kesalahan pengisian form:</p>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-amber-800 pl-11">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="px-8 py-5 border-t border-slate-200/80 bg-white text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                &copy; {{ date('Y') }} <strong>Sistem Inventory Barang</strong> — Solusi Manajemen Inventaris Modern
            </div>
            <div class="flex items-center space-x-4">
                <span>Versi 1.0</span>
                <span>•</span>
                <span class="text-emerald-600 font-semibold"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Sistem Aktif</span>
            </div>
        </footer>
    </div>

    <!-- Toggle Sidebar Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
