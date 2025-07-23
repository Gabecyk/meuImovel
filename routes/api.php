<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RealStateController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function() {
    Route::name('real_state.')->group(function() { // Aplicamos o prefixo de nome aqui

        // Isso vai gerar 7 rotas para RealStateController:
        // GET    /v1/real-states       -> real_state.index    -> RealStateController@index
        // POST   /v1/real-states       -> real_state.store    -> RealStateController@store
        // GET    /v1/real-states/{real_state} -> real_state.show     -> RealStateController@show
        // PUT/PATCH /v1/real-states/{real_state} -> real_state.update   -> RealStateController@update
        // DELETE /v1/real-states/{real_state} -> real_state.destroy  -> RealStateController@destroy
        // GET    /v1/real-states/create -> real_state.create   -> RealStateController@create (geralmente não usada em APIs)
        // GET    /v1/real-states/{real_state}/edit -> real_state.edit     -> RealStateController@edit (geralmente não usada em APIs)

        // Se você quer apenas as rotas que já tinha (index, store, update), use 'only':
        Route::resource('real-states', RealStateController::class)/*->only([
            'index', 'store', 'update'
        ])*/;

        // Se você quiser excluir rotas específicas, use 'except':
        // Route::resource('real-states', RealStateController::class)->except([
        //     'create', 'edit' // Exclui as rotas de formulário que não são comuns em APIs
        // ]);
    });
    Route::name('users.')->group(function() {
        Route::resource('users', UserController::class);
    });
    Route::name('categories.')->group(function() {
        Route::get('categories/{id}/real-states', [CategoryController::class, 'realStates']);
        Route::resource('categories', CategoryController::class);
    });
});
