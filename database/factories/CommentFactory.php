<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/4/24
 * Time: 10:20
 */


$factory->define(App\Model\Comment::class, function (Faker\Generator $faker) {

    $articleId = App\Model\Article::pluck('id')->random();
    return [
        'tid' => $articleId,
        'fid' => 0,
        'nickname' => $faker->userName,
        'email' => $faker->freeEmail,
        'content' => $faker->text(300),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});