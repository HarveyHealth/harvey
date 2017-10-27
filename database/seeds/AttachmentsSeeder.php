<?php

use Illuminate\Database\Seeder;
use App\Models\Attachment;

class AttachmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Attachment::class, 3)->create();
    }
}
