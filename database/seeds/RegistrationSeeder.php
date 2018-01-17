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

        // two with no cc_reference in our centre
        factory(App\Registration::class, 2)
            ->create([ "centre_id" => Auth::user()->centre->id, ]);

        // 5 with cc_references in our centre
        factory(App\Registration::class, 3)
            ->states(['withCCReference'])
            ->create([ "centre_id" => Auth::user()->centre->id, ]);

        // 10 with cc_references in random centres
        factory(App\Registration::class, 3)
            ->states(['withCCReference'])
            ->create();
    }
}
