<?php

use Illuminate\Database\Seeder;
use App\Models\{
    Appointment,
    Attachment,
    LabOrder,
    LabTest,
    Message,
    Patient,
    Prescription,
    SoapNote,
    User
};

class DatabaseSeeder extends Seeder
{
    const TESTING_INTAKE_TOKEN = 'b20ea4e0ae2d7504a8b78aacf1f963b6';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dont_observe = [
            Appointment::class,
            Attachment::class,
            LabOrder::class,
            LabTest::class,
            Message::class,
            Prescription::class,
            SoapNote::class,
            User::class,
        ];

        foreach ($dont_observe as $model) {
            $model::flushEventListeners();
        }

        if (app()->environment('staging', 'production')) {
            $this->call(InitialDatabaseSeeder::class);
        } else {
            $this->call(PractitionerTypesSeeder::class);
            $this->call(UserSeeder::class);

            $patient = Patient::first();
            $this->command->getOutput()->write("<info>Adding credit card to User ID #{$patient->user->id}...</info>");
            $patient->user->addCard('tok_us');
            $this->command->getOutput()->writeln(" Done.");

            $this->call(AppointmentsSeeder::class);
            $this->call(OauthClientSeeder::class);
            $this->call(LabOrdersSeeder::class);
            $this->call(MessagesSeeder::class);
            $this->call(DiscountCodesSeeder::class);
            $this->call(AttachmentsSeeder::class);
            $this->call(PrescriptionsSeeder::class);
            $this->call(SoapNotesSeeder::class);
            $this->call(RecordsSeeder::class);

            $patient = Patient::whereHas('attachments')
            ->whereHas('soapNotes')
            ->whereHas('prescriptions')
            ->whereHas('labOrders.labTests.results')
            ->whereNotNull('intake_token')
            ->orderBy('id', 'DESC')
            ->first();

            $this->command->getOutput()->writeln('Seeding Successful!');
            $this->command->getOutput()->writeln('');
            $this->command->getOutput()->writeln('<info>Admin Email:</info> admin@goharvey.com');
            $this->command->getOutput()->writeln('<info>Patient Email:</info> patient@goharvey.com');
            $this->command->getOutput()->writeln('<info>Practitioner Email:</info> practitioner@goharvey.com');
            $this->command->getOutput()->writeln('<info>All user passwords:</info> secret');
            $this->command->getOutput()->writeln('<info>Oauth Password Client Name:</info> Postman');
            $this->command->getOutput()->writeln('<info>Oauth Password Client ID:</info> #1');
            $this->command->getOutput()->writeln('<info>Oauth Password Client Secret:</info> ' . OauthClientSeeder::SECRET);

            if ($patient) {
                $this->command->getOutput()->writeln("<info>Patient ID seeded with Records:</info> #{$patient->id} [User ID #{$patient->user->id}]");
            }
        }
    }
}
