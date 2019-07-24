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
    }
}
