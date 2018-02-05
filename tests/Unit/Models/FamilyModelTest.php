<?php

namespace Tests;

use App\Family;
use App\Carer;
use App\Child;
use App\Centre;
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
        $centre3 = factory(Centre::class)->create();

        $family1 = factory(Family::class)->create();
        $family2 = factory(Family::class)->create();
        $family3 = factory(Family::class)->create();
        $family4 = factory(Family::class)->create();
        $family5 = factory(Family::class)->create();
        $family6 = factory(Family::class)->create();

        // Generate the RVIDs
        // 1,1
        $family1->lockToCentre($centre1);
        $family1->save();
        // 1,2
        $family2->lockToCentre($centre1);
        $family2->save();

        // 2,1
        $family3->lockToCentre($centre2);
        $family3->save();

        // 1,3
        $family4->lockToCentre($centre1);
        $family4->save();

        // 2,2
        $family5->lockToCentre($centre2);
        $family5->save();

        // 3,1
        $family6->lockToCentre($centre3);
        $family6->save();

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

        // In Family4, sequence should be 1
        $this->seeInDatabase('families', [
            'id' => $family4->id,
            'initial_centre_id' => $centre1->id,
            'centre_sequence' => 3,
        ]);

        // In Family5, sequence should be 2
        $this->seeInDatabase('families', [
            'id' => $family5->id,
            'initial_centre_id' => $centre2->id,
            'centre_sequence' => 2,
        ]);

        // In Family6, sequence should be 1
        $this->seeInDatabase('families', [
            'id' => $family6->id,
            'initial_centre_id' => $centre3->id,
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
        $family->lockToCentre($centre);
        $family->save();
        $family->fresh();

        // and matches the following
        $candidate = $centre->prefix . str_pad((string)$family->centre_sequence, 4, 0, STR_PAD_LEFT);

        $this->assertEquals($candidate, $family->rvid);
    }
}
