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
        $map->description = "Plaza Gabriel Miró, Explanada de España, Plaza de Canalejas, Dársena Interior, Paseo del Puerto, Paseo de Gómiz, Playa del Postiguet, Vista al Monte Benacantíl, Castillo de Santa Bárbara y Cabeza del Moro. La Marina, Acceso al Puerto Deportivo, Isla Marina, Playa del Cocó y Real Club de Regatas.
		La Cantera, Nuevo Paseo de los Miradores del Norte, Subida a la Loma del Túnel del Trenet.
		\"Caminante no hay camino, se hace camino al andar \", este tramo que no es un paseo, esta mejorando día a día, al ser frecuentemente transitado.
		Por el nuevo paseo, Av. de Villajoyosa. Desvío Ruta Corta, Calle Sol Naciente, Hotel Albahia.
		La Albufereta, La Isleta ( TRAM ). Lavadero de coches, Calle Monte San Juliá.
		Subiendo a la Serra Grossa, Barrera, Asentamiento Neolítico,Mirador de Levante, Caminar la Sierra de Este a Oeste, Collado de los Jesuitas, Mirador Sur, de los Fuegos , y Mirador de Poniente, bajamos por el Sendero Noroeste hasta el Camino de mantenimiento del Túnel de la Serra.
		Sendero ladera Norte de la Serra del Molinet o Santa Ana. La Goteta ( TRAM ) Plaza Mar n.2. Av. de Dénia, Calle Virgen del Socorro, Plaza de Topete, Mirador a la Bahía de Alicante.
		Virgen del Socorro, Calle Villavieja diferentes perspectivas a la Cabeza de la Cabeza del Moro, Plaza de Santa María, Basílica y Museo de Arte Contemporáneo.
		Plaza Paseíto Ramiro, Balas de Cañón Incrustadas en el Muro de Basílica ( Recuerdo Ingles )
		Calle Jorge Juan, Plaza del Ayuntamiento, Calle Altamira, Plaza del Portal de Elche, Plaza Gabriel Miro ( Correos ), Ficus Centenarios .  ";
        $map->country = "España";
        $map->state = "Alicante";
        $map->city = "Alicante/Alacant";
        $map->image = "http://lorempixel.com/400/200/city/";
        $map->min_level = "compass";
        $user->createdMaps()->save($map);
    }
}
