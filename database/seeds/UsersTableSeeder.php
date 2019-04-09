<?php

use App\User;
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
        $user = new User;
        $user->name = 'Martin';
        $user->email = 'martinlaizg@gmail.com';
        $user->username = 'martinlaizg';
        $user->password = 'martinlaizg';
        $user->user_type = 'admin';
        $user->image = 'https://avatars1.githubusercontent.com/u/16946027?s=460&v=4';
        $user->save();
    }
}
