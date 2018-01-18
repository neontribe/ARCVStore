<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationPageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function iCanRegisterAFamily()
    {
        // // Create a centre
        // factory(App\Centre::class)->create();

        // // Create a User
        // $user =  factory(App\User::class)->create([
        //     "name"  => "test user",
        //     "email" => "testuser@example.com",
        //     "password" => bcrypt('test_user_pass'),
        //     "centre_id" => 1,
        // ]);

        // // Fill out minimum on family page and submit
        // $this->actingAs($user)
        //     ->visit(URL::route('service.registration.create'))
        //     ->type('Test Main Carer', '#carer')
        //     ->press('Save Family')
        //     ->see(URL::route('service.registration.edit'));

        //To do: POST registration to the DB, test seeInDatabase that it's there
    }

    /** @test */
    public function logoDoesntRedirectMeToDashboard()
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

        //Test that clicking on a (non)link throws an Error
        //and remains on the registration page.
        $this->actingAs($user)
            ->visit(URL::route('service.registration.create'))
            ->expectException(Error::class)
            ->click('logo')
            ->seePageIs(URL::route('service.registration.create'));
    }

    public function inputsClearAfterSubmit()
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
            ->visit(URL::route('service.registration.create'))
            ->type('Secondary Collector', '#carer_adder_input')
            ->press('#add_collector')
}
