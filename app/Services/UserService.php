<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserService
{
    /**
     * Register a new user and generate an initial JWT.
     *
     * @param array{name: string, email: string, password: string} $data validated user credentials.
     * @return array{user: User, token: string} the created user instance and the access token.
     *
     * @throws QueryException if there is a database persistence error.
     */
    public function register(array $data): array
    {
        $user = User::create($data);
        $token = JWTAuth::fromUser($user);

        Log::info('New user registered', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'ip'      => request()->ip()
        ]);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Authenticate a user and return a JWT access token.
     *
     * @param array{email: string, password: string} $credentials user login credentials.
     * @return string the generated JWT token.
     *
     * @throws Exception in the case of invalid credentials (401).
     */
    public function authenticate(array $credentials): string
    {
        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            Log::warning('Failed login attempt', ['email' => $credentials['email']]);
            throw new AuthenticationException('Invalid credentials! Please check your email and password.');
        }

        Log::info('JWT generated successfully for user', ['email' => $credentials['email']]);
        return $token;
    }

    /**
     * Logout the user, invalidating the JWT token by blacklisting.
     *
     * @return void
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}
