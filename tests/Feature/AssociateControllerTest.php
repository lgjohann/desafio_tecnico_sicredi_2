<?php

use App\Models\Associate;
use App\Models\User;
use App\Services\AssociateService;
use App\Exceptions\NotFoundException\AssociateNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery\MockInterface;

beforeEach(function () {
    $this->user = User::factory()->create();
    $token = JWTAuth::fromUser($this->user);
    $this->withToken($token);
});

it('creates an associate and returns 201', function () {
    $payload = [
        'name' => "Joana D'arc",
        'email' => 'joana@example.com',
        'cpf' => '455.004.850-67',
        'telephone' => '11999999999',
        'city' => 'Rio de Janeiro',
        'state' => 'RJ',
    ];

    // Mock do Service
    $this->mock(AssociateService::class, function (MockInterface $mock) use ($payload) {
        $mock->shouldReceive('create')
            ->once()
            ->with(
                Mockery::on(
                    function ($arg) use ($payload) {
                return $arg['email'] === $payload['email'];
            })) // usa o payload como arg na função mockada do service
            ->andReturn(new Associate($payload));
    });

    $this->postJson('/api/associates', $payload)
        ->assertStatus(201)
        ->assertJsonPath('name', "Joana D'arc");
});

it('fails validation on create with invalid data', function () {
    $this->postJson('/api/associates', ['name' => ''])
    ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'cpf', 'telephone', 'city', 'state']);
});

it('lists associates with pagination structure', function () {
    $this->mock(AssociateService::class, function (MockInterface $mock) {
        $associates = new LengthAwarePaginator([], 0, 10);

        $mock->shouldReceive('getAll')
            ->once()
            ->andReturn($associates);
    });

    $this->getJson('/api/associates')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
});

it('updates an associate successfully', function () {
    $associate = Associate::factory()->create();
    $payload = [
        'name' => 'Updated Name',
        'email' => $associate->email,
        'cpf' => $associate->cpf,
        'telephone' => '11999999999',
        'city' => 'New City',
        'state' => 'SP'
    ];

    $this->mock(AssociateService::class, function (MockInterface $mock) use ($payload) {
        $mock->shouldReceive('update')
            ->once()
            ->andReturn(new Associate($payload));
    });

    $this->putJson("/api/associates/{$associate->id}", $payload)
        ->assertStatus(200)
        ->assertJsonPath('name', 'Updated Name');
});

it('returns 404 when updating non-existent associate', function () {
    $payload = [
        'name' => 'inexiste',
        'email' => 'inexiste@email.com',
        'cpf' => '455.004.850-67',
        'telephone' => '11999999999',
        'city' => 'inexiste',
        'state' => 'SP'
    ];

    $this->mock(AssociateService::class, function (MockInterface $mock) {
        $mock->shouldReceive('update')
            ->once()
            ->andThrow(new AssociateNotFoundException('Associate not found'));
    });

    $this->putJson('/api/associates/999', $payload)
        ->assertStatus(404)
        ->assertJson([
            'status' => 404,
            'message' => 'Associate not found'
        ]);
});

it('deletes an associate successfully', function () {
    $associate = Associate::factory()->create();

    $this->mock(AssociateService::class, function (MockInterface $mock) use ($associate) {
        $mock->shouldReceive('delete')
            ->once()
            ->with($associate->id);
    });

    $this->deleteJson("/api/associates/{$associate->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'Associate deleted successfully']);
});

it('returns 404 when deleting non-existent associate', function () {
    $this->mock(AssociateService::class, function (MockInterface $mock) {
        $mock->shouldReceive('delete')
            ->once()
            ->andThrow(new AssociateNotFoundException('Associate not found'));
    });

    $this->deleteJson('/api/associates/999')
        ->assertStatus(404);
});
