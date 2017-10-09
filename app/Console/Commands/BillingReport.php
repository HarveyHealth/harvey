<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\CSV;
use Carbon\Carbon;
use App\Repositories\ReportsRepository;

use Exception, File;

class BillingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:billing
                                {from_date : A date value in the past used to filter the purchased items}
                                {to_date=now : Optional. A date value higher than from_date. Default is now}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a CSV file listing every item purchased by date range';

    protected $csv;
    protected $reports;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CSV $csv, ReportsRepository $reports)
    {
        parent::__construct();

        $this->csv = $csv;
        $this->reports = $reports;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // retrieves input
        $from_date = new Carbon($this->argument('from_date'));
        $to_date = new Carbon($this->argument('to_date'));

        // query the db
        $report = $this->reports->billingInfo($from_date,$to_date);

        // set file path
        if (!File::isDirectory($path = storage_path() . '/billing_reports/')) {
            File::makeDirectory($path);
        }
        
        $file_path = storage_path() . '/billing_reports/billing_' . $from_date->toDateString() .
                    "_" . $to_date->toDateString() . ".csv";

        // generate CSV File
        $this->csv->setData($report, true);
        $this->csv->save($file_path);

        // output file path
        $this->info("Billing report finished successfully.");
        $this->info("File stored at: " . $file_path);
    }
}
