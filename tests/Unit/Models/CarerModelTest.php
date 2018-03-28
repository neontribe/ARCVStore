<?php

namespace Tests;

use App\Family;
use App\Carer;
use App\Child;
use App\Centre;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CarerModelTest extends TestCase
{

    /** @test */
    public function itHasExpectedAttributes()
    {
        $centre = factory(Carer::class)->make();
        $this->assertNotNull($carer->name);
    }

    /** @test */
    public function itCanHaveAFamily()
    {
        $centre = factory(Carer::class)->make();
    }



}