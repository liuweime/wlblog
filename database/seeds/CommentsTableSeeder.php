<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $comment = factory(\App\Model\Comment::class, 100)->make();
        \App\Model\Comment::insert($comment->toArray());
    }
}
