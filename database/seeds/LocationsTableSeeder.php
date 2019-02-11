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
                $location->lat = rand(3800000, 390000) / 10000;
                $location->lon = rand(-100000, 10000) / 10000;
                $location->image = 'http://lorempixel.com/300/200/city/' . $numLocations;
                $map->locations()->save($location);
                $cont++;
            }
        }
    }
}
