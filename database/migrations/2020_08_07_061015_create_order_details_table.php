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
            $table->string('refinements_list');
            $table->double('thickness', 3, 1);
            $table->double('width', 5, 1);
            $table->double('length', 5, 1)->nullable();
            $table->integer('pcs');
            $table->double('volume', 5, 3);
            $table->boolean('produced_ticom')->default(0);
            $table->string('batch')->nullable();
            $table->boolean('produced_batch')->default(0);
            $table->date('loading_date')->nullable();
            $table->text('details_json')->nullable();
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
