<?php

use App\Map;
use App\User;
use Illuminate\Database\Seeder;

class MapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $map = new Map;
        $map->name = "Conocer Alicante. Paseo Marítimo de la Serra Grossa";
        $map->description = "Plaza Gabriel Miró, Explanada de España, Plaza de Canalejas, Dársena Interior, Paseo del Puerto, Paseo de Gómiz, Playa del Postiguet, Vista al Monte Benacantíl, Castillo de Santa Bárbara y Cabeza del Moro.";
        $map->country = "España";
        $map->state = "Alicante";
        $map->city = "Alicante/Alacant";
        $map->image = "http://lorempixel.com/400/200/city/";
        $map->min_level = "compass";
        $user->createdMaps()->save($map);
    }
}
