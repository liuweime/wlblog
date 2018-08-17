<?php

use Illuminate\Http\Request;

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
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


/**
 * 前台接口
 */
/************************************* 文章添加 *************************************/
Route::group(['prefix' => 'article'], function () {
    // 文章归档列表
    Route::get('/archive', 'Api\articleController@archive')->name('article.archive');
    // 文章列表
    Route::get('/', 'Api\articleController@index')->name('article.index');
    // 文章详情
    Route::get('/{id}', 'Api\articleController@show')->name('article.show')->where('id', '[0-9]+');
    // 分类下文章
    Route::get('/category/{name}', 'Api\articleController@classifiedArticle')->name('article.classified');
    // 标签下文章
    Route::get('/tag/{name}', 'Api\articleController@tagArticle')->name('article.tag');
});
/************************************* 评论添加 *************************************/
Route::group(['prefix' => 'comment'], function () {
    // 获取评论
    Route::get('/{id}', 'Api\CommentController@show')->name('comment.show')->where('id', '[0-9]+');
    // 添加评论
    Route::post('/', 'Api\CommentController@store')->name('comment.store');
    // 添加回复
    Route::post('/reply', 'Api\CommentController@reply')->name('comment.reply');
});
/************************************* 文章分类 *************************************/
Route::group(['prefix' => 'category'], function () {
    // 分类列表
    Route::get('/', 'Api\CategoryController@index')->name('category.index');
});

/**
 * 后台接口
 */
Route::group(['prefix' => 'admin'], function () {

    /************************************* 文章管理 *************************************/
    Route::group(['prefix' => 'article', 'middleware' => 'auth'], function () {

        // 获取文章列表
        Route::post('/index', 'Admin\articleController@search')->name('admin.article.index');

        Route::post('/', 'Admin\articleController@store')->name('admin.article.store');
        Route::put('/{id}', 'Admin\articleController@update')->name('admin.article.update');
        Route::delete('/{id}', 'Admin\articleController@destroy')->name('admin.article.destory');

        Route::get('/{id}', 'Admin\articleController@show')->name('admin.article.show');

    });

    /************************************* 文章分类管理 *************************************/
    Route::resource('category', 'Admin\CategoryController');

    /************************************* 标签管理 *************************************/

    /************************************* 评论管理 *************************************/

    /************************************* 多媒体文件管理 *************************************/
});