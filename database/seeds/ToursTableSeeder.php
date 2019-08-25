<?php

use App\Tour;
use App\User;
use Illuminate\Database\Seeder;

class ToursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        $tour = new Tour;
        $tour->name = "Universidad de Alicante";
        $tour->description = "La Universidad de Alicante disfruta de uno de los campus más atractivos de España. Esa es la generalizada impresión tanto de sus visitantes como de los expertos, que valoran su calidad ambiental y la arquitectura de sus modernos edificios. De hecho, el campus de Alicante es un importante aliciente añadido a sus ventajas como sede de encuentros académicos nacionales e internacionales y para cursos y estancias de alumnos y profesores.";
        $tour->image = "https://web.ua.es/es/estudia-ua/imagenes/banner-conoce-la-ua.jpg";
        $tour->min_level = "map";
        $user->createdTours()->save($tour);

        $tour = new Tour;
        $tour->name = "Valencia";
        $tour->description = "La ciudad portuaria de Valencia se ubica en la costa sureste de España, donde el río Turia se une al mar Mediterráneo. Es famosa por la Ciudad de las Artes y las Ciencias, con estructuras futurísticas, como el planetario, el oceanario y un museo interactivo. Valencia también tiene varias playas, incluidas algunas dentro del cercano Parque de la Albufera, una reserva de humedales con un lago y senderos. Fundada en la época romana, Valencia tiene edificios de cientos de años de antigüedad, incluida la puerta de la ciudad medieval (y la antigua prisión) de las Torres de Serranos. La Lonja de la Seda del siglo XVI se construyó cuando la ciudad era un centro mercantil rico y poderoso. Al frente, se ubica el gran Mercado Central (un mercado cubierto), construido en 1914. En el centro medieval, la Catedral de Valencia alberga un cáliz sagrado del siglo I y el campanario octagonal del Miguelete. El Jardín del Turia forma una franja verde que conecta el Zoológico Bioparc con el puerto y la playa de Malvarrosa. El Museo de Bellas Artes tiene obras de El Greco y Goya.";
        $tour->image = "https://ep01.epimg.net/politica/imagenes/2018/12/30/actualidad/1546195822_445312_1546195993_noticia_normal.jpg";
        $tour->min_level = "map";
        $user->createdTours()->save($tour);
    }
}
