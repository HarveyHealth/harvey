<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(TestsTableSeeder::class);
        $this->call(AppointmentsSeeder::class);

        $this->command->getOutput()->writeln("Seeding Successful!");
        $this->command->getOutput()->writeln("");
        $this->command->getOutput()->writeln("<info>Admin Email: </info>admin@goharvey.co");
        $this->command->getOutput()->writeln("<info>Patient Email: </info> patient@goharvey.co");
        $this->command->getOutput()->writeln("<info>Practitioner Email: </info> practitioner@goharvey.co");
        $this->command->getOutput()->writeln("<info>All user passwords: </info> secret");
    }
}
