<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\TipoServicoController;
use App\Http\Controllers\TipoCustoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\UnidadeMedidaController;
use App\Http\Controllers\TituloController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\ColaboradorController;


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
    'prefix' => 'colaborador'

], function ($router) {

    Route::post('/createOrUpdate', [ ColaboradorController::class, 'createOrUpdate' ]);
    Route::get('/get/{id}', [ ColaboradorController::class, 'getColaborador' ]);
    Route::get('/delete/{id}', [ ColaboradorController::class, 'deleteColaborador' ]);
    Route::get('/list', [ ColaboradorController::class, 'getColaboradores' ]);

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
    'prefix' => 'tipo-custos'

], function ($router) {

    Route::get('/list', [ TipoCustoController::class, 'list' ]);
    Route::post('/createOrUpdate', [ TipoCustoController::class, 'createOrUpdate' ]);
    Route::get('/delete/{id}', [ TipoCustoController::class, 'deleteTipoCusto' ]);
    Route::get('/get/{id}', [ TipoCustoController::class, 'getTipoCusto' ]);

});

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'unidade-medida'

], function ($router) {

    Route::get('/list', [ UnidadeMedidaController::class, 'list' ]);
    Route::post('/createOrUpdate', [ UnidadeMedidaController::class, 'createOrUpdate' ]);
    Route::get('/delete/{id}', [ UnidadeMedidaController::class, 'deleteUnidadeMedida' ]);
    Route::get('/get/{id}', [ UnidadeMedidaController::class, 'getUnidadeMedida' ]);

});

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'clientes'

], function ($router) {

    Route::get('/list', [ ClienteController::class, 'list' ]);
    Route::post('/valor-servico/createOrUpdate', [ ClienteController::class, 'createOrUpdateValorServico' ]);
    Route::post('/valor-funcionario/createOrUpdate', [ ClienteController::class, 'createOrUpdateValorFuncionario' ]);
    Route::post('/createOrUpdate', [ ClienteController::class, 'createOrUpdate' ]);
    Route::get('/valor-servico/delete/{id}', [ ClienteController::class, 'deleteValorServico' ]);
    Route::post('/valor-funcionario/delete', [ ClienteController::class, 'deleteValorFuncionario' ]);
    Route::get('/delete/{id}', [ ClienteController::class, 'deleteCliente' ]);
    Route::get('/get/{id}', [ ClienteController::class, 'getCliente' ]);

});

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'ordem-servicos'

], function ($router) {

    Route::post('/list', [ OrdemServicoController::class, 'list' ])->middleware('permission:PROG_ORDEM_SERVICO,LISTAR_ORDEM_SERVICO');
    Route::post('/createOrUpdate', [ OrdemServicoController::class, 'createOrUpdate' ])->middleware('permission:PROG_ORDEM_SERVICO,INSERIR_ORDEM_SERVICO');
    Route::get('/delete/{id}', [ OrdemServicoController::class, 'deleteOrdemServico' ])->middleware('permission:PROG_ORDEM_SERVICO,INSERIR_ORDEM_SERVICO');
    Route::post('/finalizar/{id}', [ OrdemServicoController::class, 'finalizarOrdemServico' ]);
    Route::get('/get/{id}', [ OrdemServicoController::class, 'getOrdemServico' ])->middleware('permission:PROG_ORDEM_SERVICO,INSERIR_ORDEM_SERVICO');
    Route::get('/valor-servico-os/{id}', [ OrdemServicoController::class, 'getValorServicoOS' ]);

    Route::post('/funcionario/delete/{ordem_servico_id}', [ OrdemServicoController::class, 'deleteFuncionarioOrdemServico' ]);
    Route::post('/list-kanban', [ OrdemServicoController::class, 'listKanban' ])->middleware('permission:PROG_ORDEM_SERVICO,INSERIR_ORDEM_SERVICO');
});

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'titulo'

], function ($router) {

    Route::post('/list', [ TituloController::class, 'tituloList' ]);
    Route::post('/ordem-servicos', [ TituloController::class, 'ordemServicoList' ]);
    Route::post('/ordem-servicos/gerar', [ TituloController::class, 'gerarTitulo' ]);
    Route::get('/delete/{id}', [ TituloController::class, 'deleteTitulo' ]);
    
});


Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'financeiro'

], function ($router) {

    Route::get('/transacoesList', [ FinanceiroController::class, 'transacoesList' ]);
    Route::post('/baixarParcela', [ FinanceiroController::class, 'baixarParcela' ]);
    
    
});







