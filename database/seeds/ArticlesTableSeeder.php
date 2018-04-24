<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $article = factory(\App\Model\Article::class, 20)->make();
        \App\Model\Article::insert($article->toArray());
    }
}
