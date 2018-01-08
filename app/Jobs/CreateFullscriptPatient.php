<?php

namespace App\Jobs;

use App\Lib\Clients\Fullscript;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class CreateFullscriptPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        // find patient by external ref
        $patients = $fullscript->getPatients($this->user->id);

        if (!empty($patients)) {
            return $patients[0];
        }

        // find patient by email
        $patients = $fullscript->getPatients('', $this->user->email);

        if (!empty($patients)) {
            // update patient with external ref
            return $fullscript->updatePatient($patients[0]->id, [
                'external_ref' => $this->user->id,
            ]);
        }

        return $fullscript->createPatient([
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'external_ref' => $this->user->id,
        ]);
    }
}
