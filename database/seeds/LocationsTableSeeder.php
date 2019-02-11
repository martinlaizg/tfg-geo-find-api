<?php

use App\Location;
use App\Map;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cont = 1;
        $maps = Map::all();
        foreach ($maps as $map) {
            $numLocations = rand(5, 10);
            for ($i = 1; $i < $numLocations; $i++) {
                $location = new Location;
                $location->name = "Localización " . $cont;
                $location->lat = rand(38000, 39000) / 1000;
                $location->lon = rand(-1000, 1000) / 1000;
                $location->image = 'http://lorempixel.com/300/200/city/' . rand(1, 10);
                $map->locations()->save($location);
                $cont++;
            }
        }
    }
}
