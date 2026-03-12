<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('index', function () {
        return view('index');
    })->name('index');

    Route::get('dashboard', App\Livewire\MainDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
});

// Inventory Module - Protected by 'ver inventario'
Route::middleware(['auth', 'verified', 'can:ver inventario'])->group(function () {
    Route::get('inventory', App\Livewire\Inventory\Dashboard::class)->name('inventory.dashboard');
    Route::get('inventory/products', App\Livewire\Inventory\Products::class)->name('inventory.products');
    Route::get('inventory/movements', App\Livewire\Inventory\Movements::class)->name('inventory.movements');
});

// Accounting Module - Protected by 'ver contabilidad'
Route::middleware(['auth', 'verified', 'can:ver contabilidad'])->group(function () {
    Route::get('nomenclatures', App\Livewire\Nomenclatures\Index::class)->name('nomenclatures.index');
    Route::get('accounting-rules', App\Livewire\AccountingRules\Index::class)->name('accounting-rules.index');
    Route::get('diary', App\Livewire\DiaryManager::class)->name('diary.index');
    Route::get('ledger', App\Livewire\LedgerReport::class)->name('ledger.index');
    Route::get('results', App\Livewire\ResultsReport::class)->name('results.index');
    Route::get('balance', App\Livewire\BalanceSheetReport::class)->name('balance.index');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::get('settings/users', App\Livewire\Settings\Users::class)
        ->middleware(['can:gestionar usuarios'])
        ->name('users.index');

    Route::get('settings/roles', App\Livewire\Settings\Roles::class)
        ->middleware(['role:admin']) // Only admins should create roles ideally, or 'manage users'
        ->name('roles.index');
});
