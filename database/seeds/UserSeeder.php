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
        factory(App\Models\User::class)->states('admin')->create(
        	[
        		'email' => 'admin@harvey.co',
        		'phone' => '3101234567'
        	]
        );

        factory(App\Models\User::class)->states('patient')->create(
        	[
        		'email' => 'patient@harvey.co',
        		'phone' => '3101234568'
        	]
        );

        factory(App\Models\User::class)->states('practitioner')->create(
        	[
        		'email' => 'practitioner@harvey.co',
        		'phone' => '3101234569'
        	]
        );
    }
}
