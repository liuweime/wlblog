<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $category = factory(\App\Model\Category::class, 20)->make();
        \App\Model\Category::insert($category->toArray());
    }
}
