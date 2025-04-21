<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('members')->insert([
            'name' => 'john doe',
            'phone_number' => '0811223344556',
            'poin_member' => 100000,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
