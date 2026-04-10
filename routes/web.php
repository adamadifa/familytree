<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', function () {
    $agent = new \Jenssegers\Agent\Agent();
    if ($agent->isMobile()) {
        return view('dashboard-mobile');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/anggota', \App\Livewire\MemberDirectory::class)
    ->middleware(['auth', 'verified'])
    ->name('anggota');

Route::get('/pengguna', \App\Livewire\UserManager::class)
    ->middleware(['auth', 'verified'])
    ->name('pengguna');

require __DIR__.'/auth.php';
