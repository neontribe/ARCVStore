<?php

use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Auth::loginUsingId(1);
        // two with no cc_reference
        factory(App\Registration::class, 2)->create();

        // Ten with cc_references
        factory(App\Registration::class, 10)->states(['withCCReference'])->create();

    }
}
