<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchPageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function itShowsTheLoggedInUser()
    {
        // Create some centres
        factory(App\Centre::class, 4)->create();

        // Create a User
        $user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => 1,
        ]);

        $this->actingAs($user)
            ->visit(URL::route('service.registration.index'))
            ->see($user->name)
        ;
    }

    /** @test */
    public function itShowsRegistrationsFromNeighborCentres()
    {
        // create a single Sponsor
        $sponsor = factory(App\Sponsor::class)->create();

        // create centres
        $centres = factory(App\Centre::class, 2)->create([
            "sponsor_id" => $sponsor->id,
        ]);

        $centre1 = $centres->first();
        $centre2 = $centres->last();

        // Create a User in Centre 1
        $user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $centre1->id,
        ]);

        // make centre1 some registrations
        $registrations1 = factory(App\Registration::class, 4)->create([
            "centre_id" => $centre1->id,
        ]);

        // Make centre2 some registrations
        $registrations2 = factory(App\Registration::class, 4)->create([
            "centre_id" => $centre2->id,
        ]);

        // visit the page
        $this->actingAs($user)
            ->visit(URL::route('service.registration.index'));

        $registrations = $registrations1->concat($registrations2);

        // check we can see the edit link with the registration ID in it.
        foreach ($registrations as $registration) {
            $edit_url_string = URL::route('service.registration.edit', [ 'id' => $registration->id]);
            $this->see($edit_url_string);
        }
    }


    /** @test */
    public function itShowsRegistrationsFromMyCentre()
    {
        // create a single Sponsor
        $sponsor = factory(App\Sponsor::class)->create();

        // create centre
        $centre = factory(App\Centre::class)->create([
            "sponsor_id" => $sponsor->id,
        ]);

        // Create a User in Centre
        $user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $centre->id,
        ]);

        // make centre some registrations
        $registrations = factory(App\Registration::class, 4)->create([
            "centre_id" => $centre->id,
        ]);

        $this->actingAs($user)
            ->visit(URL::route('service.registration.index'));

        // check we can see the edit link with the registration ID in it.
        foreach ($registrations as $registration) {
            $edit_url_string = URL::route('service.registration.edit', [ 'id' => $registration->id]);
            $this->see($edit_url_string);
        }
    }

    /** test */
    public function itDoesNotShowRegistrationsFromUnrelatedCentres()
    {
        // create a single Sponsor
        $sponsor = factory(App\Sponsor::class)->create();

        // create centres
        $neighbor_centres = factory(App\Centre::class, 2)->create([
            "sponsor_id" => $sponsor->id,
        ]);

        $alien_centre = factory(App\Centre::class, 2)->create([
            "sponsor_id" => factory(App\Sponsor::class)->create()->id,
        ]);

        $centre1 = $neighbor_centres->first();
        $centre2 = $neighbor_centres->last();

        // Create a User in Centre 1
        $user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $centre1->id,
        ]);

        // make centre1 some registrations
        factory(App\Registration::class, 4)->create([
            "centre_id" => $centre1->id,
        ]);

        // Make centre2 some registrations
        factory(App\Registration::class, 4)->create([
            "centre_id" => $centre2->id,
        ]);

        // Make alien_centre some registrations
        $registrations3 = factory(App\Registration::class, 4)->create([
            "centre_id" => $alien_centre->id,
        ]);

        $this->actingAs($user)
            ->visit(URL::route('service.registration.index'));

        // check we can see the edit link with the registration ID in it.
        foreach ($registrations3 as $registration) {
            $edit_url_string = URL::route('service.registration.edit', [ 'id' => $registration->id]);
            $this->dontSee($edit_url_string);
        }
    }

    /** @test */
    public function itShowsThePrimaryCarerName()
    {

        // Create a Centre (and, implicitly a random Sponsor)
        $centre = factory(App\Centre::class)->create();

        // Create a User
        $user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $centre->id,
        ]);

        // Create a random registration.
        $registration = factory(App\Registration::class)->create([
            "centre_id" => $centre->id,
        ]);

        //get the primary carer
        $pri_carer = $registration->family->carers->first();

        // Spot the Registration Family's primary carer name
        $this->actingAs($user)
            ->visit(URL::route('service.registration.index'))
            ->see($pri_carer->name);
    }

    /** @test */
    public function itShowsTheRVID()
    {
        // Create a Centre
        $centre = factory(App\Centre::class)->create();

        // Create a User
        $user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $centre->id,
        ]);

        // Create a random registration with our centre.
        $registration = factory(App\Registration::class)->create([
            "centre_id" => $centre->id,
        ]);

        // Spot the Registration family's RVID
        $this->actingAs($user)
            ->visit(URL::route('service.registration.index'))
            ->see($registration->family->rvid);
    }

    /** @test */
    public function itShowsTheVoucherEntitlement()
    {
        // Create a Centre
        $centre = factory(App\Centre::class)->create();

        // Create a User
        $user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $centre->id,
        ]);

        // Create a random registration with our centre.
        $registration = factory(App\Registration::class)->create([
            "centre_id" => $centre->id,
        ]);

        // Spot the Registration family's RVID
        $this->actingAs($user)
            ->visit(URL::route('service.registration.index'))
            ->see('<td class="center">' . $registration->family->entitlement . "</td>");
    }

    /** @test */
    public function itPaginatesWhenRequired()
    {
        // create a single Sponsor
        $sponsor = factory(App\Sponsor::class)->create();

        // create centre
        $centre = factory(App\Centre::class)->create([
            "sponsor_id" => $sponsor->id,
        ]);

        // Create a User in Centre
        $user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $centre->id,
        ]);

        // make centre some registrations
        $registrations = factory(App\Registration::class, 20)->create([
            "centre_id" => $centre->id,
        ]);

        // Visit search page, make sure next page link is present and works
        $this->actingAs($user)
            ->visit(URL::route('service.registration.index'))
            ->see('<a href="' . URL::route('service.base') . '/registration?page=2' . '" rel="next">»</a>')
            ->click('»')
            ->seePageIs(URL::route('service.base') . '/registration?page=2');
    }

    /** @test */
    public function itPreventsAccessToLeftFamilyRegistrations()
    {
    }

    /** @test */
    public function itShowsLeftFamilyRegistrationsAsDistinct()
    {
    }
}
