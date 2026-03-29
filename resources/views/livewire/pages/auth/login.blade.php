<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex min-h-screen">
    <!-- Left Panel: Branding -->
    <div class="hidden lg:flex lg:w-[45%] bg-slate-900 relative flex-col justify-between p-12">
        <!-- Top: Logo -->
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.246 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-lg tracking-tight">SupenaFamily</span>
            </div>
        </div>

        <!-- Center: Headline -->
        <div>
            <h1 class="text-4xl font-bold text-white leading-snug tracking-tight mb-4">
                Jaga & Abadikan<br>Warisan Keluarga Anda.
            </h1>
            <p class="text-slate-400 text-base leading-relaxed max-w-sm">
                Platform silsilah keluarga yang memudahkan Anda memetakan, mendokumentasikan, dan berbagi sejarah keluarga lintas generasi.
            </p>

            <div class="mt-10 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-slate-300 text-sm font-medium">Visualisasi pohon keluarga interaktif</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-slate-300 text-sm font-medium">Kolaborasi bersama anggota keluarga</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-slate-300 text-sm font-medium">Data aman dan privat untuk keluarga Anda</span>
                </div>
            </div>
        </div>

        <!-- Bottom: Footer -->
        <div>
            <p class="text-slate-600 text-xs">&copy; {{ date('Y') }} SupenaFamily. Semua hak dilindungi.</p>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="w-full lg:w-[55%] bg-white flex items-center justify-center p-8 lg:p-20">
        <div class="w-full max-w-sm">
            <!-- Mobile Header -->
            <div class="lg:hidden mb-10 text-center">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.246 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-slate-900 font-bold text-lg">SupenaFamily</span>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-1">Masuk ke Akun Anda</h2>
                <p class="text-slate-500 text-sm">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login" class="space-y-5">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input wire:model="form.email" id="email" type="email" name="email" required autofocus
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 text-slate-900 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition outline-none placeholder:text-slate-400"
                        placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition" href="{{ route('password.request') }}" wire:navigate>Lupa sandi?</a>
                        @endif
                    </div>
                    <input wire:model="form.password" id="password" type="password" name="password" required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 text-slate-900 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition outline-none placeholder:text-slate-400"
                        placeholder="Masukkan kata sandi">
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
                </div>

                <!-- Remember -->
                <div class="flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                        class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="ms-2 text-sm text-slate-600">Ingat saya</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors active:scale-[0.98] flex items-center justify-center gap-2 shadow-sm">
                    <span>Masuk</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="mt-6 text-center">
                <span class="text-slate-500 text-sm">Belum punya akun? </span>
                <a href="{{ route('register') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition" wire:navigate>Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
