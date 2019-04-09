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
Route::get('/', ['as' => 'dashboard.index','uses' => 'DashboardController@index'])->middleware('checklogin');
Route::post('/searchDashboard', ['as' => 'search.dashboard','uses' => 'DashboardController@searchForm'])->middleware('checklogin');

//LOGIN
Route::get('/login', function () {
    return view('login.index');
})->middleware('checklogin');

//USUARIOS
Route::resource('user', 'UserController')->middleware('checklogin');
Route::post('/login', ['as' => 'user.login','uses' => 'UserController@login'])->middleware('checklogin');
Route::get('/logout', ['as' => 'user.logout','uses' => 'UserController@logout'])->middleware('checklogin');
Route::get('/restore/{id}', ['as' => 'user.restore','uses' => 'UserController@restore'])->middleware('checklogin');

//RELATORIOS
Route::get('/log-access', ['as' => 'report.index','uses' => 'ReportController@index'])->middleware('checklogin');
Route::post('/access-list', ['as' => 'report.access-list','uses' => 'ReportController@listAccessLog'])->middleware('checklogin');
Route::get('/log-review', ['as' => 'report.review','uses' => 'ReportController@review'])->middleware('checklogin');
Route::post('/review-list', ['as' => 'report.review-list','uses' => 'ReportController@listReviewLog'])->middleware('checklogin');
Route::get('/log-transaction', ['as' => 'report.transaction','uses' => 'ReportController@transaction'])->middleware('checklogin');
Route::post('/transaction-list', ['as' => 'report.transaction-list','uses' => 'ReportController@listTransactionLog'])->middleware('checklogin');
Route::get('/transaction-xml', ['as' => 'report.transaction-xml','uses' => 'ReportController@transactionXML'])->middleware('checklogin');

//ROUTE PARA FAZER A MIGRAÇÃO DO BANCO ANTIGO PARA O NOVO
Route::get('/change','ChangeOldController@index')->middleware('checklogin');


