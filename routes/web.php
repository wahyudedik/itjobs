<?php

use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfileController;

Route::get('/', [ListingController::class, 'index'])->name('listings.index');
Route::get('/new', [ListingController::class, 'create'])->name('listings.create');
Route::post('/new', [ListingController::class, 'store'])->name('listings.store');

Route::get('/dashboard', function (Request $request) {
    return view('dashboard', [
        'listings' => $request->user()->listings
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/{listing}', [ListingController::class,'show'])->name('listings.show');
Route::get('/{listing}/apply', [ListingController::class, 'apply'])->name('listings.apply');

