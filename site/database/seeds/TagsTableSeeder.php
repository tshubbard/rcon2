<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            'id' => 1,
            'name' => 'say',
            'name_lower' => 'say',
            'slug' => 'say',
        ]);
        DB::table('tags')->insert([
            'id' => 2,
            'name' => 'giveTo',
            'name_lower' => 'giveto',
            'slug' => 'give-to',
        ]);
        DB::table('tags')->insert([
            'id' => 3,
            'name' => 'giveAll',
            'name_lower' => 'giveall',
            'slug' => 'give-all',
        ]);
        DB::table('tags')->insert([
            'id' => 4,
            'name' => 'kick',
            'name_lower' => 'kick',
            'slug' => 'kick',
        ]);
        DB::table('tags')->insert([
            'id' => 5,
            'name' => 'ban',
            'name_lower' => 'ban',
            'slug' => 'ban',
        ]);
    }
}
