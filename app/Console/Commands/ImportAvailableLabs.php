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

        $this->info('Reading in CSV from ' . $url . '...');

        $csv = new CSV($url);
        $csv->ignoreFirstLine(false);

        $this->info(count($csv->numberOfLines()));

        $dupes = [];
        $ungeocodable = [];

        $lab_count = 0;

        foreach ($csv as $line) {


            list($lab_id, $lab_name, $lab_group, $mobile, $blah, $blah, $address_1, $address_2, $city, $state, $zip, $latitude, $longitude, $phone) = $line;

            // make sure the zip has 5 digits
            $zip = str_pad($zip, 5, '0', STR_PAD_LEFT);

            // make sure this isn't already in the database
            $lab = AvailableLab::where('lab_name', $lab_name)
                    ->where('zip', $zip)
                    ->where('address_1', $address_1)
                    ->first();

            if ($lab) {
                $dupes[$lab_name . '-' . $zip] = true;
                $this->info('Lab already exists. Moving on...');
                continue;
            }

            $this->info('Creating lab ' . $lab_count . '...');
            $lab_count++;

            $lab = new AvailableLab;
            $lab->lab_name = $lab_name;
            $lab->lab_group = $lab_group;
            $lab->lab_id = $lab_id;
            $lab->mobile = (strtolower($mobile) == 'true');
            $lab->phone = empty($phone) ? null : $phone;
            $lab->address_1 = $address_1;
            $lab->address_2 = $address_2;
            $lab->city = $city;
            $lab->state = $state;
            $lab->zip = $zip;
            $lab->latitude = $latitude;
            $lab->longitude = $longitude;

            $lab->save();
        }

        $this->info($lab_count . ' total labs created.');

        $this->info('Dupes: ' . implode(', ', array_keys($dupes)));
        $this->info('Ungeocodable: ' . implode(', ', array_keys($ungeocodable)));
    }
}
