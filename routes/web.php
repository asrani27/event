<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;

Route::get('/', [LoginController::class, 'showLoginForm']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Event Routes
    Route::resource('admin/events', EventController::class)->names('admin.events');

    // Participant Routes
    Route::post('admin/events/{event}/participants', [ParticipantController::class, 'store'])->name('admin.events.participants.store');
    Route::delete('admin/events/{event}/participants/{participant}', [ParticipantController::class, 'destroy'])->name('admin.events.participants.destroy');
    Route::put('admin/events/{event}/participants/{participant}/status', [ParticipantController::class, 'updateStatus'])->name('admin.events.participants.updateStatus');
    Route::get('admin/events/{event}/scan_kehadiran', [ParticipantController::class, 'showScanPage'])->name('admin.events.scan_kehadiran');
    Route::post('admin/events/{event}/scan_kehadiran', [ParticipantController::class, 'processScan'])->name('admin.events.process_scan');
    Route::post('admin/events/{event}/import-excel', [ParticipantController::class, 'importExcel'])->name('admin.events.import_excel');
});

Route::get('api/pegawai/search', [ParticipantController::class, 'searchPegawai'])->name('pegawai.search');
// Public Routes
Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/password/request', function () {
    return view('auth.passwords.email');
})->name('password.request');
