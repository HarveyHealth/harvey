<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\{SoapNote, Attachment, Prescription, LabTestResult};
use Illuminate\Database\Eloquent\Model;

class SearchResultTransformer extends HarveyTransformer
{
    public function transform(Model $result)
    {
        switch (get_class($result)) {
            case SoapNote::class:
                $transformer = new SoapNoteTransformer;
                break;
            case Prescription::class:
                $transformer = new PrescriptionTransformer;
                break;
            case Attachment::class:
                $transformer = new AttachmentTransformer;
                break;
            case LabTestResult::class:
                $transformer = new LabTestResultTransformer;
                break;
            default:
                throw new Exception('Model ' . get_class($result) . ' not supported as search result');
        }

        return $transformer->transform($result) + ['resource_name' => $result->getTable()];
    }
}
