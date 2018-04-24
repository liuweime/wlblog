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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/article/archive', 'Api\articleController@archive')->name('article.archive');
Route::resource('article', 'Api\articleController');
Route::resource('comment', 'Api\CommentController', ['except' => [
    'index', 'create', 'edit', 'update'
]]);