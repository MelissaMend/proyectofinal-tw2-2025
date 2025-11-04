<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AccesoController;
use App\Http\Controllers\Auth\UsuarioController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\MateriasXUsuarioController;


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

    Route::resource('tipos', TipoController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('materias', MateriaController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('materiasxusuario/{id}', [MateriasXUsuarioController::class, 'index'])->name('materiasxusuario.index'); //datos que se pasan en la ruta
    Route::post('materiasxusuario/{id}/asignar', [MateriasXUsuarioController::class, 'asignar'])->name('materiasxusuario.asignar'); //datos que se pasan encriptados en formulario
    Route::delete('materiasxusuario/{asignacion_id}/desasignar', [MateriasXUsuarioController::class, 'desasignar'])->name('materiasxusuario.desasignar');
    //metodo que permite ejecutar la desasignacion es delete, el metodo que lo ejecuta es desasignar
});

