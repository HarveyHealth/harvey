<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\HasPatientAndPractitioner;

class Test extends Model
{
    use HasPatientAndPractitioner;
    
    public function tempURL()
    {

    }
}
