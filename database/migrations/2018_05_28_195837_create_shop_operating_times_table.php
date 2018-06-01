<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopOperatingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_operating_times', function (Blueprint $table) {
            $table->increments('id')
                ->comment('Id');
            $table->integer('shop_operating_weekday_id')
                ->unsigned()
                ->index()
                ->comment('shop_operating_weekday Id');
            $table->time('opening_time')
                ->comment('Time shop Opens');
            $table->string('open_message')
                ->nullable()
                ->comment('Message for when store is opened');
            $table->time('closing_time')
                ->comment('Time shop closes');
            $table->string('closed_message')
                ->nullable()
                ->comment('Message for when store is closed');
            $table->timestamps();

            $table->foreign('shop_operating_weekday_id')
                ->references('id')->on('shop_operating_weekdays')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_operating_times');
    }
}
