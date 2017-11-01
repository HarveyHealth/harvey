<?php

namespace App\Observers;

use App\Models\LabTestInformation;
use Cache;

class LabTestInformationObserver
{

    /**
     * Listen to the LabTestInformation saved event.
     *
     * @param  LabTestInformation $lab_test_information
     * @return void
     */
    public function saved(LabTestInformation $lab_test_information)
    {
        Cache::forget(LabTestInformation::PUBLIC_CACHE_KEY);
    }
}
