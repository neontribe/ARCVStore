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
        $token = 'abcdefabcdefabcdef';

        // Create a password reset.
        // NOTE : the token is stored as a hash!
        DB::insert('INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)', [
            $user->email,
            bcrypt($token),
            Carbon::now(),
        ]);

        // Has it saved the original password against the user?
        $this->assertTrue(Hash::check('test_user_pass', $user->password));

        // Se if the page exists.
        $this->visit(route('password.reset', [ 'token' => $token ]))
            ->see('Reset Password')
            ->type($user->email, 'email')
            ->type('mynewpassword', 'password')
            ->type('mynewpassword', 'password_confirmation')
            ->press('Reset Password')
            ->seePageIs(route('service.dashboard'))
        ;
        // Load the user again.
        $user2 = User::find($user->id);

        $this->assertTrue(Hash::check('mynewpassword', $user2->password));
    }

    /** @test */
    public function itCannotResetAPasswordWithAnInvalidLink()
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
        $token = 'abcdef0123456789abcdef0123456789';

        // Create a password reset.
        DB::insert('INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)', [
            $user->email,
            bcrypt($token),
            Carbon::now()->subMinutes(5)
        ]);

        $res = DB::select(DB::raw("select * from password_resets limit 1"));

        // Has it saved the original password against the user?
        $this->assertTrue(Hash::check('test_user_pass', $user->password));

        // Se if the page exists.
        $this->visit(route('password.reset', [ 'token' => 'NotAHashedToken' ]))
            ->see('Reset Password')
            ->type($user->email, 'email')
            ->type('mynewpassword', 'password')
            ->type('mynewpassword', 'password_confirmation')
            ->press('Reset Password')
            ->see(trans('passwords.token'));
        ;
        // Load the user again.
        $user->fresh();
        $this->assertNotTrue(Hash::check('mynewpassword', $user->password));
    }
}