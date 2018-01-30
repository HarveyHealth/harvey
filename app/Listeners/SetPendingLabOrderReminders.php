<?php
namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\TransactionalEmail;
use App\Events\LabOrderRecommended;
use App\Jobs\SendLabOrderReminder;
use Carbon;

class SetPendingLabOrderReminders implements ShouldQueue
{
    public function handle(LabOrderRecommended $event)
    {
        $lab_order = $event->lab_order;
        // 3 days
        dispatch((new SendLabOrderReminder($lab_order))->delay(Carbon::now()->addDays(3)));
        // 2 days after that
        dispatch((new SendLabOrderReminder($lab_order))->delay(Carbon::now()->addDays(5)));
        // other two days more
        dispatch((new SendLabOrderReminder($lab_order))->delay(Carbon::now()->addDays(7)));
    }
}
