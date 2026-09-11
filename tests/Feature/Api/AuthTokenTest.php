<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_agency_user_can_create_a_temporary_token(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/token', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'ERP Tests',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'expires_at'])
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('expires_in', 600);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'ERP Tests',
        ]);
    }

    public function test_device_name_is_required(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/auth/token', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('device_name')
            ->assertJsonPath('message', 'El nombre del dispositivo es obligatorio.');
    }

    public function test_invalid_credentials_return_unauthorized(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/auth/token', [
            'email' => $user->email,
            'password' => 'incorrecta',
            'device_name' => 'ERP Tests',
        ])->assertUnauthorized()->assertJsonValidationErrors('email');
    }
}
