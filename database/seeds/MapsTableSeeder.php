<?php

use App\Map;
use Carbon\Carbon;
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
        // $faker = Faker\Factory::create('es_ES');

        $date = Carbon::now()->toDateTimeString();

        for ($i = 1; $i <= 10; $i++) {
            $map = new Map;
            $map->name = "Mapa " . $i;
            $map->name = "Descripción " . $i;
            $map->country = "País " . $i;
            $map->state = "Provincia " . $i;
            $map->city = "Ciudad " . $i;
            $map->creator_id = 1;
            $map->image = "http://lorempixel.com/400/200/city/" . $i;
            $map->min_level = "any";
            $map->created_at = $date;
            $map->updated_at = $date;
            $map->save();
        }

    }
}
