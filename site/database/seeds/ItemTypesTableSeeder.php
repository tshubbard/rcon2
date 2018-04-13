<?php

use Illuminate\Database\Seeder;

class ItemTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = array(
            array('id' => 1,  'name' => 'Weapons'),
            array('id' => 2,  'name' => 'Ammo'),
            array('id' => 3,  'name' => 'Tools'),
            array('id' => 4,  'name' => 'Armor'),
            array('id' => 5,  'name' => 'Consumables'),
            array('id' => 6,  'name' => 'Traps'),
            array('id' => 7,  'name' => 'Usable'),
            array('id' => 8,  'name' => 'Deployable'),
            array('id' => 9,  'name' => 'Resource'),
            array('id' => 10,  'name' => 'Container'),
            array('id' => 11,  'name' => 'Mod'),
            array('id' => 20, 'name' => 'Misc'),
        );

        foreach ($items as $item) {
            DB::table('item_types')->insert([
                'id' => $item['id'],
                'name' => $item['name'],
            ]);
        }
    }
}
