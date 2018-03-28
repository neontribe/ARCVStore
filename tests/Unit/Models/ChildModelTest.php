<?php

namespace Tests;

use App\Family;
use App\Carer;
use App\Child;
use App\Centre;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChildModelTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function itHasExpectedAttributes()
    {
        $child = factory(Child::class)->make();
        $this->assertNotNull($child->dob);
        $this->assertNotNull($child->born);
    }

    /** @test */
    public function itCreditsWhenAChildIsUnderOne()
    {
    }

    /** @test */
    public function itCreditsWhenAChildIsUnderSchoolAge()
    {

    }

    /** @test */
    public function itNoticesWhenAChildIsAlmostBorn()
    {

    }

    /** @test */
    public function itNoticesWhenAChildIsAlmostOne()
    {

    }

    /** @test */
    public function itNoticesWhenAChildIsAlmostSchoolAge()
    {

    }
}