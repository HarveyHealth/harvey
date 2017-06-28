<?php

namespace Tests\Unit;

use App\Models\{Practitioner, User};
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PractitionerScopeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_only_returns_practitioners_with_enabled_user()
    {
        $user = factory(User::class)->create();
        $practitioner = factory(Practitioner::class)->create(['user_id' => $user->id]);

        $this->assertCount(1, Practitioner::all());

        $user->enabled = false;
        $user->save();

        $this->assertEmpty(Practitioner::all());
    }
}
