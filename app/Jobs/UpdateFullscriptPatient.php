<?php

namespace App\Jobs;

use App\Lib\Clients\Fullscript;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue};

class UpdateFullscriptPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if (!$this->user->isPatient()) return false;

        $fullscript = app()->make(Fullscript::class);

        $update = [];

        foreach (['first_name', 'last_name', 'email', 'date_of_birth' ] as $field){
            if ($field == 'date_of_birth' and $this->user->patient->isDirty('birthdate'))
            {
                $update[$field] = $this->user->patient->birthdate->format('Y-m-d');
            } elseif ($this->user->isDirty($field)) {
                $update[$field] = $this->user->$field;
            }
        }

        if (!empty($update) AND  !empty($patients = $fullscript->getPatients($this->user->id))) {
            return $fullscript->updatePatient($patients[0]->id, $update);
        }

        return false;
    }
}
