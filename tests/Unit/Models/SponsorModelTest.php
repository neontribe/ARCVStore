<?php

namespace Tests;

use App\Centre;
use App\Sponsor;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SponsorModelTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function itHasExpectedAttributes()
    {
        $sponsor = factory(Sponsor::class)->make();
        $this->assertNotNull($sponsor->name);
        $this->assertNotNull($sponsor->shortcode);
    }

    /** @test */
    public function itCanHaveCentres()
    {
        // Make a sponsor
        $sponsor = factory(Sponsor::class)->create();
        // These should auto assoicate with the only Sponsor
        $centres = factory(Centre::class, 2)->create();
        $sponsor->fresh();

        // Check it's got centres
        $this->assertNotNull($sponsor->centres);

        // Check the expected associations
        $this->assertEquals(2, $sponsor->centres->count());

        // Check they really are the same Centres
        foreach ($centres as $index => $centre) {
            $this->assertEquals($centres[$index]->name, $centre->name);
        }
    }
}