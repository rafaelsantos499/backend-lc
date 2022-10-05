<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('auth:api')->group(function(){
    Route::prefix('me',)->group(function(){
        Route::get('', 'MeController@index');
        Route::post('alterar-senha', 'MeController@update');
    });   


    Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');
    Route::apiResource('client', 'ClientController');
    Route::apiResource('carro', 'CarroController');
    Route::apiResource('locacao', 'LocacaoController');
    Route::apiResource('marca', 'MarcaController');
    Route::apiResource('modelo', 'ModeloController');   


    Route::post('novo-usuario','AuthController@novoUsuario' );
    Route::post('login', 'AuthController@login');
    Route::post('password/email', 'AuthController@forgotPassword');
    Route::post('password/reset', 'AuthController@reset');
    Route::post('verificar-email', 'AuthController@verificarEmail');

  
    
});


Route::post('novo-usuario','AuthController@novoUsuario' );
Route::post('login', 'AuthController@login');
Route::post('password/email', 'AuthController@forgotPassword');
Route::post('password/reset', 'AuthController@reset');
Route::post('verificar-email', 'AuthController@verificarEmail');







