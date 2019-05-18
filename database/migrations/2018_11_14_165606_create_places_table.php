<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tour_id')->unsigned();
            $table->string('name');
            $table->text('description');
            $table->integer('order')->nullable();
            $table->double('lat', 12, 8);
            $table->double('lon', 12, 8);
            $table->string('image')->default("");
            $table->timestamps();

            // $table->unique(['tour_id', 'order']);
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
