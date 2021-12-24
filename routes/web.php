<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\OverviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::group(['middleware' => 'auth'], function () {
    Route::redirect('/', '/overview')->name('index');
    Route::get('/overview', OverviewController::class)->name('overview');

    Route::get('monitors', [MonitorController::class, 'index'])->name('monitors.index');
    Route::get('monitors/create', [MonitorController::class, 'create'])->name('monitors.create');
    Route::post('monitors', [MonitorController::class, 'store'])->name('monitors.store');
    Route::get('monitors/{monitor}', [MonitorController::class, 'show'])->name('monitors.show');
    Route::get('monitors/{monitor}/edit', [MonitorController::class, 'edit'])->name('monitors.edit');
    Route::put('monitors/{monitor}', [MonitorController::class, 'update'])->name('monitors.update');
    Route::get('monitors/{monitor}/delete', [MonitorController::class, 'delete'])->name('monitors.delete');
    Route::delete('monitors/{monitor}', [MonitorController::class, 'destroy'])->name('monitors.destroy');

    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::get('contacts/{contact}/delete', [ContactController::class, 'delete'])->name('contacts.delete');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::get('contacts/{contact}/notify', [ContactController::class, 'notify'])->name('contacts.notify');
});
