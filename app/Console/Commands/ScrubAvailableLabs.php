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
            $street_address_1 = $street_address_2 = $city = $state = $country = $zip = $email = $website = $phone_number = '';

            array_walk($line, 'trim');

            list($id, $branch, $categories, $mobile, $physical_address, $postal_address, $street_address_1, $street_address_2, $city, $state, $zip, $email, $website, $phone_number) = $line;

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
            $line_array[12] = 'xxx';
            $line_array[13] = $formatted_phone;

            $csv_scrubbed->addLine($line_array);
        }

        $csv_scrubbed->save();
    }
}
