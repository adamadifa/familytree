<x-mobile-layout>
    <div x-data="{ activeTab: 'home', showDetail: false }" 
         x-init="window.addEventListener('memberSelected', () => { activeTab === 'home' || activeTab === 'tree' ? showDetail = true : null })"
         class="flex flex-col min-h-[calc(100vh-140px)]">
        
        <!-- Content Area -->
        <div class="flex-1">
            <!-- Home Tab -->
            <div x-show="activeTab === 'home'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <!-- Welcome Title -->
                <div class="mb-6">
                    <h2 class="text-xl font-extrabold text-slate-800">Dashboard</h2>
                    <p class="text-xs text-slate-500 font-medium tracking-wide">Selamat datang di silsilah keluarga</p>
                </div>

                <!-- Stat Grid -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center mb-3">
                            <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-2xl font-black text-slate-900">{{ \App\Models\FamilyMember::count() }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Anggota</p>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center mb-3">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </div>
                        <p class="text-2xl font-black text-slate-900">{{ \App\Models\Relationship::count() }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hubungan</p>
                    </div>
                </div>

                <!-- Recent Members / Activity or CTA -->
                <div class="bg-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-indigo-100 mb-6">
                    <h4 class="font-bold text-sm mb-1 text-indigo-50">Explorasi Pohon Keluarga</h4>
                    <p class="text-[11px] text-indigo-100 mb-4 leading-relaxed">Lihat silsilah keluarga Anda secara visual dan interaktif.</p>
                    <button @click="activeTab = 'tree'" class="bg-white text-indigo-600 px-4 py-2 rounded-xl text-xs font-bold shadow-sm active:scale-95 transition">Buka Silsilah</button>
                </div>

                <!-- Quick Access Dashboard Cards -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                            <svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div class="flex-1">
                            <h5 class="text-xs font-bold text-slate-800">Tambah Anggota</h5>
                            <p class="text-[10px] text-slate-400">Hubungkan keluarga baru</p>
                        </div>
                        <button onclick="Livewire.dispatch('openAddMemberModal')" class="p-2 bg-slate-50 rounded-lg text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tree Tab -->
            <div x-show="activeTab === 'tree'" x-transition:enter="transition ease-out duration-300" class="h-full flex flex-col">
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden flex-1 shadow-sm flex flex-col min-h-[500px]">
                    <div class="px-5 py-3 border-b border-slate-50 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-md">Interaktif</span>
                        <p class="text-[10px] text-slate-400">Geser & Zoom</p>
                    </div>
                    <div class="flex-1 bg-slate-50/30">
                        <livewire:family-tree />
                    </div>
                </div>
            </div>

            <!-- List Tab -->
            <div x-show="activeTab === 'list'" x-transition:enter="transition ease-out duration-300">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden min-h-[500px]">
                     <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-bold text-slate-800">Direktori Keluarga</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Daftar lengkap anggota</p>
                    </div>
                    <div class="p-0">
                        <livewire:member-directory />
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Drawer (Bottom Sheet) -->
        <div x-show="showDetail" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="fixed inset-x-0 bottom-0 z-50 bg-white rounded-t-[32px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] border-t border-slate-100 max-h-[85vh] flex flex-col pb-safe">
            
            <!-- Handle bar -->
            <div class="w-full flex justify-center pt-3 pb-1" @click="showDetail = false">
                <div class="w-12 h-1.5 bg-slate-200 rounded-full"></div>
            </div>

            <!-- Header with Close -->
            <div class="px-6 py-2 flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-800">Detail Anggota</h4>
                <button @click="showDetail = false" class="p-2 bg-slate-50 rounded-full text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto px-4 py-2 custom-scrollbar">
                <livewire:member-detail />
            </div>
        </div>

        <!-- Bottom Sheet Overlay -->
        <div x-show="showDetail" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showDetail = false"
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden"></div>

        <!-- Modern Floating Bottom Navigation -->
        <div class="fixed inset-x-0 bottom-6 z-40 px-6">
            <div class="max-w-md mx-auto relative h-16 bg-slate-900/90 backdrop-blur-xl rounded-[24px] shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-white/10 flex items-center overflow-hidden">
                
                <!-- Animated Background Indicator Pill -->
                <div class="absolute top-1.5 bottom-1.5 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] bg-indigo-600 rounded-[18px] z-0 shadow-lg shadow-indigo-500/30"
                     :style="activeTab === 'home' ? 'left: 6px; width: calc(33.33% - 8px);' : (activeTab === 'tree' ? 'left: 33.33%; width: calc(33.33% - 0px); translate: -4px 0; width: calc(33.33% - 4px);' : 'left: 66.66%; width: calc(33.33% - 10px);')"
                     :class="activeTab === 'home' ? 'left-[6px]' : (activeTab === 'tree' ? 'left-[calc(33.33%+2px)]' : 'left-[calc(66.66%+4px)]')">
                </div>

                <!-- Home Tab -->
                <button @click="activeTab = 'home'" class="relative flex flex-col items-center justify-center flex-1 h-full z-10 transition-colors duration-300" :class="activeTab === 'home' ? 'text-white' : 'text-slate-400'">
                    <svg class="h-5 w-5 mb-0.5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-[10px] font-bold uppercase tracking-tight">Home</span>
                </button>

                <!-- Tree Tab -->
                <button @click="activeTab = 'tree'" class="relative flex flex-col items-center justify-center flex-1 h-full z-10 transition-colors duration-300" :class="activeTab === 'tree' ? 'text-white' : 'text-slate-400'">
                    <svg class="h-5 w-5 mb-0.5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.246 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="text-[10px] font-bold uppercase tracking-tight">Silsilah</span>
                </button>

                <!-- List Tab -->
                <button @click="activeTab = 'list'" class="relative flex flex-col items-center justify-center flex-1 h-full z-10 transition-colors duration-300" :class="activeTab === 'list' ? 'text-white' : 'text-slate-400'">
                    <svg class="h-5 w-5 mb-0.5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span class="text-[10px] font-bold uppercase tracking-tight">Daftar</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modals (Global) -->
    <livewire:add-member-modal />
    <livewire:edit-member-modal />
</x-mobile-layout>
