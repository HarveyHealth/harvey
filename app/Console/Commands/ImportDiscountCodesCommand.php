<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\CSV;
use App\Models\DiscountCode;
use App\Models\User;

class ImportDiscountCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discountcodes:import {filename}';

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
        $csv->ignoreFirstLine(true);

        foreach ($csv as $line) {
            list($code, $value, $type, $expiration, $applies_to, $one_time_use) = $line;

            $code = trim($code);

            $user = User::where('email','august@goharvey.com')->first();
            if (!$user) {
                $user = new User;
                $user->id = 1;
            }

            $discount_code = DiscountCode::withCode($code)->first();

            // if this discount code already exists...
            if ($discount_code) {
                $this->info('Code ' . $code . ' already exists.');
                continue;
            }

            // otherwise, add it to the db
            $discount_code = new DiscountCode;
            $discount_code->code = $code;
            $discount_code->user_id = $user->id;
            $discount_code->discount_type = ($type == '%' ? 'percent' : 'dollars');
            $discount_code->amount = trim($value, '%$ ');
            $discount_code->expires_at = date('Y-m-d 23:59:59', strtotime($expiration));
            $discount_code->one_time_use = ($one_time_use == 1 ? true : false);
            $discount_code->applies_to = $applies_to;
            $discount_code->save();
        }
    }
}
