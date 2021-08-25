<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create()->each(
            // Fonction fléchée PHP
            fn ($user) => $user->tweets()->saveMany(\App\Models\Tweet::factory(5)->make())
            // identique à :
            // function ($user){
            //     $user->tweets()->saveMany(\App\Models\Tweet::factory(5)->make());
            // }
        );
    }
}
