<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>adamadifa_ — Abadikan Kenangan Keluarga</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --gold: #d4a853;
                --gold-light: #e8c97a;
            }
            body { font-family: 'Inter', sans-serif; }
            .font-serif { font-family: 'Playfair Display', serif; }

            .hero-overlay {
                background: linear-gradient(
                    135deg,
                    rgba(15, 23, 42, 0.85) 0%,
                    rgba(15, 23, 42, 0.6) 40%,
                    rgba(15, 23, 42, 0.75) 100%
                );
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.15);
            }
            .text-gold { color: var(--gold); }
            .bg-gold { background-color: var(--gold); }
            .border-gold { border-color: var(--gold); }

            /* Animations */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes shimmer {
                0% { background-position: -200% center; }
                100% { background-position: 200% center; }
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.8s ease-out forwards;
            }
            .animate-fade-in {
                animation: fadeIn 1s ease-out forwards;
            }
            .animate-float {
                animation: float 4s ease-in-out infinite;
            }
            .delay-100 { animation-delay: 0.1s; }
            .delay-200 { animation-delay: 0.2s; }
            .delay-300 { animation-delay: 0.3s; }
            .delay-500 { animation-delay: 0.5s; }
            .delay-700 { animation-delay: 0.7s; }
            .delay-900 { animation-delay: 0.9s; }

            .shimmer-text {
                background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 40%, #fff 50%, var(--gold-light) 60%, var(--gold) 100%);
                background-size: 200% auto;
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: shimmer 4s linear infinite;
            }

            .line-decoration::before,
            .line-decoration::after {
                content: '';
                display: inline-block;
                width: 40px;
                height: 1px;
                background: var(--gold);
                vertical-align: middle;
                margin: 0 12px;
                opacity: 0.6;
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--gold) 0%, #c4943f 100%);
                color: #1e293b;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            .btn-primary::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 50%);
                opacity: 0;
                transition: opacity 0.3s;
            }
            .btn-primary:hover::after { opacity: 1; }
            .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(212, 168, 83, 0.35); }

            .btn-outline {
                border: 1.5px solid rgba(212, 168, 83, 0.5);
                color: var(--gold-light);
                transition: all 0.3s ease;
            }
            .btn-outline:hover {
                background: rgba(212, 168, 83, 0.1);
                border-color: var(--gold);
                transform: translateY(-2px);
            }

            .feature-icon {
                width: 36px; height: 36px;
                border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                background: rgba(212, 168, 83, 0.1);
                border: 1px solid rgba(212, 168, 83, 0.2);
            }
        </style>
    </head>
    <body class="antialiased overflow-hidden">
        <div class="relative min-h-screen w-full flex items-center justify-center bg-slate-950">

            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/hero-bg.jpeg') }}" alt="Family Heritage" class="w-full h-full object-cover object-center">
                <div class="absolute inset-0 hero-overlay"></div>
            </div>

            <!-- Decorative pattern overlay -->
            <div class="absolute inset-0 z-[1] opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <!-- Top Nav Bar -->
            <div class="absolute top-0 left-0 right-0 z-20 opacity-0 animate-fade-in delay-100" style="animation-fill-mode: both;">
                <div class="flex items-center justify-between px-6 md:px-12 py-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[var(--gold)] to-[#b8862f] flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.246 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-sm tracking-wide">SUPENAFAMILY</span>
                    </div>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gold hover:text-gold-light transition">Dashboard →</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-white/70 hover:text-white transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                Masuk
                            </a>
                        @endauth
                    @endif
                </div>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 text-center px-6 max-w-3xl mx-auto">



                <!-- Main Title -->
                <h1 class="opacity-0 animate-fade-in-up delay-200" style="animation-fill-mode: both;">
                    <span class="block font-serif text-5xl md:text-7xl lg:text-8xl font-bold text-white leading-[0.95] tracking-tight mb-3">
                        Supena
                    </span>
                    <span class="block shimmer-text font-serif text-5xl md:text-7xl lg:text-8xl font-bold leading-[0.95] tracking-tight">
                        Family
                    </span>
                </h1>

                <!-- Decorative Divider -->
                <div class="opacity-0 animate-fade-in-up delay-300 my-6 md:my-8 flex items-center justify-center gap-3" style="animation-fill-mode: both;">
                    <div class="w-12 h-[1px] bg-gradient-to-r from-transparent to-[var(--gold)]"></div>
                    <div class="w-2 h-2 rounded-full bg-gold opacity-60 animate-float"></div>
                    <div class="w-12 h-[1px] bg-gradient-to-l from-transparent to-[var(--gold)]"></div>
                </div>

                <!-- Tagline -->
                <div class="opacity-0 animate-fade-in-up delay-500" style="animation-fill-mode: both;">
                    <p class="font-serif text-xl md:text-2xl lg:text-3xl text-white/90 italic font-medium tracking-wide mb-3">
                        "Abadikan Kenangan, Hubungkan Generasi"
                    </p>
                    <p class="text-sm md:text-base text-slate-400 font-light max-w-lg mx-auto leading-relaxed">
                        Jelajahi dan dokumentasikan silsilah keluarga besar Supena — dari akar hingga pucuk, lintas generasi dan waktu.
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="opacity-0 animate-fade-in-up delay-700 mt-10 flex flex-col sm:flex-row items-center justify-center gap-4" style="animation-fill-mode: both;">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary px-10 py-4 font-bold rounded-xl text-sm tracking-wide active:scale-95 flex items-center gap-2.5">
                                <span>Masuk ke Dashboard</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary px-10 py-4 font-bold rounded-xl text-sm tracking-wide active:scale-95 flex items-center gap-2.5">
                                <span>Masuk Sekarang</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-outline px-10 py-4 font-semibold rounded-xl text-sm tracking-wide active:scale-95">
                                    Daftar Anggota
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Feature highlights -->
                <div class="opacity-0 animate-fade-in-up delay-900 mt-14 grid grid-cols-3 gap-4 max-w-md mx-auto" style="animation-fill-mode: both;">
                    <div class="flex flex-col items-center gap-2">
                        <div class="feature-icon">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">Pohon Interaktif</span>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="feature-icon">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">Multi Generasi</span>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="feature-icon">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">Aman & Privat</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="absolute bottom-0 left-0 right-0 z-10">
                <div class="h-24 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                <div class="bg-slate-950/60 backdrop-blur-sm py-4 px-6 flex items-center justify-center">
                    <p class="text-white/30 text-[11px] uppercase tracking-[0.2em] font-medium">
                        &copy; {{ date('Y') }} adamadifa_ &middot; Menjaga Silsilah, Merangkai Kenangan
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
