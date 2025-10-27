<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AccesoController;
use App\Http\Controllers\Auth\UsuarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [AccesoController::class, 'mostrarFormulario'])->name('acceso');

Route::post('/login', [AccesoController::class, 'iniciarSesion'])->name('login');
Route::post('/logout', [AccesoController::class, 'cerrarSesion'])->name('logout');

Route::middleware('auth')->group(function(){
    Route::resource('usuarios', UsuarioController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});
