<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800">
            Profil Saya
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6 pb-12">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 bg-red-50/30">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-app-layout>
