<?php

namespace Tests;

use App\Centre;
use App\Sponsor;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CentreModelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function itHasExpectedAttributes()
    {
        $centre = factory(Centre::class)->make();
        $this->assertNotNull($centre->name);
        $this->assertNotNull($centre->sponsor_id);
        $this->assertContains($centre->print_pref, ['collection', 'individual']);
    }

    /** @test */
    public function itHasASponsor()
    {
        $centre = factory(Centre::class)->create([
            'sponsor_id' => factory(Sponsor::class)->create()->id,
        ]);
        $this->assertInstanceOf(Sponsor::class, $centre->sponsor);
    }

    /** @test */
    public function itCanHaveRegistrations()
    {

    }

    /** @test */
    public function itCanHaveUsers()
    {

    }

    /** @test */
    public function itCanHaveNeighbors()
    {
    }
}
