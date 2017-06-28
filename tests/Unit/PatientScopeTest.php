<?php

namespace Tests\Unit;

use App\Models\{Patient, User};
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PatientScopeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_only_returns_patients_with_enabled_user()
    {
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create(['user_id' => $user->id]);

        $this->assertCount(1, Patient::all());

        $user->enabled = false;
        $user->save();

        $this->assertEmpty(Patient::all());
    }
}
