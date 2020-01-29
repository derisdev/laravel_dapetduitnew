<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferwallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offerwalls', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('icon');
            $table->string('title');
            $table->mediumText('image');
            $table->text('description');
            $table->text('coin');
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
        Schema::dropIfExists('offerwalls');
    }
}
