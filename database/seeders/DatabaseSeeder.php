<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        DB::table('users')->insert([
            'email'=>'derlisruizdiaz@hotmail.com',
            'username'=>'derlis',
            'password'=>Hash::make('decano'),
            'name'=>'Derlis',
            'active'=>1
        ]);
    }
}
