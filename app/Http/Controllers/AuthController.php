<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException\UserNotFoundException;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {}

    #[OA\Post(
        path: '/api/auth/register',
        description: 'Cria um novo usuário no sistema',
        summary: 'Registrar novo usuário',
        tags: ['Authentication']
    )]
    #[OA\RequestBody(
        description: 'Dados do usuário para registro',
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequest')
    )]
    #[OA\Response(
        response: 201,
        description: 'Usuário registrado com sucesso',
        content: new OA\JsonContent(ref: '#/components/schemas/RegisterResponse')
    )]
    #[OA\Response(
        response: 422,
        description: 'Erro de validação',
        content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
    )]
    public function register(RegisterUserRequest $request): JsonResponse
    {
        Log::info('Trying to register a new user');
        $data = $this->service->register($request->validated());

        Log::info('User registered successfully');
        return response()->json([
            'token' => $data['token'],
            'user'  => new UserResource($data['user']),
        ], 201);
    }

    #[OA\Post(
        path: '/api/auth/login',
        description: 'Autentica um usuário e retorna um token JWT',
        summary: 'Fazer login',
        tags: ['Authentication']
    )]
    #[OA\RequestBody(
        description: 'Credenciais do usuário',
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest')
    )]
    #[OA\Response(
        response: 200,
        description: 'Login realizado com sucesso',
        content: new OA\JsonContent(ref: '#/components/schemas/LoginResponse')
    )]
    #[OA\Response(
        response: 401,
        description: 'Credenciais inválidas',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiError')
    )]
    #[OA\Response(
        response: 422,
        description: 'Erro de validação',
        content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
    )]
    public function login(LoginUserRequest $request): JsonResponse
    {
        Log::info('Trying to login user');
        $token = $this->service->authenticate($request->validated());

        Log::info('User logged in successfully');
        return response()->json([
            'token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ], 200);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        description: 'Invalida o token JWT do usuário autenticado',
        summary: 'Fazer logout',
        security: [['bearerAuth' => []]],
        tags: ['Authentication']
    )]
    #[OA\Response(
        response: 200,
        description: 'Logout realizado com sucesso',
        content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
    )]
    #[OA\Response(
        response: 401,
        description: 'Não autenticado',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiError')
    )]
    public function logout(): JsonResponse
    {
        Log::info('Trying to logout user');
        $this->service->logout();

        Log::info('User logged out successfully');
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    #[OA\Get(
        path: '/api/auth/user',
        description: 'Retorna os dados do usuário atualmente autenticado',
        summary: 'Obter usuário autenticado',
        security: [['bearerAuth' => []]],
        tags: ['Authentication']
    )]
    #[OA\Response(
        response: 200,
        description: 'Dados do usuário retornados com sucesso',
        content: new OA\JsonContent(ref: '#/components/schemas/UserResource')
    )]
    #[OA\Response(
        response: 401,
        description: 'Não autenticado',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiError')
    )]
    #[OA\Response(
        response: 404,
        description: 'Usuário não encontrado',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiError')
    )]
    public function getUser(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) throw new UserNotFoundException("User not found");

        return response()->json(new UserResource($user), 200);
    }
}
