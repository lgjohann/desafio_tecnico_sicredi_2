<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {}

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $this->service->register($request->validated());

        return response()->json([
            'token' => $data['token'],
            'user'  => new UserResource($data['user']),
        ], 201);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $token = $this->service->authenticate($request->validated());

        return response()->json([
            'token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ], 200);

    }
    public function logout(): JsonResponse
    {
        $this->service->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function getUser(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) throw new NotFoundHttpException("User not found");

        return response()->json(new UserResource($user), 200);
    }

}
