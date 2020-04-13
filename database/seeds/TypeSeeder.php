<?php

use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => "NDA"],
            ['name' => "AGB"],
            ['name' => "PO"],
            ['name' => "Einzelvertrag"],
            ['name' => "Rahmenvertrag"],
            ['name' => "Sonstiges"],
        ];

        DB::table('types')->insert($types);
    }
}
