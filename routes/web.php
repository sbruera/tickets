<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentRequestController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::resource('tickets', TicketController::class);

    Route::resource('tickets.documents', DocumentController::class)
        ->shallow();

    Route::resource('tickets.document-requests', DocumentRequestController::class)
        ->shallow();

});

require __DIR__.'/settings.php';
