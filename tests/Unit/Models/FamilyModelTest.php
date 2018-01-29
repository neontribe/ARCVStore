<?php

namespace Tests;

use App\Family;
use App\Carer;
use App\Child;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FamilyModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function itHasAnAttributeThatCalculatesSumOfEligibleChildren()
    {
        // Create Family
        $family = factory(Family::class)->create();

        // Add 2 Carers
        $family->carers()->saveMany(factory(Carer::class, 2)->make());

        // Add some Children
        $family->children()
            ->saveMany(
                collect([
                    factory(Child::class, 'unbornChild')->make(),
                    factory(Child::class, 'underOne', 2)->make(),
                    factory(Child::class, 'underSchoolAge')->make(),
                    factory(Child::class, 'overSchoolAge')->make(),
                ])->flatten()
            );

        $this->assertEquals($family->eligibleChildrenCount, 3);
    }

    /** @test */
    public function itHasAnAttributeThatReturnsNearestDueDateOrNull()
    {
        // Create Family
        $family = factory(Family::class)->create();

        // Add 2 Carers
        $family->carers()->saveMany(factory(Carer::class, 2)->make());

        // Add some born Children
        $family->children()
            ->saveMany(
                collect([
                    factory(Child::class, 'underOne', 2)->make(),
                    factory(Child::class, 'underSchoolAge')->make(),
                    factory(Child::class, 'overSchoolAge')->make(),
                ])->flatten()
            );

        // Test we've not got an expecting date
        $this->assertEquals(null, $family->expecting);


        // Add a prenant Family
        $pregnant_family = factory(Family::class)->create();

        $pregnancy = factory(Child::class, 'unbornChild')->make();

        $pregnant_family->children()
            ->saveMany(
                collect([
                    $pregnancy,
                    factory(Child::class, 'underOne', 2)->make(),
                    factory(Child::class, 'underSchoolAge')->make(),
                    factory(Child::class, 'overSchoolAge')->make(),
                ])->flatten()
            );

        // Test we've not got an expecting date
        $this->assertEquals($pregnancy->dob, $pregnant_family->expecting);
    }


}
