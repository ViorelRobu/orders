<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('species_id');
            $table->unsignedBigInteger('quality_id');
            $table->unsignedBigInteger('product_type_id');
            $table->string('default_refinements')->nullable();
            $table->double('thickness', 3, 1);
            $table->double('width', 5, 1);
            $table->timestamps();

            $table->foreign('species_id')->references('id')->on('species')->onDelete('restrict');
            $table->foreign('quality_id')->references('id')->on('quality')->onDelete('restrict');
            $table->foreign('product_type_id')->references('id')->on('product_types')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
