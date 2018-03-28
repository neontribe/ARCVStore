<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class RegistrationPageTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * @expectedException InvalidArgumentException
     * @test
     */
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
            ->click('logo')
            ->seePageIs(URL::route('service.registration.create'));
    }
}
