<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\HasPatientAndPractitioner;

class PatientNote extends Model
{
    use HasPatientAndPractitioner;
}
