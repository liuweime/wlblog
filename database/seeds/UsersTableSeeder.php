<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(\App\Model\User::class, 20)->make();
        \App\Model\User::insert($user->toArray());
    }
}
