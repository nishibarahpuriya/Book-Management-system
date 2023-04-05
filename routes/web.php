<?php

use Illuminate\Support\Facades\Route;
use App\Http\controllers\bookController;

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');

Route::get('books',[bookController::class, 'books'])->name('books');