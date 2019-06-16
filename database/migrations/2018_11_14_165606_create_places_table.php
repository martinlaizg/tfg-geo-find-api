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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tour_id');
            $table->string('name');
            $table->text('description');
            $table->string('question')->nullable();
            $table->string('answer')->nullable();
            $table->string('answer2')->nullable();
            $table->string('answer3')->nullable();
            $table->integer('order');
            $table->double('lat', 12, 8);
            $table->double('lon', 12, 8);
            $table->string('image')->nullable();
            $table->timestamps();

            $table->unique(['tour_id', 'order']);
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
