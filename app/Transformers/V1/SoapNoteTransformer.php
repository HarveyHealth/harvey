<?php

namespace App\Transformers\V1;

use App\Models\SoapNote;
use League\Fractal\TransformerAbstract;

class SoapNoteTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(SoapNote $soap_note)
    {
        if (currentUser()) {
            if (currentUser()->isPatient()) {
                return (new SoapNotePatientTransformer)->transform($soap_note);
            }
            if (currentUser()->isAdminOrPractitioner()) {
                return (new SoapNoteAdminOrPractitionerTransformer)->transform($soap_note);
            }
        }

        return [];
    }
}
