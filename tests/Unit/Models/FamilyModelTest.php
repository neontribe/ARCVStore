<?php

namespace Tests;

use App\Family;
use App\Carer;
use App\Child;
use App\Centre;
use App\Registration;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FamilyModelTest extends TestCase
{
    use DatabaseMigrations;

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
        $family = factory(Family::class)->create([]);

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

        // Add a pregnant Family
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

    /** @test */
    public function itCanGenreateAndSetAnRvidCorrectly()
    {
        // Set up some families and centres.
        $centre1 = factory(Centre::class)->create();
        $centre2 = factory(Centre::class)->create();

        $family1 = factory(Family::class)->create();
        $family2 = factory(Family::class)->create();
        $family3 = factory(Family::class)->create();

        // Generate the RVIDs
        $family1->generateRVID($centre1);
        $family1->save();

        $family2->generateRVID($centre1);
        $family2->save();

        $family3->generateRVID($centre2);
        $family3->save();

        // Check the fields have been set

        // In Family1, sequence should be 1
        $this->seeInDatabase('families', [
            'id' => $family1->id,
            'initial_centre_id' => $centre1->id,
            'centre_sequence' => 1,
        ]);

        // In Family2, sequence should be 2
        $this->seeInDatabase('families', [
            'id' => $family2->id,
            'initial_centre_id' => $centre1->id,
            'centre_sequence' => 2,
        ]);

        // In Family3, sequence should be 1
        $this->seeInDatabase('families', [
            'id' => $family3->id,
            'initial_centre_id' => $centre2->id,
            'centre_sequence' => 1,
        ]);
    }

    /** @test */
    public function itCanGetsARvidCorrectlyForGivenCentre()
    {
        $centre = factory(Centre::class)->create();
        $family = factory(Family::class)->create();

        // Check it returns "UNKNOWN" if an rvid hasn't been set.
        $this->assertEquals("UNKNOWN", $family->rvid);

        // Set the RVID
        $family->generateRVID($centre);

        // and matches the following
        $candidate = $centre->prefix . str_pad($family->centre_sequence, 4, 0, STR_PAD_LEFT);

        $this->assertEquals($candidate, $family->rvid);
    }
}
