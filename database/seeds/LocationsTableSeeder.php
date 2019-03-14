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
        $map = Map::first();

        $loc = new Location;
        $loc->lat = 38.343534;
        $loc->lon = -0.485350;
        $loc->name = "WP N.- 1 INICIO - Plaza de Gabriel Miró ( Correos )";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.344503;
        $loc->lon = -0.478957;
        $loc->name = "WP N.- 2 Playa del Postiguet, Puerta del Mar  1,1 Km";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.348833;
        $loc->lon = -0.472058;
        $loc->name = "WP N.- 3 Playa del Postiguet, La Marina  2 Km.";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.351056;
        $loc->lon = -0.468942;
        $loc->name = "WP N.- 4 Playa del Cocó, R. C. de Regatas 2,4 Km";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.355405;
        $loc->lon = -0.460875;
        $loc->name = "WP N.- 5 Final Paseo Bajo, Subida a la  Loma 3,5 Km.";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.359689;
        $loc->lon = -0.453267;
        $loc->name = "WP N.- 6 Intersección Ruta Corta,  3,5 Km Bajada a Orillamar";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.362436;
        $loc->lon = -0.447621;
        $loc->name = "WP N.- 7 La Isteta,  Albufereta ( TRAM ) 5,2 Km.";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.365109;
        $loc->lon = -0.449698;
        $loc->name = "WP N.- 8 Subida Sant Juliá ( 6,6 Km y 35 m.)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.362040;
        $loc->lon = -0.450525;
        $loc->name = "WP N.- 8 bis Barrera ( 6,1 Km y 73 m)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.363369;
        $loc->lon = -0.450554;
        $loc->name = "WP N.- 9 Caminos y Senderos 6,5 Km y 124 m.";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.364114;
        $loc->lon = -0.450967;
        $loc->name = "WP N.- 10 Asentamiento Neolítico ( 6,7 Km y 116 m)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.362529;
        $loc->lon = -0.452951;
        $loc->name = "WP N.- 11 Intersección Izq.  ( 7,1 Km y 160 m.)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.361783;
        $loc->lon = -0.453000;
        $loc->name = "WP N.- 12 Mirador de Levante ( 7, 2 Km y 165 m.)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.360999;
        $loc->lon = -0.457203;
        $loc->name = "WP N.- 13 Collado Jesuitas ( 7, 8 Km y 170 m.)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.358099;
        $loc->lon = -0.459692;
        $loc->name = "WP N. 14 Mirador de los Fuegos  ( 8,2 Km y 177 m.)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.357101;
        $loc->lon = -0.462163;
        $loc->name = "WP N.- 15 Mirador de Poniente ( 8,5 KIm y 164 m)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.357222;
        $loc->lon = -0.464719;
        $loc->name = "WP N.- 16 Abajo ( 8,9 Km y 73 m.)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.353906;
        $loc->lon = -0.470316;
        $loc->name = "WP N.- 17 La Sangueta. TRAM ( 9,6 Km y 42 m )";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.348653;
        $loc->lon = -0.474772;
        $loc->name = "WP N.- 18 Virgen del Socorro ( 10 ,5 Km y 17 m)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.346603;
        $loc->lon = -0.478318;
        $loc->name = "WP N.- 19 San Roque ( 11 Km y 27 m)";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.346086;
        $loc->lon = -0.478980;
        $loc->name = "WP N.- 20 Paseito Ramiro ( 11,3 Km )";
        $map->locations()->save($loc);

        $loc = new Location;
        $loc->lat = 38.343712;
        $loc->lon = -0.484616;
        $loc->name = "WP N.-  21 FINAL,  Plaza de Correos ( 12 Km.)";
        $map->locations()->save($loc);

    }
}
