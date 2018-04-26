<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        //
        $article = factory(\App\Model\Article::class, 20)->make();
        foreach ($article as $item) {
            $name = $item->user->name;
            $title = $item->title;
            $faker = Faker\Factory::create();
            $content = $faker->text(500);
            createMarkdownFile($name, $title, $content);
            unset($item->user);
        }
        \App\Model\Article::insert($article->toArray());
    }
}
