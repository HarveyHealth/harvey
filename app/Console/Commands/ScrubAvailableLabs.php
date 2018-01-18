<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\CSV;

class ScrubAvailableLabs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'availablelabs:scrub {filename}';

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
        $csv = new CSV($this->argument('filename'));

        $scrubbed_filename = str_replace('.csv','-scrubbed.csv',$this->argument('filename'));

        $this->info($scrubbed_filename);

        $csv_scrubbed = new CSV($scrubbed_filename);
        $csv->ignoreFirstLine(true);

        $x = 0;

        foreach($csv as $line) {

            $line_array = $line;

            $x++;

            // reset
            $street_address_1 = $street_address_2 = $city = $state = $country = $zip = $email = $website = $phone_number = $longitude = $latitude = '';

            array_walk($line, 'trim');

            list($id, $branch, $categories, $mobile, $physical_address, $postal_address, $street_address_1, $street_address_2, $city, $state, $zip, $latitude, $longitude, $email, $website, $phone_number) = $line;

            if (!empty($latitude)) {
                $csv_scrubbed->addLine($line_array);
                continue;
            }

            // break the address into pieces
            $address_array = explode(',', $physical_address);

            // state and zip
            if (!empty($address_array)) {
                $zip_etc = trim(array_pop($address_array));

                $zip_array = explode(' ', $zip_etc);
                array_walk($zip_array, 'trim');

                list($state, $country, $zip) = $zip_array;
            }

            // city
            if (!empty($address_array)) {
                $city = trim(array_pop($address_array));
            }

            // address 1 and address 2
            if (!empty($address_array)) {

                // address 1
                $street_address_1 = trim(array_shift($address_array));

                // address 2
                if (!empty($address_array)) {
                    $street_address_2 = trim(array_shift($address_array));
                }
            }

            $formatted_phone = preg_replace('/[^0-9]/', '', $phone_number);
            $this->info($x . ': ' . $formatted_phone);

            $line_array[6] = $street_address_1;
            $line_array[7] = $street_address_2;
            $line_array[8] = $city;
            $line_array[9] = $state;
            $line_array[10] = $zip;
            $line_array[13] = $formatted_phone;

            $pattern = '/\(.*\)/';

            // now try to geocode it
            $geocode_array = [];
            $strings = ['street_address_1','city','state','zip'];
            foreach ($strings as $part) {
                if (!empty($$part)) {
                    $the_part = preg_replace($pattern, '', $$part);
                    $the_part = trim($the_part);
                }
                    $geocode_array[] = $the_part;
            }
            $geocode_array[] = 'United States';

            $query = implode(',', $geocode_array);

            $location_string = exec("fnlocation $query");

            if (!empty($location_string)) {
                $location_parts = explode(': ', $location_string);
                if (count($location_parts) > 1) {
                    $this->info(json_encode($location_parts));
                    if (count($location_parts) > 1) {
                        $location_parts = explode(', ', $location_parts[1]);
                        if (count($location_parts) > 1) {
                            list($latitude, $longitude) = $location_parts;
                        }
                    }
                }

                sleep(5);
            }

            $line_array[11] = $latitude;
            $line_array[12] = $longitude;

            $csv_scrubbed->addLine($line_array);
        }

        $csv_scrubbed->save();
    }
}
