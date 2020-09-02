<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->insert([
            'name' => 'BS'
        ]);
        DB::table('product_types')->insert([
            'name' => 'DIY'
        ]);
        DB::table('product_types')->insert([
            'name' => 'FJ'
        ]);
        DB::table('product_types')->insert([
            'name' => 'DIY Lamella'
        ]);
        DB::table('product_types')->insert([
            'name' => 'Cheeseboards'
        ]);
    }
}
