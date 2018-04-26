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

Route::group(['prefix' => 'article'], function () {
    Route::get('/archive', 'Api\articleController@archive')->name('article.archive');
    Route::get('/', 'Api\articleController@index')->name('article.index');
    Route::get('/{id}', 'Api\articleController@show')->name('article.show');
});

Route::group([
    'middleware' => 'auth',
    'prefix' => 'admin'
], function () {
    Route::group(['prefix' => 'article'], function () {
        Route::get('/create', 'Admin\articleController@create')->name('admin.article.create');
        Route::get('/edit/{id}', 'Admin\articleController@edit')->name('admin.article.edit');
        Route::get('/', 'Admin\articleController@search')->name('admin.article.index');
        Route::post('/search', 'Admin\articleController@search')->name('admin.article.index');
        Route::get('/{id}', 'Admin\articleController@show')->name('admin.article.show');
        Route::put('/{id}', 'Admin\articleController@update')->name('admin.article.update');
        Route::delete('/{id}', 'Admin\articleController@destroy')->name('admin.article.destory');
        Route::post('/', 'Admin\articleController@store')->name('admin.article.store');
    });
});

Route::resource('comment', 'Api\CommentController', ['except' => [
    'index', 'create', 'edit', 'update'
]]);