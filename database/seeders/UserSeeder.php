<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->create([
            'name' => 'NJ Dev',
            'email' => 'nathan@nathanjms.co.uk',
            'password' => bcrypt('admin')
        ]);
        User::factory(9)->create();
    }
}
