<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\Clients\Fullscript;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateFullscriptPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
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
        //
        $patient = false;

        $fullscript = \App::make(Fullscript::class);

        // find patient by external ref
        $patients = $fullscript->getPatients($this->user->id);

        if (!empty($patients)){
            $patient = $patients[0];
        }
        else{
            // find patient by email
            $patients = $fullscript->getPatients('', $this->user->email);

            if (!empty($patients)){
                $patient = $patients[0];
                // update patient with external ref
                $patient = $fullscript->updatePatient($patient['id'], [
                    'external_ref' => $this->user->id,
                ]);
            }
            else{
                // create patient
                $patient = $fullscript->createPatient([
                    "first_name" => $this->user->first_name,
                    "last_name" => $this->user->last_name,
                    "email" => $this->user->email,
                    "date_of_birth"=> date('Y-m-d',strtotime($this->user->patient->birthdate)),
                    "external_ref" => $this->user->id,
                ]);
            }
        }

        return $patient;
    }
}
