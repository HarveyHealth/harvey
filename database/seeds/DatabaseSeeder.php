<?php

use Illuminate\Database\Seeder;
use App\Models\{Appointment, Attachment, User, LabTest, LabOrder, Message};

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([Appointment::class, User::class, LabTest::class, LabOrder::class, Message::class, Attachment::class] as $model) {
            $model::flushEventListeners();
        }

        if (app()->environment(['staging', 'production'])) {
            $this->call(InitialDatabaseSeeder::class);
        } else {
            $this->call(PractitionerTypesSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(AppointmentsSeeder::class);
            $this->call(OauthClientSeeder::class);
            $this->call(LabOrdersSeeder::class);
            $this->call(MessagesSeeder::class);
            $this->call(DiscountCodesSeeder::class);

            $this->command->getOutput()->writeln('Seeding Successful!');
            $this->command->getOutput()->writeln('');
            $this->command->getOutput()->writeln('<info>Admin Email:</info> admin@goharvey.com');
            $this->command->getOutput()->writeln('<info>Patient Email:</info> patient@goharvey.com');
            $this->command->getOutput()->writeln('<info>Practitioner Email:</info> practitioner@goharvey.com');
            $this->command->getOutput()->writeln('<info>All user passwords:</info> secret');
            $this->command->getOutput()->writeln('<info>Oauth Password Client Name:</info> Postman');
            $this->command->getOutput()->writeln('<info>Oauth Password Client ID:</info> 1');
            $this->command->getOutput()->writeln('<info>Oauth Password Client Secret:</info> ' . OauthClientSeeder::SECRET);
        }
    }
}
