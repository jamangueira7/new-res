<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'DashboardController@index');

Route::get('/login', function () {

    return view('login.index');
});

Route::get('/teste', function () {
    $teste = DB::connection('oracle')
        ->table('LOG_CONTROLE_IMPORTACAO')->get();
    return $teste;
});
//ROUTE PARA FAZER A MIGRAÇÃO DO BANCO ANTIGO PARA O NOVO
Route::get('/change','ChangeOldController@index');


