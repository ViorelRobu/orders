<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->addColumn('text', 'pcs_height')->after('loading_date')->nullable();
            $table->addColumn('text', 'rows')->after('pcs_height')->nullable();
            $table->addColumn('text', 'label')->after('rows')->nullable();
            $table->addColumn('boolean', 'foil')->after('label')->default(0);
            $table->addColumn('text', 'pal')->after('foil')->nullable();
            $table->addColumn('text', 'comment')->after('pal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('pcs_height');
            $table->dropColumn('rows');
            $table->dropColumn('label');
            $table->dropColumn('foil');
            $table->dropColumn('pal');
            $table->dropColumn('comment');
        });
    }
}
