<?php

namespace App\Models\Pitbull;

use Illuminate\Database\Eloquent\Model;

class PBRole extends Model
{
    protected $table = 'pb_roles';

    public function permissions()
    {

    }

    public function permissionsForRole(PBRole $role)
    {
        $permissions = [];
    }
}
