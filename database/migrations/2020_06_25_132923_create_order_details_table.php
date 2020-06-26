<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('article_id');
            $table->string('finisaje');
            $table->double('grosime', 5,2);
            $table->double('latime', 6,2);
            $table->double('lungime', 7,3);
            $table->double('bucati', 4);
            $table->double('volum', 6, 3);
            $table->string('eticheta')->nullable();
            $table->string('stick_panou')->nullable();
            $table->string('ean_pal')->nullable();
            $table->string('ean_picior')->nullable();
            $table->string('paletizare')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('restrict');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
