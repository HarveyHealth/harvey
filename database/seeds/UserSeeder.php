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
        factory(App\Models\Admin::class)->create([
            'user_id' => factory(App\Models\User::class)->create([
                'email' => 'admin@goharvey.com',
                'phone' => '3101234567',
                'api_token' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
            ])->id
        ]);
    
        factory(App\Models\Patient::class)->create([
            'user_id' => factory(App\Models\User::class)->create([
                'email' => 'patient@goharvey.com',
                'phone' => '3101234568',
                'api_token' => 'baaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
            ])->id
        ]);
    
        factory(App\Models\Practitioner::class)->create([
            'user_id' => factory(App\Models\User::class)->create([
                'email' => 'practitioner@goharvey.com',
                'phone' => '3101234569',
                'api_token' => 'caaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
            ])->id
        ]);
    }
}
