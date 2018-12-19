<?php

use Carbon\Carbon;

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

		$date = Carbon::now()->toDateTimeString();

		DB::table('maps')->insert([
			'name' => 'Playas',
			'country' => 'España',
			'state' => 'Alicante/Alacant',
			'city' => 'Benidorm',
			'creator_id' => 1,
			'min_level'=>'any',
			'created_at' => $date,
			'updated_at'=> $date
		]);

		// $map = factory(App\Map::class, 10)->create();
    }
}
