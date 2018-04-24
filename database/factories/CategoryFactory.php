<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/4/24
 * Time: 10:16
 */

$factory->define(App\Model\Category::class, function (Faker\Generator $faker) {
    $word = $faker->word;
    return [
        'parent_id' => 0,
        'name' => $word,
        'alias' => $word,
        'desc' => $faker->text(50),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});