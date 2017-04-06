<?php

use Illuminate\Database\Seeder;

class OauthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Laravel\Passport\Client::class)->create([
            'name' => 'Postman',
            'secret' => 'bHdnJqfTV7QtKC8JTifFenxBcCW4TlUVXQPk63In'
        ]);
    }
}
