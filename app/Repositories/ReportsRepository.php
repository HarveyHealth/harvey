<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Repository for all the reports queries
 */
class ReportsRepository extends BaseRepository
{
    private $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function billingInfo(Carbon $from_date, Carbon $to_date):array
    {
        // query database
        $query = DB::table('invoice_items')
            ->join('invoices','invoices.id','=','invoice_id')
            ->join('skus','invoice_items.sku_id','=','skus.id')
            ->join('patients','patients.id','=','patient_id')
            ->join('users','patients.user_id','=','users.id')
            ->leftJoin('discount_codes','invoices.discount_code_id','=','discount_codes.id')
            ->leftJoin('transactions','invoices.transaction_id','=','transactions.id')

            /* consultations */
            ->leftJoin('appointments', function ($join) {
                $join->on('appointments.invoice_id', '=', 'invoices.id');
                $join->on('skus.item_type', '=', DB::raw('"consultation"'));
            })
            ->leftJoin('practitioners as c_practitioners','c_practitioners.id','=','appointments.practitioner_id')
            ->leftJoin('users as c_users','c_practitioners.user_id','=','c_users.id')

            /* lab tests */
            ->leftJoin('lab_orders',function($join){
                $join->on('lab_orders.invoice_id','=','invoices.id');
                $join->on('skus.item_type', '=', DB::raw('"lab-test"'));
            })
            ->leftJoin('lab_tests',function($join){
                $join->on('skus.id', '=', 'lab_tests.sku_id');
                $join->on('lab_orders.id','=','lab_tests.lab_order_id');
            })
            ->leftJoin('lab_tests_information','skus.id','=','lab_tests_information.sku_id')
            ->leftJoin('practitioners as l_practitioners','l_practitioners.id','=','lab_orders.practitioner_id')
            ->leftJoin('users as l_users','l_practitioners.user_id','=','l_users.id')


            ->where('invoice_items.created_at','>=', $from_date)
            ->where('invoice_items.created_at','<=',$to_date)

            ->orderBy('invoices.id')

            ->select(

                'invoices.id as invoice_id',
                'invoices.transaction_id',
                'transactions.transaction_date',
                'users.id as client_id', //Client ID
                'users.first_name as client_first_name',//Client Name
                'users.last_name as client_last_name',//Client Name
                'users.created_at as client_signup_date',//Client Signup Date
                //Practitioner Name
                DB::raw("IF(c_users.first_name IS NULL, l_users.first_name, c_users.first_name) as practitioner_first_name"),
                DB::raw("IF(c_users.last_name IS NULL, l_users.last_name, c_users.last_name) as practitioner_last_name"),
                DB::raw("IF(c_users.id IS NULL, l_users.id, c_users.id) as practitioner_id"),
                'users.state as client_state', //Client State
                DB::raw("IF(c_users.state IS NULL, l_users.state, c_users.state) as practitioner_state"), // Practitioner State

                'skus.item_type',//Product Type (Consultation/Lab Test/Processing Fee)

                //If the line item is a Consultation
                DB::raw("(select min(appointment_at)
                        from appointments a
                        where a.patient_id = invoices.patient_id
                        and status_id = 5) = appointments.appointment_at as first_consultation"), //First Consultation (Y/N)

                'appointments.duration_in_minutes as consultation_duration',//Consultation Duration (30/60 min)
                DB::raw('(100/60) * appointments.duration_in_minutes as practitioner_cost'),//Practitioner Cost (100 usd per hour)
                //If the line item is a Lab Test, please also include:
                'skus.name as lab_test_name', //Lab Test Name
                'lab_tests_information.lab_name', //Lab Name
                DB::raw("(lab_tests_information.sample = 'Blood draw') as blood_draw"),//Blood Draw (Y/N)
                //If the line item is a Processing Fee, please also include:
                'skus.slug as processing_type',//Processing Type (Full/Partial)
                'skus.cost', // Lab Cost
                'invoice_items.amount as item_amount',//Consultation Price, Processing Total, Lab Test Price
                'discount_codes.code as discount_code', // Discount Code
                'invoices.discount', // Discount Amount
                'invoices.amount as total' // Invoice Total
            );

        $report = [];

        $current_invoice_id = NULL;
        $current_row = [];

        foreach ($query->cursor() as $item) {

            if ($item->invoice_id != $current_invoice_id){
                // adds the completed invoice to the report
                $report[] = $current_row;
                $current_invoice_id = $item->invoice_id;
                // switches to the new invoice
                $current_row = [
                    "Transaction ID" => "--",
                    "Transaction Date" => "--",
                    "Client Name" => NULL,
                    "Practitioner Name" => NULL,
                    "Client ID" => $item->client_id,
                    "Practitioner ID" => NULL,
                    "Client State" => $item->client_state,
                    "Practitioner State" => NULL,
                    "Product" => NULL,
                    "First Consultation" => "--",
                    "Consultation Duration" => "--", // (30/60 min)
                    "Consultation Price" => "--",
                    "Blood Draw" => "--",// (Y/N)
                    "Processing Type" => "--", //(Full/Partial)
                    "Lab Names" => "--",
                    "Lab Cost" => "--",
                    "Lab Total" => "--",
                    "Discount Code" => "--",
                    "Discount Amount" => "--",
                    "Total" => NULL
                ];
            }

            if (!empty($item->transaction_id)){
                $current_row["Transaction ID"] = $item->transaction_id;
                $current_row["Transaction Date"] = $item->transaction_date;
            }

            $current_row["Client Name"] = join(" ", [$item->client_first_name, $item->client_last_name]);

            if (!empty($item->practitioner_id)){
                $current_row["Practitioner Name"] = join(" ", [$item->practitioner_first_name, $item->practitioner_last_name]);
                $current_row["Practitioner ID"] = $item->practitioner_id;
                $current_row["Practitioner State"] = $item->practitioner_state;
            }

            $current_row["Total"] = $item->total;

            if (!empty($item->discount)){
                $current_row["Discount Code"] = $item->discount_code;
                $current_row["Discount Amount"] = $item->discount;
            }

            switch($item->item_type){
                case 'lab-test':
                    $current_row["Product"] = "Lab Order";
                    $current_row["Blood Draw"] = ($item->blood_draw)?'Yes':'No';
                    $current_row["Lab Names"] .= (empty($current_row["Lab Names"]))?"":";";
                    $current_row["Lab Names"] .=  $item->lab_test_name;
                    if (!is_int($current_row["Lab Total"])){
                        $current_row["Lab Total"] = 0;
                    }
                    $current_row["Lab Total"] += $item->item_amount;
                    if (!is_int($current_row["Lab Cost"])){
                        $current_row["Lab Cost"] = 0;
                    }
                    $current_row["Lab Cost"] += $item->cost;

                    break;
                case 'consultation':
                    $current_row["Product"] = "consultation";
                    $current_row["First Consultation"] = ($item->first_consultation)?'Yes':'No';
                    $current_row["Consultation Duration"] = $item->consultation_duration;
                    $current_row["Consultation Price"] = $item->item_amount;
                    break;
                case 'service-fee':
                    $current_row["Processing Type"] = ($item->processing_type == 'processing-fee-self')?'Partial':'Full';
                    break;
            }
        }

        return $report;
    }

}
