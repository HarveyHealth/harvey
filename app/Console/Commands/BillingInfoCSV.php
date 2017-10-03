<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\CSV;

use Illuminate\Support\Facades\DB;


use Carbon\Carbon;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CSV $csv)
    {
        parent::__construct();

        $this->csv = $csv;
    }

    private const TYPE_CONSULTATION = "Consultation";
    private const TYPE_TEST = "Lab Test";
    private const TYPE_FEE = "Processing Fee";

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

        // query database
        $query = DB::table('invoice_items')
            ->join('invoices','invoices.id','=','invoice_id')
            ->join('skus','invoice_items.sku_id','=','skus.id')
            ->join('patients','patients.id','=','patient_id')
            ->join('users','patients.user_id','=','users.id')
            /* consultations */
            ->leftJoin('appointments', function ($join) {
                $join->on('appointments.invoice_id', '=', 'invoices.id.user_id');
                $join->on('skus.item_type', '=', DB::raw('consultation'));
            })
            ->leftJoin('practitioners as c_practitioners','c_practitioners.id','=','appointments.practiotioner_id')
            ->leftJoin('users as c_users','c_practitioners.user_id','=','c_users.id')
            ->leftJoin('discount_codes as c_discount_codes','appointments.discount_code_id','=','c_discount_codes.id')
            /* lab tests */
            ->leftJoin('lab_orders',function($join){
                $join->on('lab_orders.invoice_id','=','invoices.id');
                $join->on('skus.item_type', '=', DB::raw('lab-test'));
            })
            ->leftJoin('lab_tests','lab_orders.id','=','lab_tests.lab_order_id')
            ->leftJoin('lab_tests_information','skus.id','=','lab_tests_information.sku_id')
            ->leftJoin('discount_codes','lab_orders.discount_code_id','=','discount_codes.id')

            ->whereDate('invoice_items.created_at','>=',$from_date)
            ->whereDate('invoice_items.created_at','=<',$to_date)

            ->select(
                'users.id', //Client ID
                'users.first_name',//Client Name
                'users.last_name',//Client Name
                'users.created_at',//Client Signup Date

                //Practitioner Name (Consultation)
                'c_users.first_name',//Client Name
                'c_users.last_name',//Client Name

                'users.state', //Client State
                'c_users.state', //Practitioner State (consultation)

                'invoices.transaction_id', //Transaction ID
                'skus.item_type',//Product Type (Consultation/Lab Test/Processing Fee)

                //If the line item is a Consultation, please also include:

                //First Consultation (Y/N)
                'appointments.duration_in_minutes',//Consultation Duration (30/60 min)
                //New to Integrated Medicine (Y/N)
                'skus.price',//Consultation Price
                DB::raw('(100/60) * appointments.duration_in_minutes'),//Practitioner Cost (100 usd per hour)
                'c_discount_codes.code',//Discount Code
                'c_discount_codes.discount_type',//Discount Total
                'c_discount_codes.amount',//Discount Total

                //If the line item is a Lab Test, please also include:
                'skus.name', //Lab Test Name
                'skus.price',//Lab Test Price
                //Lab Test Cost
                'lab_tests_information.lab_name', //Lab Name
                'lab_tests_information.sample',//Blood Draw (Y/N)
                'discount_codes.code',//Discount Code
                'discount_codes.discount_type',//Discount Total
                'discount_codes.amount',//Discount Total

                //If the line item is a Processing Fee, please also include:
                //Processing Type (Full/Partial)
                //Processing Total
            );


        // generate CSV File
    }
}
