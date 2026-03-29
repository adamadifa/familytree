<div>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800">Anggota Keluarga</h2>
    </x-slot>

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="relative max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau tempat lahir..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition shadow-sm outline-none">
        </div>
        @if(auth()->user()->role === 'admin')
        <button wire:click="$dispatch('openAddMemberModal')" class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-bold text-sm shadow-lg shadow-indigo-200 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Kepala Keluarga
        </button>
        @endif
    </div>

    @if($members->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Pencarian Tidak Ditemukan</h3>
            <p class="text-slate-500 text-sm">Tidak ada anggota keluarga yang cocok dengan kata kunci pencarian Anda.</p>
        </div>
    @else
        <!-- Grid of Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($members as $member)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-100 hover:-translate-y-1 transition-all duration-300 group">
                    <div class="p-6 relative">
                        <!-- Actions Dropdown / Quick Links -->
                        @if(auth()->user()->canManage($member))
                        <div class="absolute top-4 right-4 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="$dispatch('openEditMemberModal', { id: {{ $member->id }} })" class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition shadow-sm" title="Edit Profil">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button wire:click="deleteMember({{ $member->id }})" wire:confirm="Yakin ingin menghapus anggota ini secara permanen dari basis data? (Tindakan ini juga menghapus semua hubungan keluarga terkait)" class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition shadow-sm" title="Hapus Permanen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        @endif

                        <!-- Card Body -->
                        <div class="flex flex-col items-center">
                            <!-- Avatar -->
                            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4 ring-4 ring-white shadow-md overflow-hidden relative">
                                @if($member->photo_path)
                                    <img src="{{ Storage::url($member->photo_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl">{{ $member->gender === 'M' ? '👨🏻‍💼' : '👩🏻‍💼' }}</span>
                                @endif
                                
                                <!-- Gender Dot Indicator -->
                                <div class="absolute bottom-0 right-0 w-5 h-5 rounded-full border-2 border-white flex items-center justify-center {{ $member->gender === 'M' ? 'bg-indigo-500' : 'bg-pink-500' }}" title="{{ $member->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}">
                                    @if($member->gender === 'M')
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0a10.003 10.003 0 0110 10c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0a10.003 10.003 0 0110 10c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09"/></svg>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Information -->
                            <div class="text-center w-full">
                                <h3 class="text-base font-bold text-slate-900 truncate px-2">{{ $member->first_name }} {{ $member->last_name }}</h3>
                                
                                <div class="mt-2 space-y-1.5 flex flex-col items-center">
                                    @if($member->birth_date)
                                    <div class="inline-flex items-center gap-1.5 text-xs text-slate-500 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ \Carbon\Carbon::parse($member->birth_date)->format('d M Y') }}
                                        @if($member->death_date)
                                            <span class="text-slate-300 mx-0.5">-</span>
                                            {{ \Carbon\Carbon::parse($member->death_date)->format('d M Y') }}
                                        @endif
                                    </div>
                                    @endif
                                    
                                    @if($member->birth_place)
                                    <div class="inline-flex items-center gap-1 text-xs text-slate-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $member->birth_place }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div>
            {{ $members->links() }}
        </div>
    @endif

    <!-- Modals -->
    <livewire:add-member-modal />
    <livewire:edit-member-modal />
</div>
