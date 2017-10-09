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
            /* consultations */
            ->leftJoin('appointments', function ($join) {
                $join->on('appointments.invoice_id', '=', 'invoices.id');
                $join->on('skus.item_type', '=', DB::raw('"consultation"'));
            })
            ->leftJoin('practitioners as c_practitioners','c_practitioners.id','=','appointments.practitioner_id')
            ->leftJoin('users as c_users','c_practitioners.user_id','=','c_users.id')
            ->leftJoin('discount_codes as c_discount_codes','appointments.discount_code_id','=','c_discount_codes.id')
            /* lab tests */
            ->leftJoin('lab_orders',function($join){
                $join->on('lab_orders.invoice_id','=','invoices.id');
                $join->on('skus.item_type', '=', DB::raw('"lab-test"'));
            })
            ->leftJoin('lab_tests','lab_orders.id','=','lab_tests.lab_order_id')
            ->leftJoin('lab_tests_information','skus.id','=','lab_tests_information.sku_id')
            ->leftJoin('practitioners as l_practitioners','l_practitioners.id','=','lab_orders.practitioner_id')
            ->leftJoin('users as l_users','l_practitioners.user_id','=','l_users.id')
            ->leftJoin('discount_codes','lab_orders.discount_code_id','=','discount_codes.id')

            ->where('invoice_items.created_at','>=', $from_date)
            ->where('invoice_items.created_at','<=',$to_date)

            ->select(
                'users.id as client_id', //Client ID
                'users.first_name as client_first_name',//Client Name
                'users.last_name as client_last_name',//Client Name
                'users.created_at as client_signup_date',//Client Signup Date
                //Practitioner Name
                DB::raw("IF(c_users.first_name IS NULL, l_users.first_name, c_users.first_name) as practitioner_first_name"),
                DB::raw("IF(c_users.last_name IS NULL, l_users.last_name, c_users.last_name) as practitioner_last_name"),
                'users.state as client_state', //Client State
                DB::raw("IF(c_users.state IS NULL, l_users.state, c_users.state) as practitioner_state"), // Practitioner State
                'invoices.transaction_id', //Transaction ID
                'skus.item_type',//Product Type (Consultation/Lab Test/Processing Fee)

                //If the line item is a Consultation, please also include:
                DB::raw("(select min(id)
                        from appointments a
                        where a.patient_id = invoices.patient_id) = appointments.id as first_consultation"), //First Consultation (Y/N)

                'appointments.duration_in_minutes as consultation_duration',//Consultation Duration (30/60 min)
                DB::raw('(100/60) * appointments.duration_in_minutes as practitioner_cost'),//Practitioner Cost (100 usd per hour)
                //If the line item is a Lab Test, please also include:
                'skus.name as lab_test_name', //Lab Test Name
                'lab_tests_information.lab_name', //Lab Name
                DB::raw("(lab_tests_information.sample = 'Blood draw') as blood_draw"),//Blood Draw (Y/N)
                //If the line item is a Processing Fee, please also include:
                'skus.slug as processing_type',//Processing Type (Full/Partial)
                'invoice_items.amount as item_amount',//Consultation Price, Processing Total, Lab Test Price
                DB::raw("IF(discount_codes.code IS NULL, c_discount_codes.code, discount_codes.code) as discount_code"), // //Discount Code
                DB::raw("IF(discount_codes.discount_type IS NULL, c_discount_codes.discount_type, discount_codes.discount_type) as discount_type"), //Discount Type
                DB::raw("IF(discount_codes.amount IS NULL, c_discount_codes.amount, discount_codes.amount) as discount_amount") // //Discount Total
            );

        $report = [];

        foreach ($query->get() as $item) {
            // generate values and apply some formatting
            $client_name = join(" ", [$item->client_first_name, $item->client_last_name]);
            $practitioner_name = join(" ", [$item->practitioner_first_name, $item->practitioner_last_name]);

            $product_type = ucfirst(str_replace("-"," ",$item->item_type));

            $discount_total = ($item->discount_type=='percent')? $item->item_amount * $item->discount_amount/100:$item->discount_amount;


            switch ($item->processing_type) {
                case 'processing-fee-self':
                    $processing_type = 'Full';
                    break;
                case 'shipping':
                    $processing_type = 'Partial';
                    break;
                default:
                    $processing_type = '';
                    break;
            }

            if ($item->consultation_duration){ // fill info if item is consultation
                $first_consultation = ($item->first_consultation)?'Y':'N';
            }
            else{
                $first_consultation = "";
            }

            if ($item->lab_name){
                $blood_draw = ($item->blood_draw)?'Y':'N';
            }
            else{
                $blood_draw = "";
            }


            $report[] = [
                "Client ID" => $item->client_id,
                "Client Name" => $client_name,
                "Client Signup Date" => new Carbon($item->client_signup_date),
                "Practitioner Name" => $practitioner_name,
                "Client State" => $item->client_state,
                "Practitioner State" => $item->practitioner_state,
                "Transaction ID" => $item->transaction_id,
                "Product Type" => $product_type,
                //If the line item is a Consultation
                "First Consultation" => $first_consultation,
                "Consultation Duration" => $item->consultation_duration, // (30/60 min)
                // If the line item is a Lab Test,
                "Lab Test Name" => $item->lab_test_name,
                "Lab Name" => $item->lab_name,
                "Blood Draw" => $blood_draw,// (Y/N)
                // If the line item is a Processing Fee
                "Processing Type" => $processing_type, //(Full/Partial)
                // general item amounts
                "Item Cost" => $item->practitioner_cost,
                "Item Price" => $item->item_amount,
                "Discount Code" => $item->discount_code,
                "Discount Total" => $discount_total
            ];
        }

        return $report;
    }

}
