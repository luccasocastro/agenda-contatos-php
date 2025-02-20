<?php

use App\Http\Controllers\ContatoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('contatos', ContatoController::class);

