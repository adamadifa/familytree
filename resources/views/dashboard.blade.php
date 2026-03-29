<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800">Dashboard</h2>
    </x-slot>

    <!-- Stat Cards -->
    {{-- 
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Anggota</p>
                <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ \App\Models\FamilyMember::count() }}</p>
            <p class="text-xs text-slate-400 mt-1">Terdaftar dalam sistem</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hubungan</p>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ \App\Models\Relationship::count() }}</p>
            <p class="text-xs text-slate-400 mt-1">Koneksi keluarga tercatat</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Generasi</p>
                <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">3</p>
            <p class="text-xs text-slate-400 mt-1">Generasi terdokumentasi</p>
        </div>
    </div>
    --}}

    <!-- Tree + Detail -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Tree Visualization -->
        <div class="flex-1">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Pohon Silsilah</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Klik anggota untuk melihat detail</p>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-md">Interaktif</span>
                </div>
                <div class="p-0">
                    <livewire:family-tree />
                </div>
            </div>
        </div>

        <!-- Member Detail Sidebar -->
        <div class="w-full lg:w-80 flex-shrink-0">
            <livewire:member-detail />
        </div>
    </div>

    <livewire:add-member-modal />
    <livewire:edit-member-modal />
</x-app-layout>
