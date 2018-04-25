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
Route::resource('article', 'Api\articleController', ['except' => [
    'create','store','edit','update','destroy'
]]);

Route::group(['prefix' => 'admin'], function () {

    Route::group(['prefix' => 'article'], function () {
        // 获取文章列表
        Route::get('/', 'Admin\articleController@index');
        // 搜索
        Route::post('/search', 'Admin\articleController@search');
        // 获取文章详细
        Route::get('/{id}', 'Admin\articleController@show');
        // 更新文章
        Route::put('/{id}', 'Admin\articleController@update');
        // 删除文章
        Route::delete('/{id}', 'Admin\articleController@destroy');
        // 创建文章
        Route::post('/', 'Admin\articleController@store');
    });
});

Route::resource('comment', 'Api\CommentController', ['except' => [
    'index', 'create', 'edit', 'update'
]]);