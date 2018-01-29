<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PractitionerCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'practitioner:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $first_name = $this->ask('First name?');
        $last_name = $this->ask('Last name?');
        $zip = $this->ask('Zip code?');
        $email = $this->ask('Email?');

        // geocode the zip code
        $this->info('Geocoding...');
        $geocoder = new \App\Lib\Clients\Geocoder;
        $geodata = $geocoder->geocode($zip);

        if (empty($geodata['location']['longitude'])) {
            $this->error('Could not geocode location.');
            return;
        }

        // get the timezone for the geocode
        $this->info('Determining timezone...');
        $tz = new \App\Lib\Clients\TimezoneDB;
        $zone = $tz->timezoneForLongitudeAndLatitude($geodata['location']['longitude'], $geodata['location']['latitude']);

        if (empty($zone)) {
            $this->error('Could not get timezone.');
            return;
        }

        $this->info('Creating user object...');
        $user = \App\Models\User::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'zip' => $zip,
            'email' => $email,
            'longitude' => $geodata['location']['longitude'],
            'latitude' => $geodata['location']['latitude'],
            'timezone' => $zone
        ]);

        $this->info('Creating practitioner object...');
        $practitioner = new \App\Models\Practitioner;
        $practitioner->user_id = $user->id;
        $practitioner->practitioner_type = 1;
        $practitioner->save();

        $user->sendVerificationEmail();
    }
}
