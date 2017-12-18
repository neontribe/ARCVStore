<?php

use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // fetch first user
        $user = Auth::loginUsingId(1);

        factory(App\Registration::class, 6)->create();

    }
}
