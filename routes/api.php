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

Route::prefix('v1')->middleware('jwt.auth')->group(function(){
    Route::post('me', 'AuthController@me');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');
    Route::apiResource('client', 'ClientController');
    Route::apiResource('carro', 'CarroController');
    Route::apiResource('locacao', 'LocacaoController');
    Route::apiResource('marca', 'MarcaController');
    Route::apiResource('modelo', 'ModeloController');
    
});


Route::prefix('v1')->group(function(){
    Route::apiResource('CriarNovoUsuario','AuthController' );
});

Route::post('login', 'AuthController@login');

