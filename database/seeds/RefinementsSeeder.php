<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefinementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('refinements')->insert([
            'name' => 'PEFC',
            'description' => 'PEFC surse controlate'
        ]);
        DB::table('refinements')->insert([
            'name' => 'PEFC70',
            'description' => '70% PEFC certified'
        ]);
        DB::table('refinements')->insert([
            'name' => 'PEFC100',
            'description' => '100% PEFC certified'
        ]);
        DB::table('refinements')->insert([
            'name' => 'FOL',
            'description' => 'infoliat'
        ]);
        DB::table('refinements')->insert([
            'name' => 'NOFOL',
            'description' => 'neinfoliat'
        ]);
        DB::table('refinements')->insert([
            'name' => 'RGH',
            'description' => 'neslefuit'
        ]);
        DB::table('refinements')->insert([
            'name' => 'S4S',
            'description' => 'slefuit'
        ]);
        DB::table('refinements')->insert([
            'name' => 'F40',
            'description' => 'lamela cu latimea intre 40-100mm'
        ]);
        DB::table('refinements')->insert([
            'name' => 'F/J',
            'description' => 'lamela FJ'
        ]);
        DB::table('refinements')->insert([
            'name' => 'TAC',
            'description' => 'tac'
        ]);
        DB::table('refinements')->insert([
            'name' => 'PAL',
            'description' => 'palet'
        ]);
        DB::table('refinements')->insert([
            'name' => 'RC',
            'description' => 'colturi rotunjite'
        ]);
    }
}
