<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movie_groups')->insert(['name' => 'Movie Group One']);
        DB::table('movie_group_members')->insert([
            'user_id' => 1,
            'movie_group_id' => 1,
        ]);
    }
}
