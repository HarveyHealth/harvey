<?php

use Illuminate\Database\Seeder;

class OauthClientSeeder extends Seeder
{
    const SECRET = 'bHdnJqfTV7QtKC8JTifFenxBcCW4TlUVXQPk63In';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Laravel\Passport\Client::class)->create([
            'name' => 'Postman',
            'secret' => self::SECRET,
        ]);
    }
}
