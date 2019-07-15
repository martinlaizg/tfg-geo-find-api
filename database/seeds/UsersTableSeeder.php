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
        $user->name = 'Martín Laiz Gómez';
        $user->email = 'martinlaizg@gmail.com';
        $user->username = 'martinlaizg';
        $user->password = hash('sha512', 'martinlaizg');
        $user->user_type = 'admin';
        $user->image = 'https://lh4.googleusercontent.com/-eXc5ACBrzHE/AAAAAAAAAAI/AAAAAAAAMAE/19d3oS0_Mmw/s96-c/photo.jpg';
        $user->save();

    }
}
