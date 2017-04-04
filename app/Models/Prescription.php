<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\HasPatientAndPractitioner;

class Prescription extends Model
{
    use HasPatientAndPractitioner;
}
