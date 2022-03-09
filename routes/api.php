<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FuncionarioController;

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






Route::post('/funcionario/create-simples', [ FuncionarioController::class, 'createSimples' ]);
Route::post('/funcionario/createOrUpdate', [ FuncionarioController::class, 'createOrUpdate' ]);
Route::get('/funcionario/get/{id}', [ FuncionarioController::class, 'getFuncionario' ]);
Route::get('/funcionario/list', [ FuncionarioController::class, 'getFuncionarios' ]);
