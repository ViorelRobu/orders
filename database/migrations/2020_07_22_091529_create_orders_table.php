<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_order')->nullable();
            $table->string('auftrag')->nullable();
            $table->unsignedBigInteger('destination_id');
            $table->date('customer_kw')->nullable();
            $table->date('production_kw')->nullable();
            $table->date('delivery_kw')->nullable();
            $table->integer('month')->nullable();
            $table->date('loading_date')->nullable();
            $table->string('priority')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('archived')->default(0);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
