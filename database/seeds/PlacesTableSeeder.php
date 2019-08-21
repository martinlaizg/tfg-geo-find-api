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
        // Universidad de Alicante
        $tour = Tour::find(1);
        $place = new Place;
        $place->name = "Dibujar el espacio";
        $place->description = "Esta gigantesca mano sita en el campus de la Universidad de Alicante, ubicado en el municipio de San Vicente del Raspeig, es una escultura de Pepe Díaz Azorín que expresa un homenaje a la Universidad de Orihuela (1569-1824), recordando los esfuerzos y anhelos de maestros y escolares de otros siglos";
        $place->order = 0;
        $place->lat = 38.385500;
        $place->lon = -0.511155;
        $place->image = "https://veu.ua.es/es/imagenes/noticias/2019/2/mano-ua.jpg";
        $tour->places()->save($place);
        $place = new Place;
        $place->name = "Reloj de sol";
        $place->description = "La idea del reloj solar que aquí se presenta nació como propuesta de dotar a la Escuela Politécnica de un elemento simbólico que sirviera como punto de referencia de los edificios que forman esta Escuela dentro del campus de la Universidad de Alicante";
        $place->order = 1;
        $place->lat = 38.386237;
        $place->lon = -0.511448;
        $place->image = "https://revista.eps.ua.es/index.php/galeria-de-fotos/1-xcbv/detail/37-2009-11-20_fotospoli_05?tmpl=component&phocadownload=2";
        $tour->places()->save($place);
        $place = new Place;
        $place->name = "Biblioteca universitaria";
        $place->description = "La Biblioteca general, dispone de aulas de ordenadores, amplio lugar para estudiar y leer, así como una definida oferta de lectura académica y otra literatura";
        $place->order = 2;
        $place->lat = 38.383650;
        $place->lon = -0.512115;
        $place->image = "https://blogs.ua.es/bibliotecauniversitaria/files/2017/06/bibioteca-general.jpg";
        $tour->places()->save($place);
        $place = new Place;
        $place->name = "Obra de Arte, Escultura de Antoni Miró";
        $place->description = "El alcoyano Antoni Miró donará a la Universidad de Alicante una escultura en conmemoración de La Batalla de Almansa del 25 de abril de 1707 que enfrentó a los ejércitos borbónicos de Felipe V y los austracistas del archiduque Carlos de Austria. Con unas dimensiones de 2,90 x 10 x 1,20 metros y un peso de 4 toneladas y media.";
        $place->order = 3;
        $place->lat = 38.384571;
        $place->lon = -0.514871;
        $place->image = "https://veu.ua.es/es/imagenes/noticias/2016/11/mirohorizontal.jpg";
        $tour->places()->save($place);

        // Valencia
        $tour = Tour::find(2);
        $place = new Place;
        $place->name = "Ciudad de las Artes y las Ciencias";
        $place->description = "La Ciudad de las Artes y las Ciencias es un complejo arquitectónico, cultural y de entretenimiento de la ciudad de Valencia. El complejo fue diseñado por Santiago Calatrava y Félix Candela, junto con los ingenieros autores del diseño estructural de las cubiertas del L'Oceanografic Alberto Domingo y Carlos Lázaro.";
        $place->order = 0;
        $place->lat = 39.454853;
        $place->lon = -0.350458;
        $place->image = "https://lh5.googleusercontent.com/p/AF1QipOfmVkd1fl-BcQUwfVOlzkciQO9YIjJ5GDgEMU6=w408-h306-k-no";
        $tour->places()->save($place);

    }
}
