<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_id');
            $table->string('title');
            $table->text('description');
            $table->smallInteger('position');
            $table->tinyInteger('bg_color_red');
            $table->tinyInteger('bg_color_green');
            $table->tinyInteger('bg_color_blue');
            $table->tinyInteger('bg_color_alpha');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
