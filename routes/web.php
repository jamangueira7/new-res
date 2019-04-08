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

//DASHBOARD
Route::get('/', ['as' => 'dashboard.index','uses' => 'DashboardController@index']);
Route::post('/searchDashboard', ['as' => 'search.dashboard','uses' => 'DashboardController@searchForm']);

//LOGIN
Route::get('/login', function () {
    return view('login.index');
});

//USUARIOS
Route::resource('user', 'UserController');
Route::get('/restore/{id}', ['as' => 'user.restore','uses' => 'UserController@restore']);

//RELATORIOS
Route::get('/log-access', ['as' => 'report.index','uses' => 'ReportController@index']);
Route::post('/access-list', ['as' => 'report.access-list','uses' => 'ReportController@listAccessLog']);
Route::get('/log-review', ['as' => 'report.review','uses' => 'ReportController@review']);
Route::post('/review-list', ['as' => 'report.review-list','uses' => 'ReportController@listReviewLog']);
Route::get('/log-transaction', ['as' => 'report.transaction','uses' => 'ReportController@transaction']);

//CONFIGURATION
Route::get('/parameters', ['as' => 'configuration.parameters','uses' => 'ConfigurationController@parameters']);

//ROUTE PARA FAZER A MIGRAÇÃO DO BANCO ANTIGO PARA O NOVO
Route::get('/change','ChangeOldController@index');


