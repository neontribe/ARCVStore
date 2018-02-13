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
        // Pick a seed scenario
        switch (config('app.seeds')) {
            case "Usability":
                $this->usabilitySeeds();
                break;
            case "Dev":
            default:
                $this->devSeeds();
                break;
        }
    }

    /**
     * Standard scenario for development
     */
    public function devSeeds()
    {
        $this->call(SponsorSeeder::class);
        $this->call(CentreSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RegistrationSeeder::class);
    }

    /**
     * Specific scenario for usability testing
     */
    public function usabilitySeeds()
    {
        $this->call(UsabilityScenarioSeeder::class);
    }
}
