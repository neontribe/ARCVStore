<?php

use Carbon\Carbon;
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
            ->create([ "centre_id" => Auth::user()->centre->id, ]);

        // 10 with cc_references in random centres
        factory(App\Registration::class, 3)
            ->create();

        // One registration with our CC with an incative family.
        $inactive = factory(App\Registration::class)
            ->create(['centre_id' => Auth::user()->centre->id, ])
            ;

        // We will have a better way of incorporating this into factories - but currenly families get created by Reg seeds.
        // So for now, this ensures we have one for testing.
        $family = $inactive->family;
        $family->leaving_on = Carbon::now()->subMonths(2);
        $family->leaving_reason = config('arc.leaving_reasons')[0];
        $family->save();
    }
}
