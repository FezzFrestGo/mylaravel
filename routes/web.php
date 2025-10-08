<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerangkatController;

// Redirect root and /index to perangkat listing
Route::redirect('/', '/perangkat');
Route::redirect('/index', '/perangkat');

// CRUD routes for perangkat
Route::resource('perangkat', PerangkatController::class)->only([
    'index','store','edit','update','destroy'
]);
