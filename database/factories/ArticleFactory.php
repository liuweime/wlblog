<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/4/24
 * Time: 9:36
 */

use Faker\Generator as Faker;

$factory->define(App\Model\Article::class, function (Faker $faker) {
    $authorId = App\Model\User::pluck('id')->random();
    $categoryId = App\Model\Category::pluck('id')->random();
    $title = $faker->sentence(mt_rand(3,6));
    $words = $faker->words(mt_rand(3,5));
    $tags = implode(',', $words);
    return [
        'author_id' => $authorId,
        'category_id' => $categoryId,
        'title' => str_slug($title),
        'tag' => $tags,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});