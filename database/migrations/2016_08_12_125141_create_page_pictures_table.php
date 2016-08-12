<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('page_picture', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('page_id');
        $table->integer('picture_id');
        $table->string('title');
        $table->text('description');
        $table->smallInteger('position');
        $table->float('x');
        $table->float('y');
        $table->float('w');
        $table->float('h');
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
      Schema::drop('page_picture');
    }
}
