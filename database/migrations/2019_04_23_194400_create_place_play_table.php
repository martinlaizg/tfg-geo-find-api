<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacePlayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_play', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('play_id');
            $table->unsignedBigInteger('place_id');
            $table->timestamps();

            $table->unique(['play_id', 'place_id']);
            $table->foreign('play_id')->references('id')->on('plays')->onDelete('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('place_play');
    }
}
