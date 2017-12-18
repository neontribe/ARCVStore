<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SponsorSeeder::class);
        $this->call(CentreSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RegistrationSeeder::class);
    }
}
