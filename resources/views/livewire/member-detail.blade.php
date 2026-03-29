<div class="bg-white rounded-xl border border-slate-200 p-5 overflow-y-auto" style="max-height: 78vh;">
    @if($member)
        <div class="flex flex-col items-center text-center">
            <!-- Avatar -->
            <div class="w-20 h-20 rounded-full border-2 {{ $member->gender === 'M' ? 'border-indigo-200' : 'border-pink-200' }} overflow-hidden bg-slate-100 flex items-center justify-center mb-4">
                @if($member->photo_path)
                    <img src="{{ Storage::url($member->photo_path) }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-10 h-10 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                @endif
            </div>

            <h3 class="text-base font-bold text-slate-900 mb-0.5">{{ $member->first_name }} {{ $member->last_name }}</h3>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider {{ $member->gender === 'M' ? 'bg-indigo-50 text-indigo-600' : 'bg-pink-50 text-pink-600' }} mb-5">
                {{ $member->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}
            </span>

            <div class="w-full space-y-3 text-left">
                <!-- Birth Info -->
                <div class="p-3 bg-slate-50 rounded-lg">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Riwayat Hidup</p>
                    <div class="flex items-center gap-2 mb-0.5">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm text-slate-700">{{ $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->translatedFormat('d F Y') : '-' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-xs text-slate-500">{{ $member->birth_place ?? 'Tempat tidak diketahui' }}</p>
                    </div>
                </div>

                @if($member->bio)
                <div class="p-3 bg-slate-50 rounded-lg">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan</p>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $member->bio }}</p>
                </div>
                @endif

                <!-- Relatives -->
                <div class="pt-3 space-y-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keluarga Dekat</p>

                    @foreach($member->spouses as $spouse)
                        <div class="w-full flex items-center justify-between p-2.5 bg-white hover:bg-slate-50 rounded-lg border border-slate-100 transition group">
                            <button wire:click="loadMember({{ $spouse->id }})" class="flex items-center gap-2.5 flex-1 text-left">
                                <div class="w-2 h-2 rounded-full {{ $spouse->is_divorced ? 'bg-red-400' : 'bg-pink-400' }}"></div>
                                <p class="text-sm text-slate-700 group-hover:text-slate-900 transition font-medium">{{ $spouse->first_name }}</p>
                            </button>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] font-semibold {{ $spouse->is_divorced ? 'text-red-500 bg-red-50' : 'text-pink-500 bg-pink-50' }} px-2 py-0.5 rounded">
                                    {{ $spouse->is_divorced ? 'Status: Cerai' : 'Pasangan' }}
                                </span>
                                <!-- Toggle Divorce Button -->
                                @if($canEdit)
                                <button wire:click="toggleDivorce({{ $spouse->id }})" title="{{ $spouse->is_divorced ? 'Tandai sebagai Rujuk' : 'Tandai sebagai Cerai' }}" class="px-1.5 py-0.5 rounded transition hover:bg-slate-100 flex items-center justify-center">
                                    <span class="text-[14px]" style="color: {{ $spouse->is_divorced ? '#ef4444' : '#ec4899' }}">{{ $spouse->is_divorced ? '💔' : '❤' }}</span>
                                </button>
                                @else
                                <div class="px-1.5 py-0.5 flex items-center justify-center" title="{{ $spouse->is_divorced ? 'Bercerai' : 'Menikah' }}">
                                    <span class="text-[14px]" style="color: {{ $spouse->is_divorced ? '#ef4444' : '#ec4899' }}">{{ $spouse->is_divorced ? '💔' : '❤' }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @foreach($member->parents as $parent)
                        <button wire:click="loadMember({{ $parent->id }})" class="w-full flex items-center justify-between p-2.5 bg-white hover:bg-slate-50 rounded-lg border border-slate-100 transition group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                                <p class="text-sm text-slate-700 group-hover:text-slate-900 transition font-medium">{{ $parent->first_name }}</p>
                            </div>
                            <span class="text-[10px] font-semibold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded">Orang Tua</span>
                        </button>
                    @endforeach

                    @foreach($member->children as $child)
                        <button wire:click="loadMember({{ $child->id }})" class="w-full flex items-center justify-between p-2.5 bg-white hover:bg-slate-50 rounded-lg border border-slate-100 transition group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                <p class="text-sm text-slate-700 group-hover:text-slate-900 transition font-medium">{{ $child->first_name }}</p>
                            </div>
                            <span class="text-[10px] font-semibold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded">Anak Kandung</span>
                        </button>
                    @endforeach

                    @foreach($member->step_children as $stepChild)
                        <button wire:click="loadMember({{ $stepChild->id }})" class="w-full flex items-center justify-between p-2.5 bg-white hover:bg-slate-50 rounded-lg border border-slate-100 transition group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                                <p class="text-sm text-slate-700 group-hover:text-slate-900 transition font-medium">{{ $stepChild->first_name }}</p>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">Anak Tiri / Bawaan</span>
                        </button>
                    @endforeach

                    @foreach($member->step_parents as $stepParent)
                        <button wire:click="loadMember({{ $stepParent->id }})" class="w-full flex items-center justify-between p-2.5 bg-white hover:bg-slate-50 rounded-lg border border-slate-100 transition group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                                <p class="text-sm text-slate-700 group-hover:text-slate-900 transition font-medium">{{ $stepParent->first_name }}</p>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $stepParent->gender === 'M' ? 'Ayah Tiri' : 'Ibu Tiri' }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            @if($canEdit)
            <div class="mt-6 w-full space-y-2">
                <button wire:click="$dispatch('openEditMemberModal', { id: {{ $member->id }} })" class="w-full py-2.5 bg-white border border-indigo-200 text-indigo-600 text-sm font-semibold rounded-lg hover:bg-indigo-50 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Data
                </button>

                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="$dispatch('openAddMemberModal', { relativeId: {{ $member->id }}, relationshipType: 'parent' })" class="py-2 text-xs font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition">
                        + Orang Tua
                    </button>
                    <button wire:click="$dispatch('openAddMemberModal', { relativeId: {{ $member->id }}, relationshipType: 'child' })" class="py-2 text-xs font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition">
                        + Anak
                    </button>
                    <button wire:click="$dispatch('openAddMemberModal', { relativeId: {{ $member->id }}, relationshipType: 'spouse' })" class="col-span-2 py-2 text-xs font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition">
                        + Pasangan
                    </button>
                </div>

                <hr class="border-slate-100 my-2">

                <button wire:click="deleteMember" wire:confirm="Apakah Anda yakin ingin menghapus data anggota ini beserta seluruh relasi silsilahnya? Tindakan ini tidak dapat dibatalkan." class="w-full py-2.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Anggota
                </button>
            </div>
            @endif
        </div>
    @else
        <div class="h-64 flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h4 class="text-sm font-bold text-slate-700 mb-1">Pilih Anggota</h4>
            <p class="text-xs text-slate-400 leading-relaxed px-4">Klik kotak anggota di pohon silsilah untuk melihat informasi detail.</p>
        </div>
    @endif
</div>
