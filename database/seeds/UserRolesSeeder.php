<?php

use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role' => 'administrator'
        ]);
        DB::table('roles')->insert([
            'role' => 'planificare'
        ]);
        DB::table('roles')->insert([
            'role' => 'productie'
        ]);
        DB::table('roles')->insert([
            'role' => 'sef schimb'
        ]);

        DB::table('user_role')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);

    }
}
