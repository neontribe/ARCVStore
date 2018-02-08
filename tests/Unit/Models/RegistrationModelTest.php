<?php

namespace Tests;

use App\Centre;
use App\Registration;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationModelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function itCanReturnRegistrationsOnlyForActiveFamilies()
    {
        // Create a centre
        $centre = factory(Centre::class)->create();

        // Create 4 random registrations (and families etc.) in that centre.
        $registrations = factory(Registration::class, 4)->create([
            'centre_id' => $centre->id,
        ]);

        // Check that we have 4.
        $this->assertEquals(Registration::whereActiveFamily()->count(), 4);

        // A family has left.
        $family = $registrations->first()->family;
        $family->leaving_on = Carbon::now();
        $family->save();

        // check there are only 3.
        $this->assertEquals(Registration::whereActiveFamily()->count(), 3);
    }
}
