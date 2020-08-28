<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QualitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quality')->insert([
            'name' => 'A'
        ]);
        DB::table('quality')->insert([
            'name' => 'AB'
        ]);
        DB::table('quality')->insert([
            'name' => 'B'
        ]);
        DB::table('quality')->insert([
            'name' => 'BC'
        ]);
        DB::table('quality')->insert([
            'name' => 'C'
        ]);
        DB::table('quality')->insert([
            'name' => 'CC'
        ]);
    }
}
