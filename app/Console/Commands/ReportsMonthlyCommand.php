<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Lib\CSV;
use Carbon\Carbon;
use App\Repositories\ReportsRepository;
use \App\Models\User;
use \App\Models\Appointment;

class ReportsMonthlyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:monthly';

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
        $default_month = date('m', strtotime('-1 month'));
        $default_year = (date('m') == 1 ? date('Y', strtotime('-1 month')) : date('Y'));
        $month = $this->anticipate('month', [$default_month], $default_month);
        $year = $this->anticipate('year', [$default_year], $default_year);

        $start_date = new Carbon(date('Y-m-01 00:00:00', strtotime($year . "-{$month}-01")));
        $end_date = new Carbon($start_date);
        $end_date->addMonth(1)->subSecond();

        // get the 'count' data
        $this->info("Getting 'count' data...");
        $accounts_created =  $this->accountsCreatedWithinRange($start_date, $end_date);
        $appointments_created = $this->appointmentsCreatedWithinRange($start_date, $end_date);
        $appointments_scheduled = $this->appointmentsScheduledWithinRange($start_date, $end_date);

        // create the billing CSV
        $this->info('Building billing CSV...');
        $url = $this->createMonthlyBillingCSV($start_date, $end_date);

        $channel_array = [
            'layne' => 'D3MS0TKB5',
        ];

        $channels = array_values($channel_array);

        $alert_title = '';

        $this->info('Sending report info to Slack...');

        $info = '*_Report for ' . date('F, Y', strtotime("$year-$month-01")) . '_*';
        ops_info($alert_title, $info, $channels);

        $break = '*' . str_pad('',strlen($info) * 2,'=') . '*';
        $info = $break;
        ops_info($alert_title, $info, $channels);

        // number of accounts created
        $info = 'Number of accounts created: ' . $accounts_created;
        ops_info($alert_title, $info, $channels);

        // number of appointments created
        $info = 'Number of appointments created: ' . $appointments_created;
        ops_info($alert_title, $info, $channels);

        // number of appointments scheduled
        $info = 'Number of appointments scheduled: ' . $appointments_scheduled;
        ops_info($alert_title, $info, $channels);

        // posting report
        $info = 'Billing report for ' . $start_date->format('F, Y');
        ops_info($alert_title, $info, $channels);

        $info = 'Report URL: ' . $url;
        ops_info($alert_title, $info, $channels);

        $info = $break;
        ops_info($alert_title, $info, $channels);
    }

    /*
    a) Billing Query CSV for December 2017
     */

    private function accountsCreatedWithinRange($start_date, $end_date)
    {
        $this->info('Getting number of accounts created within range...');
        $count = User::where('created_at','>=',$start_date)
                        ->where('created_at','<=',$end_date)
                        ->count();

        return $count;
    }

    private function appointmentsCreatedWithinRange($start_date, $end_date)
    {
        $this->info('Getting number of appointments created within range...');
        $count = Appointment::where('created_at','>=',$start_date)
                        ->where('created_at','<=',$end_date)
                        ->count();

        return $count;
    }

    private function appointmentsScheduledWithinRange($start_date, $end_date)
    {
        $this->info('Getting number of appointments scheduled within range...');
        $count = Appointment::where('appointment_at','>=',$start_date)
                        ->where('appointment_at','<=',$end_date)
                        ->count();

        return $count;
    }

    private function createMonthlyBillingCSV($start_date, $end_date)
    {
        // create the filename
        $filename = sha1($start_date . ' to ' . $end_date . ' on ' . date('Y-m-d H:i:s')) . '.csv';

        // now make sure we have a folder to store this in
        if (!File::isDirectory($path = storage_path() . '/billing_reports/')) {
            $this->info('Making billing_reports folder...');
            File::makeDirectory($path);
        }

        // create the full filepath
        $file_path = storage_path() . '/billing_reports/' . $filename;

        // get the report data
        $this->info('Querying DB for billing info...');
        $report_data = $this->reports->billingInfo($start_date, $end_date);

        // generate CSV File
        $this->info('Generating CSV...');
        $this->csv->setData($report_data, true);

        // output file path
        $this->csv->save($file_path);
        $this->info('Billing report created successfully.');
        $this->info('Billing CSV stored locally at ' . $file_path);

        // upload to S3
        $this->info('Uploading to S3...');
        $now = \Carbon\Carbon::now();

        $metadata = [
            'visibility' => 'public',
            'Expires' => 'Expires, ' . $now->timezone('GMT')->addDays(7)->format('D, m M Y H:i:s T'),
            'ContentType' => 'text/csv',
        ];

        $s3_filename = 'billing-reports/billing_report.csv';

        $file = Storage::disk('s3')->getDriver()->put(
            $s3_filename,
            $this->csv->csvString(),
            $metadata
        );

        $url = Storage::disk('s3')->url($s3_filename);
        // send back the url so we know where the file is
        return $url;
    }
}
