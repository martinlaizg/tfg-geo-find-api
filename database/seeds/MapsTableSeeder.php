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
        $map->name = "Universidad de Alicante";
        $map->description = "La Universidad de Alicante disfruta de uno de los campus más atractivos de España. Esa es la generalizada impresión tanto de sus visitantes como de los expertos, que valoran su calidad ambiental y la arquitectura de sus modernos edificios. De hecho, el campus de Alicante es un importante aliciente añadido a sus ventajas como sede de encuentros académicos nacionales e internacionales y para cursos y estancias de alumnos y profesores. Con una extensión actual de cerca de un millón de metros cuadrados y terrenos anejos disponibles para su expansión de otros 650.000 metros, una de sus principales características es su aspecto amplio y despejado. Su juventud le ha permitido ser diseñado urbanísticamente desde el principio según las más actuales tendencias. El nivel arquitectónico de sus edificios está avalado por la relación de premios y menciones que han obtenido, algunos de ellos construidos por arquitectos de prestigio internacional. A ello se añade una cuidada jardinería, un importante arbolado y un muy alto índice de peatonalización. La ampliación en perspectiva del campus se está planificando para que consolide y aumente esa calidad.";
        $map->country = "España";
        $map->state = "Alicante";
        $map->city = "Alicante/Alacant";
        $map->image = "https://web.ua.es/es/estudia-ua/imagenes/banner-conoce-la-ua.jpg";
        $map->min_level = "map";
        $user->createdMaps()->save($map);
    }
}
