<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\NotificationController;

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

    Route::post('document-requests/{document_request}/fulfill', [DocumentRequestController::class, 'fulfill'])->name('document-requests.fulfill');

    Route::get('agent/tickets', [TicketController::class, 'agentIndex'])->name('agent.tickets');
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');

    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

});

require __DIR__.'/settings.php';
