<?php

use Illuminate\Database\Seeder;

class WakeUpCallsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'alvormar',
            'password' => bcrypt('aLv0RmAr!'),
        ]);
    }
}
