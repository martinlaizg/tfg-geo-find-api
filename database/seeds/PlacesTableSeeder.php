<?php

use App\Place;
use App\Tour;
use Illuminate\Database\Seeder;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tour = Tour::first();

        $place = new Place;
        $place->name = "Dibujar el espacio";
        $place->description = "Esta gigantesca mano sita en el campus de la Universidad de Alicante, ubicado en el municipio de San Vicente del Raspeig, es una escultura de Pepe Díaz Azorín que expresa un homenaje a la Universidad de Orihuela (1569-1824), recordando los esfuerzos y anhelos de maestros y escolares de otros siglos. Titulada Diuxar l'espai, presidiendo una de las principales entradas del campus, tiene más de 7 m de altura y está realizada con 52 toneladas de homigón blanco. Fue donada a la universidad en 1998 y se ha convertido en su símbolo. Pudo ser restaurada en 2011 gracias a la Fundación Manuel Peláez.";
        $place->order = 0;
        $place->lat = 38.385500;
        $place->lon = -0.511155;
        $tour->places()->save($place);

        $place = new Place;
        $place->name = "Reloj de sol";
        $place->description = "La idea del reloj solar que aquí se presenta nació como propuesta de dotar a la Escuela Politécnica de un elemento simbólico que sirviera como punto de referencia de los edificios que forman esta Escuela dentro del campus de la Universidad de Alicante. La gran cantidad de días despejados de que disponemos en Alicante permiten asegurar el funcionamiento natural de éste. El diseño de este elemento se aparta un poco de la forma convencional de estos instrumentos, y surgió como respuesta al problema de ejecutar materialmente la graduación de las marcas horarias sobre la superficie o cuadra~te sobre la que se proyecta la sombra del estilo, de manera que se pensó en una superficie que con su propia forma produjera dicha graduación. Como consecuencia, la sombra solar sube un escalón cada hora por la mañana, hasta que a partir de mediodía comienza a bajar uno cada hora de la tarde por el otro lado.Este escalonamiento se pensó y sirve de hecho normalmente como asiento para los estudiantes y también el reloj de sol se usa";
        $place->order = 1;
        $place->lat = 38.386237;
        $place->lon = -0.511448;
        $tour->places()->save($place);

        $place = new Place;
        $place->name = "Biblioteca universitaria";
        $place->description = "La Biblioteca general, dispone de aulas de ordenadores, amplio lugar para estudiar y leer, así como una definida oferta de lectura académica y otra literatura";
        $place->order = 2;
        $place->lat = 38.383650;
        $place->lon = -0.512115;
        $tour->places()->save($place);

        $place = new Place;
        $place->name = "Obra de Arte, Escultura de Antoni Miró";
        $place->description = "El alcoyano Antoni Miró donará a la Universidad de Alicante una escultura en conmemoración de La Batalla de Almansa del 25 de abril de 1707 que enfrentó a los ejércitos borbónicos de Felipe V y los austracistas del archiduque Carlos de Austria. Con unas dimensiones de 2,90 x 10 x 1,20 metros y un peso de 4 toneladas y media. Dicha escultura se ha instalado en la fuente situada junto al edificio de Filosofía y Letras III del campus de San Vicente, y lleva por título Almansa 1707. La donación de esta escultura va acompañada del cuadro Les Nigerianes, y de una colección de 80 grabados digitales que formarán parte del fondo del MUA. Inspirada en la obra que el pintor Ricardo Balaca pintó en 1862, Antoni Miró ha querido mostrar en la escultura la retirada de tropas, con soldados llevando a los heridos y muertos, que convirtió la campaña en una carnicería y que supuso el inicio de la pérdida de Valencia de su condición de reino.";
        $place->order = 3;
        $place->lat = 38.384571;
        $place->lon = -0.514871;
        $tour->places()->save($place);

    }
}
