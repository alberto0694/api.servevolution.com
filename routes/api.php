<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\TipoServicoController;
use App\Http\Controllers\ClienteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', [ UsuarioController::class, 'login', 'login' ] );

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/register', [ UsuarioController::class, 'register' ]);
    Route::post('/logout', [ UsuarioController::class, 'logout' ]);
    Route::post('/refresh', [ UsuarioController::class, 'refresh' ]);
    Route::get('/user-profile', [ UsuarioController::class, 'userProfile' ]);

});

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'funcionario'

], function ($router) {

    Route::post('/createOrUpdate', [ FuncionarioController::class, 'createOrUpdate' ]);
    Route::get('/get/{id}', [ FuncionarioController::class, 'getFuncionario' ]);
    Route::get('/delete/{id}', [ FuncionarioController::class, 'deleteFuncionario' ]);
    Route::get('/list', [ FuncionarioController::class, 'getFuncionarios' ]);

});

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'tipo-servicos'

], function ($router) {

    Route::get('/list', [ TipoServicoController::class, 'list' ]);
    Route::post('/createOrUpdate', [ TipoServicoController::class, 'createOrUpdate' ]);
    Route::get('/delete/{id}', [ TipoServicoController::class, 'deleteTipoServico' ]);
    Route::get('/get/{id}', [ TipoServicoController::class, 'getTipoServico' ]);

});

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'clientes'

], function ($router) {

    Route::get('/list', [ ClienteController::class, 'list' ]);
    Route::post('/createOrUpdate', [ ClienteController::class, 'createOrUpdate' ]);
    Route::get('/delete/{id}', [ ClienteController::class, 'deleteTipoServico' ]);
    Route::get('/get/{id}', [ ClienteController::class, 'getCliente' ]);

});








