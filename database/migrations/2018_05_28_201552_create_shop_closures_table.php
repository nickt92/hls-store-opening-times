<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopClosuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_closures', function (Blueprint $table) {
            $table->increments('id')
                ->comment('Id');
            $table->string('description')
                ->nullable()
                ->comment('Closure Description');
            $table->dateTime('start')
                ->comment('Closure start date');
            $table->dateTime('finish')
                ->comment('Closure finish date');
            $table->boolean('reoccurring')
                ->comment('is repeated annually');
            $table->boolean('enabled')
                ->comment('is enabled switch');
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
        Schema::dropIfExists('shop_closures');
    }
}
