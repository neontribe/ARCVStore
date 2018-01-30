<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->increments('id');
            $table->string('init_centre_id')->nullable();
            $table->string('rvid')->nullable(); // Rose Voucher ID; globally unique per participant.
            $table->dateTime('leaving_on')->nullable();
            $table->string('leaving_reason', 128)->nullable();
            $table->timestamps();
        });

        $table->foreign('init_centre_id')
            ->references('id')
            ->on('centre');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('families');
    }
}
