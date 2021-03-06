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
        $centres = factory(App\Centre::class, 4)->create();

        // Grab one and change print pref to individual and another to collection.
        $centres[0]->print_pref = 'collection';
        $centres[0]->save();
        $centres[1]->print_pref = 'individual';
        $centres[1]->save();
    }
}
