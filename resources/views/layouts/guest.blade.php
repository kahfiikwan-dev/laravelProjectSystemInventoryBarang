<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') - Sistem Inventory Barang</title>
    
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo and System Title -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-400 items-center justify-center shadow-xl shadow-brand-500/30 mb-4">
                <i class="fa-solid fa-boxes-stacked text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Invent<span class="text-brand-400">Pro</span></h1>
            <p class="text-sm text-slate-400 mt-1">Sistem Manajemen Inventory Barang</p>
        </div>

        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 text-sm flex items-center space-x-3">
                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-200 text-sm flex items-center space-x-3">
                <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 p-4 rounded-xl bg-blue-500/20 border border-blue-500/40 text-blue-200 text-sm flex items-center space-x-3">
                <i class="fa-solid fa-circle-info text-blue-400"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border border-slate-100">
            @yield('content')
        </div>

        <!-- Footer Copyright -->
        <p class="text-center text-xs text-slate-500 mt-8">
            &copy; {{ date('Y') }} Sistem Inventory Barang. Hak cipta dilindungi undang-undang.
        </p>
    </div>

</body>
</html>
