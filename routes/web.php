<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('dashboard')-> name('dashboard');
// });

Route::get('dashboard', 'DashboardController@getDashboard')->name('dashboard');
Route::get('data', 'DashboardController@getData')->name('data');
Route::post('data', 'DashboardController@postData')->name('postdata');
Route::get('predict', 'DashboardController@getPredict')->name('predict');


Route::get('', 'UserController@getLogin')->name('login');
Route::post('', 'UserController@postLogin')->name('postLogin');

Route::get('singin', 'UserController@getSingin')->name('singin');
Route::post('singin', 'UserController@postSingin')->name('postSingin');

Route::get('logout', 'UserController@Logout')->name('logout');
