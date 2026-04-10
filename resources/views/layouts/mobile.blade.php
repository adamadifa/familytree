<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SupenaFamily') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <!-- Flatpickr Datepicker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <style>
            body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
            .flatpickr-calendar { border-radius: 12px !important; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important; border: 1px solid #f1f5f9 !important; }
            .flatpickr-day.selected { background: #4f46e5 !important; border-color: #4f46e5 !important; }
            .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
            .pt-safe { padding-top: env(safe-area-inset-top); }
            
            /* Custom Scrollbar for drawers */
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        </style>
    </head>
    <body class="bg-slate-50 antialiased selection:bg-indigo-100 overflow-x-hidden">
        
        <!-- Mobile Native-Style Header -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm pt-safe">
            <div class="h-16 px-5 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.246 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-slate-900 font-extrabold text-base tracking-tight italic">Supena<span class="text-indigo-600">Family</span></span>
                </div>

                <!-- User Menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-50 transition active:scale-95">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center border-2 border-white shadow-sm overflow-hidden">
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-bold text-slate-500">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                    </button>

                    <!-- User Dropdown (Floating Menu) -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.12)] border border-slate-50 py-2.5 z-50">
                        
                        <div class="px-4 py-2 border-b border-slate-50 mb-2">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition" wire:navigate>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>

                        <div class="border-t border-slate-50 mt-2 pt-2">
                             <livewire:layout.logout-button />
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-5 pb-32">
            {{ $slot }}
        </main>

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.addEventListener('sweet-alert', event => {
                const data = event.detail[0] || event.detail;
                Swal.fire({
                    icon: data.icon || 'success',
                    title: data.title || 'Berhasil',
                    text: data.text || '',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl',
                    }
                });
            });
        </script>
    </body>
</html>
