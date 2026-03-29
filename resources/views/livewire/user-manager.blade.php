<div>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800">Daftar Pengguna Aplikasi</h2>
    </x-slot>

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="relative max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email pengguna..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition shadow-sm outline-none">
        </div>
        
        <button wire:click="openModal" class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-bold text-sm shadow-lg shadow-indigo-200 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Pengguna
        </button>
    </div>

    <!-- Error Display -->
    @error('delete')
    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        {{ $message }}
    </div>
    @enderror

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 bg-slate-50/80 border-b border-slate-200 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold">Pengguna</th>
                        <th scope="col" class="px-6 py-4 font-bold">Hak Akses</th>
                        <th scope="col" class="px-6 py-4 font-bold">Terhubung Ke Anggota</th>
                        <th scope="col" class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                        <div class="text-slate-500 text-xs">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        Administrator
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Anggota Keluarga
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->familyMember)
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium border border-indigo-100">
                                        @if($user->familyMember->photo_path)
                                            <img src="{{ Storage::url($user->familyMember->photo_path) }}" class="w-5 h-5 rounded-full object-cover">
                                        @else
                                            <div class="w-5 h-5 rounded-full bg-indigo-200 flex items-center justify-center text-[10px]">{{ $user->familyMember->gender === 'M' ? '👨🏻' : '👩🏻' }}</div>
                                        @endif
                                        {{ $user->familyMember->first_name }} {{ $user->familyMember->last_name }}
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs italic">Tidak Terhubung</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editUser({{ $user->id }})" class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-600 hover:text-white rounded-lg transition" title="Edit Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Yakin ingin menghapus akses akun pengguna ini? Data silsilahnya tidak akan terpengaruh, hanya akun loginnya saja yang dihapus." class="p-2 text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-lg transition" title="Hapus Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                Tidak ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900" id="modal-title">
                            {{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                        </h3>
                        <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 p-2 rounded-xl transition">
                            <span class="sr-only">Tutup</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveUser">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input wire:model="name" type="text" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm" required>
                                @error('name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat Email <span class="text-red-500">*</span></label>
                                <input wire:model="email" type="email" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm" required>
                                @error('email') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Hak Akses (Role) <span class="text-red-500">*</span></label>
                                <select wire:model="role" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm" required>
                                    <option value="member">Anggota Keluarga (Terbatas)</option>
                                    <option value="admin">Administrator (Akses Penuh)</option>
                                </select>
                                @error('role') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                                    Hubungkan dengan Data Anggota (Opsional)
                                </label>
                                <p class="text-[11px] text-slate-500 mb-2 leading-relaxed">
                                    Pilih anggota keluarga agar akun ini hanya bisa mengubah pohon keturunan di bawah anggota tersebut.
                                </p>
                                <select wire:model="family_member_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm">
                                    <option value="">-- Biarkan Kosong --</option>
                                    @foreach($familyMembers as $member)
                                        <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }} ({{ $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->format('Y') : '?' }})</option>
                                    @endforeach
                                </select>
                                @error('family_member_id') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-2">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ $userId ? 'Ubah Kata Sandi (Opsional)' : 'Kata Sandi' }} {!! $userId ? '' : '<span class="text-red-500">*</span>' !!}</label>
                                <input wire:model="password" type="password" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm" {{ $userId ? '' : 'required' }} placeholder="{{ $userId ? 'Biarkan kosong jika tidak ingin mengubah' : 'Minimal 8 karakter' }}">
                                @error('password') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3 sm:flex-row-reverse border-t border-slate-100 pt-6">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition shadow-lg shadow-indigo-200 text-sm">
                                Simpan Pengguna
                            </button>
                            <button type="button" wire:click="closeModal" class="w-full sm:w-auto mt-3 sm:mt-0 inline-flex justify-center items-center px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold rounded-xl transition text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
