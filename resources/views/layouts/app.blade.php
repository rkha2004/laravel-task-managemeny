<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 text-slate-900 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'Task Manager') : config('app.name', 'Task Manager') }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>

    <!-- Vite compiled assets with Tailwind v4 -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback CDN for environments where build assets haven't been compiled yet -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-800">
    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('tasks.index') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-xl shadow-xs group-hover:bg-slate-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold text-slate-900 tracking-tight block leading-none">Task<span class="text-slate-900">Flow</span></span>
                            <span class="text-[11px] text-slate-400 font-medium">Laravel Practical Test</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links & Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('tasks.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('tasks.index') ? 'text-slate-900 bg-slate-100' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Semua Task
                    </a>
                    
                    <a href="{{ route('tasks.create') }}" 
                       class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Task Baru</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages / Toast Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('success'))
            <div id="flash-success" class="flex items-start gap-3 p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm transition-all duration-300">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1 text-sm font-medium">
                    {{ session('success') }}
                </div>
                <button type="button" onclick="document.getElementById('flash-success').remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-error" class="flex items-start gap-3 p-4 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm transition-all duration-300">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1 text-sm font-medium">
                    {{ session('error') }}
                </div>
                <button type="button" onclick="document.getElementById('flash-error').remove()" class="text-rose-500 hover:text-rose-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div id="flash-errors" class="flex items-start gap-3 p-4 mb-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm transition-all duration-300">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="flex-1 text-sm">
                    <p class="font-semibold mb-1">Terdapat kesalahan pada input Anda:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-amber-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" onclick="document.getElementById('flash-errors').remove()" class="text-amber-500 hover:text-amber-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-6 text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) &bull; SQLite Database</span>
            </div>
            <div class="text-xs text-slate-400">
                Practical Test &bull; Junior Laravel Programmer
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
