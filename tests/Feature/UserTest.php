<?php

namespace Tests\Feature;

use App\Models\{Admin, License, Patient, Practitioner, User};
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use ResponseCode;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_user_must_be_logged_in_order_to_view_their_own_account_information()
    {
        $user = factory(User::class)->create();
        $response = $this->json('GET', 'api/v1/users/' . $user->id);

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_a_user_can_view_their_own_account_information()
    {
        // Given a user
        $user = factory(User::class)->create();

        // With a type
        factory(Patient::class)->create(['user_id' => $user->id]);

        // When they access the show user endpoint
        Passport::actingAs($user);
        $response = $this->json('GET', 'api/v1/users/' . $user->id);

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And the user can see their own information
        $response->assertJsonFragment(['type' => 'user', 'id' => "{$user->id}"]);
    }

    public function test_a_user_can_modify_their_account_information()
    {
        // Given a user
        $user = factory(User::class)->create();

        // With a type
        factory(Patient::class)->create(['user_id' => $user->id]);

        // And user parameters
        $parameters = ['first_name' => 'ZZXXYY'];

        // When the user attempts updating their account with parameters
        Passport::actingAs($user);
        $response = $this->json('PATCH', 'api/v1/users/' . $user->id, $parameters);

        // Then it is successful
        $response->assertStatus(ResponseCode::HTTP_OK);

        // And we recognize a change has been made to the user
        $response->assertJsonFragment(['first_name' => 'ZZXXYY']);
    }

    public function test_a_patient_can_not_update_to_a_non_serviceable_zip()
    {
        $user = factory(User::class)->create();
        factory(Patient::class)->create(['user_id' => $user->id]);

        $parameters = ['zip' => 12345];

        Passport::actingAs($user);
        $response = $this->json('PATCH', 'api/v1/users/' . $user->id, $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $response->assertSee('Sorry, we do not service this zip.');
    }

    public function test_a_practitioner_can_update_to_a_non_serviceable_zip()
    {
        $user = factory(User::class)->create();
        factory(Practitioner::class)->create(['user_id' => $user->id]);

        $parameters = ['zip' => 12345];

        Passport::actingAs($user);
        $response = $this->json('PATCH', 'api/v1/users/' . $user->id, $parameters);

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonFragment(['zip' => '12345']);
    }

    public function test_a_user_can_not_update_another_user()
    {
        $user = factory(Patient::class)->create()->user;

        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->json('PATCH', 'api/v1/users/' . $user->id, []);

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_a_user_cannot_modify_guarded_properties_of_their_account()
    {
        $user = factory(User::class)->create();
        factory(Patient::class)->create(['user_id' => $user->id]);

        // Parameters with guarded property
        $parameters = ['enabled' => false];

        Passport::actingAs($user);
        $response = $this->json('PATCH', 'api/v1/users/' . $user->id, $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);

        // Property will remain unchanged
        $this->assertDatabaseHas('users', [
            'id' => "{$user->id}",
            'enabled' => true
        ]);
    }

    public function test_a_user_cannot_modify_another_users_account_information()
    {
        // Given user A and user B exist
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();

        // When user A tries to view user B data
        Passport::actingAs($userA);
        $response = $this->json('GET', 'api/v1/users/' . $userB->id);

        // Then it is not successful
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
        $response->assertSee('Unauthorized Access');
    }

    public function test_a_new_user_is_a_patient_by_default()
    {
        $faker = Faker::create();

        $parameters = [
            'first_name' => $faker->firstName,
            'last_name'=> $faker->lastName,
            'email' => $faker->email,
            'password' => $faker->password,
            'terms' => true,
            'zip' => 90401,
        ];

        factory(License::class)->create(['state' => 'CA']);

        $response = $this->json('POST', 'api/v1/users', $parameters);

        $response->assertStatus(ResponseCode::HTTP_CREATED);

        $newUserId = $response->decodeResponseJson()['data']['id'];
        $this->assertDatabaseHas('patients', ['user_id' => $newUserId]);
    }

    public function test_a_new_user_must_submit_a_password()
    {
        $faker = Faker::create();

        $parameters = [
            'first_name' => $faker->firstName,
            'last_name'=> $faker->lastName,
            'email' => $faker->email,
            'terms' => true,
            'zip' => 90401,
        ];

        $response = $this->json('POST', 'api/v1/users', $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $response->assertSee('The password field is required.');
    }

    public function test_admin_can_get_users()
    {
        $admin = factory(Admin::class)->create();
        Passport::actingAs($admin->user);

        $response = $this->json('GET', 'api/v1/users');
        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonFragment(['email' => $admin->user->email]);
    }

    public function test_admin_can_search_users()
    {
        factory(Patient::class)->create([
            'user_id' => factory(User::class)->create(['first_name' => 'Toronja'])->id
        ]);

        factory(Patient::class)->create([
            'user_id' => factory(User::class)->create(['first_name' => 'AnotherOne'])->id
        ]);

        Passport::actingAs(factory(Admin::class)->create()->user);

        $response = $this->json('GET', 'api/v1/users/?term=toronja');
        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(1, $response->original['data']);
        $response->assertJsonFragment(['first_name' => 'Toronja']);
    }

    public function test_admin_can_fuzzy_search_users_using_index()
    {
        factory(Patient::class)->create([
            'user_id' => factory(User::class)->create(['first_name' => 'Toronja'])->id
        ]);

        factory(Patient::class)->create([
            'user_id' => factory(User::class)->create(['first_name' => 'AnotherOne'])->id
        ]);

        Passport::actingAs(factory(Admin::class)->create()->user);

        foreach (['toronja', 'tornoja', 'toronaj'] as $searchTerm) {
            $response = $this->json('GET', "api/v1/users/?term={$searchTerm}&indexed=true");
            $response->assertStatus(ResponseCode::HTTP_OK);
            $this->assertCount(1, $response->original['data']);
            $response->assertJsonFragment(['first_name' => 'Toronja']);
        }
    }

    public function test_admin_can_filter_by_type_users()
    {
        factory(Patient::class, 3)->create();
        factory(Practitioner::class, 3)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);

        $response = $this->json('GET', 'api/v1/users/?type=patient');
        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(3, $response->original['data']);
    }

    public function test_unprivileged_user_is_not_allowed_to_search_users()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $response = $this->json('GET', 'api/v1/users');

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
        $response->assertSee('You are not authorized to access this resource.');
    }
}
