<?php

use App\Models\User;
use function Pest\Laravel\{postJson, getJson, actingAs};

it('registers a user and returns the correct json structure', function () {
    $payload = [
        'name' => 'Gui Johann',
        'email' => 'gui@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = postJson('/api/auth/register', $payload);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'token',
            'user' => ['id', 'name', 'email']
        ]);

    $this->assertDatabaseHas('users', ['email' => 'gui@example.com']);
});

it('returns a formatted error when registration validation fails', function () {
    $response = postJson('/api/auth/register', []);

    $response->assertStatus(422)
        ->assertJson([
            'path' => 'api/auth/register',
            'method' => 'POST',
            'status' => 422,
            'message' => 'Invalid input data.',
        ])
        ->assertJsonStructure(['errors']);
});

it('logs in a user and returns a jwt token', function () {
    $password = 'secret123';

    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    $response = postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token', 'expires_in']);
});

it('returns 401 with custom format for invalid credentials', function () {
    $user = User::factory()->create();

    $response = postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password123',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'status' => 401,
            'message' => 'Invalid credentials! Please check your email and password.',
        ]);
});

it('returns 401 when trying to access protected route without token', function () {
    $response = getJson('/api/auth/user');

    $response->assertStatus(401)
        ->assertJson([
            'error' => 'Unauthorized',
        ]);
});

it('can retrieve the authenticated user profile', function () {
    $user = User::factory()->create();

    $token = JWTAuth::fromUser($user);

    $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/auth/user')
        ->assertStatus(200)
        ->assertJsonPath('email', $user->email);
});
