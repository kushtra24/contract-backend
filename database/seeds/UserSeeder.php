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
        $types = [
            ['name' => "Kushtrim", 'email' => 'info@kushtrim.net', 'email_verified_at' => '2020-05-01 19:06:51', 'password' => '$2y$12$oorGXcknpxRC1hYzZ/6/4ujiaw.Hp.oNidDHTinkfxGARmfhFVbU.'],
            ['name' => "Test User", 'email' => 'test@email.com', 'email_verified_at' => '2020-05-01 19:06:51', 'password' => '$2y$12$3TsGfd00KS/f5Y1FJYLAT.L.EBv0aSyDhRWVxnH9Yc.sfSitto2hS']
        ];

        DB::table('users')->insert($types);
    }
}
