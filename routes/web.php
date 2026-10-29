<?php

use App\Http\Controllers\AspiranteController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/aspirantes');

Route::resource('aspirantes', AspiranteController::class)->only(['create', 'store']);
