<div>
    @if($isOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeModal"></div>

        <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-indigo-100">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">{{ $canEdit ? 'Edit Profil Anggota' : 'Profil Anggota' }}</h3>
                        <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider">Perbarui data biodata</p>
                    </div>
                </div>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition p-2 hover:bg-slate-50 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                <form wire:submit.prevent="save" class="space-y-6">
                    
                    <!-- Photo Section - Top -->
                    <div x-data="{ photoPreview: null }" class="flex flex-col items-center justify-center space-y-3 mb-2">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-slate-100 flex items-center justify-center">
                                <!-- Alpine.js Local Preview -->
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>

                                <!-- Fallback to Existing Photo (Server Side) -->
                                <div x-show="!photoPreview" class="w-full h-full">
                                    @if ($existingPhoto)
                                        <img src="{{ Storage::url($existingPhoto) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-100">
                                            <svg class="w-12 h-12 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($canEdit)
                                <div wire:loading wire:target="photo" class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                    <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            
                            @if($canEdit)
                            <label class="absolute bottom-0 right-0 bg-indigo-600 text-white p-1.5 rounded-full shadow-lg cursor-pointer hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <input type="file" 
                                       wire:model="photo" 
                                       class="hidden" 
                                       accept="image/*"
                                       @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                            </label>
                            @endif
                        </div>
                        @if($canEdit)
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Perbarui Foto</span>
                        @endif
                    </div>

                    <!-- Retroactive Parent Selection -->
                    @if($canEdit && count($existingParents) === 1 && count($spousesOfPrimaryParent) > 0)
                    <div class="space-y-1.5 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100 mb-6">
                        <label class="block text-sm font-bold text-indigo-900">Tambahkan Orang Tua Ke-2</label>
                        <p class="text-[11px] text-indigo-600 mb-2">Anggota ini saat ini hanya memiliki 1 orang tua tercatat. Pilih pasangannya untuk melengkapi data orang tua.</p>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <select wire:model="otherParentId" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm bg-white cursor-pointer">
                                <option value="">Tunda (Biarkan menjadi orang tua tunggal)</option>
                                @foreach($spousesOfPrimaryParent as $spouse)
                                    <option value="{{ $spouse['id'] }}">{{ $spouse['first_name'] }} {{ $spouse['last_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-slate-700">Nama Depan <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input wire:model="first_name" type="text" required {!! !$canEdit ? 'disabled' : '' !!} class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm disabled:bg-slate-50 disabled:text-slate-500">
                            </div>
                            @error('first_name') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-slate-700">Nama Belakang</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input wire:model="last_name" type="text" {!! !$canEdit ? 'disabled' : '' !!} class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm disabled:bg-slate-50 disabled:text-slate-500">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @if($canEdit) wire:click="$set('gender', 'M')" @endif class="flex items-center gap-3 p-3 rounded-xl border-2 transition text-sm font-bold {{ $gender === 'M' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 shadow-sm' : 'bg-white border-slate-100 text-slate-500 hover:border-slate-200 hover:bg-slate-50' }} {{ !$canEdit ? 'opacity-70 cursor-default' : '' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $gender === 'M' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-slate-100 text-slate-400' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0a10.003 10.003 0 0110 10c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09"/></svg>
                                </div>
                                Laki-laki
                            </button>
                            <button type="button" @if($canEdit) wire:click="$set('gender', 'F')" @endif class="flex items-center gap-3 p-3 rounded-xl border-2 transition text-sm font-bold {{ $gender === 'F' ? 'bg-pink-50 border-pink-500 text-pink-700 shadow-sm' : 'bg-white border-slate-100 text-slate-500 hover:border-slate-200 hover:bg-slate-50' }} {{ !$canEdit ? 'opacity-70 cursor-default' : '' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $gender === 'F' ? 'bg-pink-600 text-white shadow-md shadow-pink-200' : 'bg-slate-100 text-slate-400' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0a10.003 10.003 0 0110 10c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09"/></svg>
                                </div>
                                Perempuan
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-slate-700">Tempat Lahir</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <input wire:model="birth_place" type="text" {!! !$canEdit ? 'disabled' : '' !!} placeholder="Bandung" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm disabled:bg-slate-50 disabled:text-slate-500">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-slate-700">Tanggal Lahir</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div x-data x-init="flatpickr($refs.birthpicker, { dateFormat: 'Y-m-d', allowInput: true })">
                                    <input x-ref="birthpicker" type="text" wire:model="birth_date" {!! !$canEdit ? 'disabled' : '' !!} placeholder="YYYY-MM-DD" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none {{ $canEdit ? 'cursor-pointer' : '' }} bg-white shadow-sm disabled:bg-slate-50 disabled:text-slate-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700">Tanggal Wafat (Opsional)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div x-data x-init="flatpickr($refs.deathpicker, { dateFormat: 'Y-m-d', allowInput: true })">
                                <input x-ref="deathpicker" type="text" wire:model="death_date" {!! !$canEdit ? 'disabled' : '' !!} placeholder="YYYY-MM-DD" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none {{ $canEdit ? 'cursor-pointer' : '' }} bg-white shadow-sm disabled:bg-slate-50 disabled:text-slate-500">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pb-2">
                        <label class="block text-sm font-bold text-slate-700">Biografi Singkat</label>
                        <div class="relative group">
                            <div class="absolute top-3.5 left-3.5 pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <textarea wire:model="bio" rows="3" {!! !$canEdit ? 'disabled' : '' !!} placeholder="Ceritakan sedikit tentang beliau..." class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none shadow-sm resize-none disabled:bg-slate-50 disabled:text-slate-500"></textarea>
                        </div>
                    </div>

                    <!-- Footer - Action Buttons -->
                    <div class="flex gap-3 pt-6 sticky bottom-0 bg-white pb-2 mt-4 space-y-0">
                        <button type="button" wire:click="closeModal" class="flex-1 py-3.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold rounded-xl transition text-sm">{{ $canEdit ? 'Batal' : 'Tutup Profil' }}</button>
                        @if($canEdit)
                        <button type="submit" class="flex-[2] py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition active:scale-[0.98] text-sm shadow-xl shadow-indigo-100 flex items-center justify-center gap-2">
                            <span>Simpan Perubahan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
