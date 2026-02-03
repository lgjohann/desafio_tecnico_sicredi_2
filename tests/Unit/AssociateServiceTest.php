<?php

use App\Models\Associate;
use App\Services\AssociateService;
use App\Exceptions\NotFoundException\AssociateNotFoundException; // Ajuste conforme seu namespace real
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    $this->service = new AssociateService();
});

it('can create a new associate', function () {
    $data = [
        'name' => 'joão silva',
        'email' => 'joão@example.com',
        'cpf' => '12345678900',
        'telephone' => '11999999999',
        'city' => 'São Paulo',
        'state' => 'SP',
    ];

    $associate = $this->service->create($data);

    expect($associate)->toBeInstanceOf(Associate::class)
        ->name->toBe('joão silva')
        ->email->toBe('joão@example.com');

    $this->assertDatabaseHas('associates', ['email' => 'joão@example.com']);
});

it('can retrieve paginated associates', function () {
    Associate::factory()->count(15)->create();

    $result = $this->service->getAll(10);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class)
        ->total()->toBe(15)
        ->count()->toBe(10); // 10 itens na primeira página
});

it('can update an existing associate', function () {
    $associate = Associate::factory()->create([
        'name' => 'Old Name',
        'city' => 'Old City'
    ]);

    $updateData = [
        'name' => 'New Name',
        'email' => $associate->email,
        'cpf' => $associate->cpf,
        'telephone' => '11988888888',
        'city' => 'New City',
        'state' => 'RJ',
    ];

    $updatedAssociate = $this->service->update($associate->id, $updateData);

    expect($updatedAssociate->name)->toBe('New Name')
        ->and($updatedAssociate->city)->toBe('New City');

    $this->assertDatabaseHas('associates', [
        'id' => $associate->id,
        'name' => 'New Name'
    ]);
});

it('throws exception when trying to update non-existent associate', function () {
    $data = [
        'name' => 'inexiste',
        'email' => 'inexiste@test.com',
        'cpf' => '00000000000',
        'telephone' => '00000000',
        'city' => 'inexiste',
        'state' => 'XX'
    ];

    expect(fn() => $this->service->update(999, $data))
        ->toThrow(AssociateNotFoundException::class);
});

it('can delete an associate', function () {
    $associate = Associate::factory()->create();

    $this->service->delete($associate->id);

    $this->assertDatabaseMissing('associates', ['id' => $associate->id]);
});

it('throws exception when trying to delete non-existent associate', function () {
    expect(fn() => $this->service->delete(999))
        ->toThrow(AssociateNotFoundException::class);
});
