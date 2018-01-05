<?php

namespace App\Jobs;

use App\Lib\Clients\Fullscript;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class UpdateFullscriptPatient implements ShouldQueue
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
        $fullscript = app()->make(Fullscript::class);

        // find patient by external ref
        if (!empty($patients = $fullscript->getPatients($this->user->id))) {
            return $fullscript->updatePatient($patients[0]->id, [
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
                'date_of_birth'=> empty($this->user->patient->birthdate) ? null : $this->user->patient->birthdate->format('Y-m-d'),
            ]);
        }

        return false;
    }
}
