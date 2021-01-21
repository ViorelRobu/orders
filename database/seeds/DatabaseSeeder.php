<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserRolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProductTypesSeeder::class);
        $this->call(QualitySeeder::class);
        $this->call(RefinementsSeeder::class);
        $this->call(SpeciesSeeder::class);
    }
}
