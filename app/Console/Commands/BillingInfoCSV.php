<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\CSV;
use Carbon\Carbon;
use App\Repositories\ReportsRepository;


class BillingInfoCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:billing
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
     * returns an empty well-formatted report row
     */
    private function getRow()
    {
        return [
            "Client ID"=>"",
            "Client Name"=>"",
            "Client Signup Date"=>"",
            "Practitioner Name"=>"",
            "Client State"=>"",
            "Practitioner State"=>"",
            "Transaction ID"=>"",
            "Product Type"=>"", // (Consultation/Lab Test/Processing Fee)
            //If the line item is a Consultation
            "First Consultation"=>"",// (Y/N)
            "Consultation Duration"=>"", // (30/60 min)
            "New to Integrated Medicine"=>"",// (Y/N)
            "Consultation Price"=>"",
            "Practitioner Cost"=>"",
            "Discount Code"=>"",
            "Discount Total"=>"",
            // If the line item is a Lab Test,
            "Lab Test Name"=>"",
            "Lab Test Price"=>"",
            "Lab Test Cost"=>"",
            "Lab Name"=>"",
            "Blood Draw"=>"",// (Y/N)
            "Discount Code"=>"",
            "Discount Total"=>"",
            // If the line item is a Processing Fee
            "Processing Type"=>"", //(Full/Partial)
            "Processing Total"=>"",
        ];
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
        $report = $reports->billingInfo($from_date,$to_date);


        // set file path
        $filepath = "";

        // generate CSV File
        $this->csv->setData($report, true);
        $this->csv->save($filepath);

        // output file path
    }
}
