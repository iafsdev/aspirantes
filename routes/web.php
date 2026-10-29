<?php

use App\Http\Controllers\AspiranteController;
use App\Http\Controllers\EvaluacionController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/aspirantes');

Route::resource('aspirantes', AspiranteController::class)->only(['create', 'store']);

Route::singleton('aspirantes.evaluacion', EvaluacionController::class)
    ->creatable()
    ->only(['create', 'store', 'edit', 'update']);
