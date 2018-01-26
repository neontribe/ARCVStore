<?php

namespace Tests;

use App\Centre;
use App\Registration;
use App\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use URL;

class DashboardPageTest extends TestCase
{
    use DatabaseMigrations;

    private $centre;
    private $user;
    private $registration;

    public function setUp()
    {
        parent::setUp();

        $this->centre = factory(Centre::class)->create();

        // Create a User
        $this->user =  factory(User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $this->centre->id,
        ]);

        // make centre some registrations
        $this->registration = factory(Registration::class)->create([
            "centre_id" => $this->centre->id,
        ]);
    }

    /** @test */
    public function itShowsTheExportButtonWhenUserCanExport()
    {
        // Create an FM User
        $fmuser =  factory(User::class)->create([
            "name"  => "FM test user",
            "email" => "testfmuser@example.com",
            "password" => bcrypt('test_fmuser_pass'),
            "centre_id" => $this->centre->id,
            "role" => "foodmatters_user",
        ]);

        // Create a CC user
        $ccuser =  factory(User::class)->create([
            "name"  => "CC test user",
            "email" => "testccuser@example.com",
            "password" => bcrypt('test_ccuser_pass'),
            "centre_id" => $this->centre->id,
            "role" => "centre_user",
        ]);

        $this->actingAs($ccuser)
            ->visit(URL::route('service.dashboard'))
            ->dontSee(URL::route('service.centres.registrations.summary'))
        ;

        Auth::logout();

        $this->actingAs($fmuser)
            ->visit(URL::route('service.dashboard'))
            ->see(URL::route('service.centres.registrations.summary'))
        ;
    }

    /** @test */
    public function itShowsTheLoggedInUserDetails()
    {
        $this->actingAs($this->user)
            ->visit(URL::route('service.registration.edit', [ 'id' => $this->registration->id ]))
            ->see($this->user->name)
            ->see($this->user->centre->name)
        ;
    }

}