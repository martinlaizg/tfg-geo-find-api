<?php

use App\Place;
use App\Play;
use App\Tour;
use App\User;
use Illuminate\Database\Seeder;

class PlaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tour = Tour::first();
        $user = User::first();

        $user->tours()->attach($tour->id);
        $place = Place::first();
        $play = Play::first();

        $play->places()->attach($place->id);

    }
}
