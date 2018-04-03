<?php

use App\Centre;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ChangePasswordPageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function itCanResetAPasswordWithAValidLink()
    {
        // Invent a Centre for our user
        $centre = factory(Centre::class)->create();

        // Create a User
        $user =  factory(User::class)->create([
            "name"  => "test user",
            "email" => "testuser@example.com",
            "password" => bcrypt('test_user_pass'),
            "centre_id" => $centre->id,
        ]);

        // Create a token for testing.
        $token = '0123456789abcdef0123456789abcdef';

        // Create a password reset.
        DB::insert('INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)', [
            $user->email,
            $token,
            Carbon::now()->subMinutes(5)
        ]);

        // Has it saved the original password against the user?
        $this->assertTrue(Hash::check('test_user_pass', $user->password));

        print route('password.reset', [ 'token' => $token ]) . '?email=' . $user->email;

        // Se if the page exists.
        $this->visit(route('password.reset', [ 'token' => $token ]) . '?email=' . $user->email)
            ->see('Reset Password')
            ->type('mynewpassword', 'password')
            ->type('mynewpassword', 'password_confirmation')
            ->press('Reset Password')
            ->seePageIs(route('service.dashboard'))
        ;
        // Load the user again.
        $user->fresh();
        $this->assertTrue(Hash::check('mynewpassword', $user->password));
    }
}