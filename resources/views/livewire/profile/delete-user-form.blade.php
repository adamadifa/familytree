<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-600 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Hapus Akun
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-lg shadow-red-200 text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Hapus Akun Permanen
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-8">
            <h2 class="text-xl font-bold text-slate-900 mb-2">
                Apakah Anda yakin ingin menghapus akun ini?
            </h2>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                Setelah akun Anda dihapus, semua data profil Anda akan sirna selamanya. Silakan masukkan kata sandi Anda untuk mengonfirmasi penghapusan.
            </p>

            <div>
                <label for="password" class="sr-only">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-red-500 transition-colors">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input wire:model="password" id="password" type="password" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition outline-none shadow-sm" placeholder="Masukkan kata sandi untuk konfirmasi...">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold rounded-xl transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-lg shadow-red-200 text-sm">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
