<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\Clients\Fullscript;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;

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
        $fullscript = \App::make(Fullscript::class);

        // find patient by external ref
        $patients = $fullscript->getPatients($this->user->id);

        if (!empty($patients)){
            $patient = $patients[0];
            // update
            $patient = $fullscript->updatePatient($patient['id'], [
                "first_name" => $this->user->first_name,
                "last_name" => $this->user->last_name,
                "email" => $this->user->email,
                "date_of_birth"=> date('Y-m-d',strtotime($this->user->patient->birthdate))
            ]);
        }


        return $patient;
    }
}
