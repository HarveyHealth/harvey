<?php

namespace App\Lib;

use App\Lib\ZipCodeValidator;
use App\Models\LabTest;

class HarveyAvailability
{
    public static function get()
    {
        $states_collection = ZipCodeValidator::getStatesCollection();

        $consultations_serviceable_filter = function ($i) { return app(ZipCodeValidator::class)->isServiceable($i); };
        $consultations_serviceable_states = $states_collection->filter($consultations_serviceable_filter)->values();

        return [
            'consultations' => [
                'not_serviceable' => $states_collection->diff($consultations_serviceable_states)->values(),
                'serviceable' => $consultations_serviceable_states,
            ],
            'supplements' => [
                'not_serviceable' => [],
                'serviceable' => $states_collection,
            ],
            'lab_tests' => [
                'not_serviceable' => LabTest::getUnserviceableStatesCollection(),
                'require_consultation' => LabTest::getRequireConsultationStatesCollection(),
                'serviceable' => LabTest::getServiceableStatesCollection(),
            ],
        ];
    }
}
