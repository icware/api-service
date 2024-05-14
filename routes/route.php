<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Main\MainController;

Route::get('/', [MainController::class, 'main']);


if (class_exists('App\Packages\Example\routes\Example')) {
    // Importe as rotas do pacote Example
    include base_path('app/Packages/Example/routes/Example.php');
}