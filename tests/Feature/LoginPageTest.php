<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginPageTest extends TestCase
{

    use DatabaseMigrations;

    private $user = null;
    private $centre = null;

    public function seedForTests()
    {
        $this->centre = factory(App\Centre::class)->create();

        // Create a User
        $this->user =  factory(App\User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $this->centre->id,
        ]);
    }

    /** @test */
    public function itShowsALoginPageWhenRouted()
    {
        $this->seedForTests();

        $this->visit(URL::route('service.login'))
            ->assertResponseStatus(200)
            ->assertResponseOK()
            ->seeInElement('title', 'Login')
        ;
    }

    /** @test */
    public function itDoesNotShowTheLoggedInUserDetails()
    {
        $this->seedForTests();

        $this->visit(URL::route('service.login'))
            ->dontSee($this->user->name)
            ->dontSee($this->user->centre->name)
        ;
    }


    /** @test */
    public function itShowsAForgotPasswordLink()
    {
        $this->seedForTests();

        $this->visit(URL::route('service.login'))

        ;
    }

    /** @test */
    public function itShowsARememberMeInput()
    {
    }

    /** @test */
    public function itShowsAUsernameInputBox()
    {
    }

    /** @test */
    public function itShowsAPasswordInputBox()
    {
    }

    /** @test */
    public function itDoesNotShowTheAuthUserMasthead()
    {
    }

    /** @test */
    public function itLogsOutAuthedUsersWhoRouteHere()
    {
    }
}
