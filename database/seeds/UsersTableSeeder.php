<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'uuid' => '1f0a11a0-e608-11e7-9f59-3b497dd9e825',
            'name' => "Dandi Pangestu",
            'email' => 'dandipangestu96@gmail.com',
            'password' => bcrypt('secret'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }
}
