<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1 specific user in the first centre
        factory(App\User::class)->create([
            "name"  => "ARC CC User",
            "email" => "arc+ccuser@neontribe.co.uk",
            "password" => bcrypt('store_pass'),
            "centre_id" => 1,
            "role" => "centre_user",
        ]);

        factory(App\User::class)->create([
            "name"  => "ARC FM User",
            "email" => "arc+fmuser@neontribe.co.uk",
            "password" => bcrypt('store_pass'),
            "centre_id" => 1,
            "role" => "foodmatters_user",
        ]);

        // 1 faked user not associated with a random Centre
        factory(App\User::class)->create();

        // 3 faked users associated with random Centres
        factory(App\User::class, 3)->states('withRandomCentre')->create();
    }
}
