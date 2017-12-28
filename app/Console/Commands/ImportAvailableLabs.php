<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\CSV;
use App\Models\AvailableLab;
use App\Lib\Clients\Geocoder;

class ImportAvailableLabs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'availablelabs:import {csv_url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports a CSV of available labs';

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
        $url = $this->argument('csv_url');

        $geocoder = new Geocoder;
        $lab_count = 1;

        $this->info('Reading in CSV from ' . $url . '...');

        $csv = new CSV($url);
        $csv->ignoreFirstLine(true);

        $dupes = [];
        $ungeocodable = [];

        foreach ($csv as $line) {
            list($lab_name, $iggbo, $phone, $address_1, $address_2, $city, $state, $zip, $latitude, $longitude) = $line;

            // make sure the zip has 5 digits
            $zip = str_pad($zip, 5, '0', STR_PAD_LEFT);

            // make sure this isn't already in the database
            $lab = AvailableLab::where('lab_name', $lab_name)
                    ->where('zip', $zip)
                    ->first();

            if ($lab) {
                $dupes[$zip] = true;
                $this->info('Lab already exists. Moving on...');
                continue;
            }

            // geocode the zip code
            $geo_data = $geocoder->geocode($zip . ', USA');

            if (empty(array_get($geo_data, 'location.latitude')) || empty(array_get($geo_data, 'address.city'))) {
                $ungeocodable[$zip] = true;
            }

            $this->info('Creating lab ' . $lab_count . '...');
            $lab_count++;

            $lab = new AvailableLab;
            $lab->lab_name = $lab_name;
            $lab->iggbo = (strtolower($iggbo) == 'true');
            $lab->phone = empty($phone) ? null : $phone;
            $lab->address_1 = array_get($geo_data, 'address.address_1');
            $lab->address_2 = array_get($geo_data, 'address.address_2');
            $lab->city = array_get($geo_data, 'address.city');
            $lab->state = array_get($geo_data, 'address.state');
            $lab->zip = array_get($geo_data, 'address.zip');
            $lab->latitude = array_get($geo_data, 'location.latitude');
            $lab->longitude = array_get($geo_data, 'location.longitude');
            $lab->save();
        }

        $this->info($lab_count . ' total labs created.');

        $this->info('Dupes: ' . implode(', ', array_keys($dupes)));
        $this->info('Ungeocodable: ' . implode(', ', array_keys($ungeocodable)));
    }
}
