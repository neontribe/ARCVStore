<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('family_id')->unsigned(); // FK Families
            $table->timestamps();

            $table->foreign('family_id')
                ->references('id')
                ->on('families');

            $table->unique(['name', 'family_id'], 'unique_family_carer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carers');
    }
}
