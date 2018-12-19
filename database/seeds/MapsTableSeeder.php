<?php

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

		DB::table('maps')->insert([
			'name' => 'Playas',
			'country' => 'España',
			'state' => 'Alicante/Alacant',
			'city' => 'Benidorm',
			'user_id' => 1,
			'min_level'=>'any',
		]);

		// $map = factory(App\Map::class, 10)->create();
    }
}
