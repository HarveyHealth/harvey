<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class)->states('admin')->create(
        	[
        		'email' => 'test@harvey.com',
        		'phone' => '3101234567'
        	]
        );
    }
}
