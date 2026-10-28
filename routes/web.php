<?php

use Illuminate\Support\Facades\Route;

// La página de inicio no tiene contenido propio: manda directo al listado.
Route::redirect('/', '/aspirantes');
