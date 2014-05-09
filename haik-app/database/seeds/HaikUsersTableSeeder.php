<?php

class HaikUsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('haik_users')->truncate();

        User::create(array(
                'name' => 'admin',
                'email' => 'user@example.com',
                'password' => Hash::make('hogehoge'),
        ));
        
    }
}