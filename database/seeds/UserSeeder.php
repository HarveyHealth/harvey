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
                'email' => 'admin@goharvey.com',
                'phone' => '3101234567',
                'api_token' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
            ]
        );

        factory(App\Models\User::class)->states('patient')->create(
            [
                'email' => 'patient@goharvey.com',
                'phone' => '3101234568',
                'api_token' => 'baaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
            ]
        );
        
        factory(App\Models\User::class)->states('practitioner')->create(
            [
                'email' => 'practitioner@goharvey.com',
                'phone' => '3101234569',
                'api_token' => 'caaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
            ]
        );
    }
}
