<?php

use Illuminate\Database\Seeder;

class CentreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 4 random Centres
        factory(App\Centre::class, 4)->create();
    }
}
