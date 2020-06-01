<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Trần Đạt',
            'email' => 'trandat.hust@gmail.com',
            'password' => Hash::make(123456),
        ]);
    }
}
