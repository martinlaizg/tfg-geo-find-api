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
        $user->password = 'cd17168a0bb539e433ad3d527067defd5f5c1588f12fdf420da330d17a303b3e8ebaac6ea1729a4e4089c51517b6b0ef6899ea32b1eaf5d998b7e86ab8b4e8c9';
        $user->user_type = 'admin';
        $user->image = 'https://avatars1.githubusercontent.com/u/16946027?s=460&v=4';
        $user->save();
    }
}
