<?php

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Services\UserService;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

beforeEach(function () {
    $this->userService = new UserService();
});

it('can register a user and return a token', function () {
    $data = [
        'name'     => 'fulaninho',
        'email'    => 'fulaninho@example.com',
        'password' => '12345678'
    ];

    $result = $this->userService->register($data);

    expect($result['user'])->toBeInstanceOf(User::class)
        ->and($result['token'])->toBeString()
        ->and($result['user']->email)->toBe($data['email']);

    $this->assertDatabaseHas('users', ['email' => 'fulaninho@example.com']);
});

it('can login a user and return a token', function () {
    $data = [
        'name'     => 'fulaninho',
        'email'    => 'fulaninho@example.com',
        'password' => '12345678'
    ];

    $this->userService->register($data);

    $credentials = [
        'email'    => 'fulaninho@example.com',
        'password' => '12345678'
    ];

    $result = $this->userService->authenticate($credentials);

    expect($result)->toBeString();
});

it('throws an exception with 401 code when credentials are invalid', function () {
    JWTAuth::shouldReceive('attempt')
        ->once()
        ->andReturn(false);

    expect(fn() => $this->userService->authenticate(['email' => 'test@test.com', 'password' => '1235678']))
        ->toThrow(InvalidCredentialsException::class, 'Invalid credentials!', 401);
});

it('invalidates token during logout', function () {
    JWTAuth::shouldReceive('getToken')->once()->andReturn('token_abc');
    JWTAuth::shouldReceive('invalidate')->once()->with('token_abc');

    $this->userService->logout();
});
