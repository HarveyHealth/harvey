<?
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Repository for all the reports queries
 */
class ReportsRepository extends BaseRepository
{
    public $db;



    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function billingInfo(Carbon $from_date, Carbon $to_date)
    {
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
    }

}
