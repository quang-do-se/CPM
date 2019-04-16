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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/uploadICD9', 'UploadController@uploadICD9Landing');
Route::post('/uploadICD9', 'UploadController@uploadICD9');

Route::get('/uploadPhecode', 'UploadController@uploadPhecodeLanding');
Route::post('/uploadPhecode', 'UploadController@uploadPhecode');

Route::get('/uploadICD9Phecode', 'UploadController@uploadICD9PhecodeLanding');
Route::post('/uploadICD9Phecode', 'UploadController@uploadICD9Phecode');


Route::get('/api/searchICD9Phecode', 'SearchAPIController@getICD9Phecode');
Route::get('/api/searchICD9', 'SearchAPIController@getICD9');
Route::get('/api/searchPhecode', 'SearchAPIController@getPhecode');

Route::get('/search', 'SearchController@index');
