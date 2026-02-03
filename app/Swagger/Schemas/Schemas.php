<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

// ==================== SCHEMAS DE RECURSOS ====================

#[OA\Schema(
    schema: "UserResource",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "João Silva"),
        new OA\Property(property: "email", type: "string", example: "joao.silva@example.com"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time"),
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "AssociateResource",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "Jubileu Souza"),
        new OA\Property(property: "cpf", type: "string", example: "964.711.500-83"),
        new OA\Property(property: "email", type: "string", example: "jubisouza@email.com"),
        new OA\Property(property: "telephone", type: "string", example: "11999999999"),
        new OA\Property(property: "city", type: "string", example: "Rio Grande do Sul"),
        new OA\Property(property: "state", type: "string", example: "RS"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
    ],
    type: "object"
)]

// ==================== SCHEMAS DE REQUEST ====================

#[OA\Schema(
    schema: "RegisterRequest",
    required: ["name", "email", "password"],
    properties: [
        new OA\Property(
            property: "name",
            description: "Nome completo do usuário",
            type: "string",
            example: "João Silva"
        ),
        new OA\Property(
            property: "email",
            description: "Email do usuário",
            type: "string",
            format: "email",
            example: "joao.silva@example.com"
        ),
        new OA\Property(
            property: "password",
            description: "Senha do usuário (mínimo 6 caracteres)",
            type: "string",
            format: "password",
            example: "senha123"
        )
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "LoginRequest",
    required: ["email", "password"],
    properties: [
        new OA\Property(
            property: "email",
            description: "Email do usuário",
            type: "string",
            format: "email",
            example: "joao.silva@example.com"
        ),
        new OA\Property(
            property: "password",
            description: "Senha do usuário",
            type: "string",
            format: "password",
            example: "senha123"
        )
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "AssociateCreateRequest",
    required: ['name', 'email', 'cpf', 'telephone', 'city', 'state'],
    properties: [
        new OA\Property(
            property: 'name',
            type: 'string',
            example: 'Maria Souza'
        ),
        new OA\Property(
            property: 'email',
            type: 'string',
            format: 'email',
            example: 'maria@example.com'
        ),
        new OA\Property(
            property: 'cpf',
            type: 'string',
            example: '12345678900'
        ),
        new OA\Property(
            property: 'telephone',
            type: 'string',
            example: '964.711.500-83'
        ),
        new OA\Property(
            property: 'city',
            type: 'string',
            example: 'São Paulo'
        ),
        new OA\Property(
            property: 'state',
            description: 'Sigla de 2 letras',
            type: 'string',
            example: 'SP'
        )
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "AssociateUpdateRequest",
    properties: [
        new OA\Property(
            property: 'name',
            type: 'string',
            example: 'Maria Souza'
        ),
        new OA\Property(
            property: 'email',
            type: 'string',
            format: 'email',
            example: 'maria@example.com'
        ),
        new OA\Property(
            property: 'cpf',
            type: 'string',
            example: '12345678900'
        ),
        new OA\Property(
            property: 'telephone',
            type: 'string',
            example: '964.711.500-83'
        ),
        new OA\Property(
            property: 'city',
            type: 'string',
            example: 'São Paulo'
        ),
        new OA\Property(
            property: 'state',
            description: 'Sigla de 2 letras',
            type: 'string', example: 'SP'
        )
    ],
    type: "object"
)]

// ==================== SCHEMAS DE RESPONSE ====================

#[OA\Schema(
    schema: "RegisterResponse",
    properties: [
        new OA\Property(
            property: "token",
            description: "Token JWT de autenticação",
            type: "string",
            example: "eyJ0132sOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
        ),
        new OA\Property(
            property: "user",
            ref: "#/components/schemas/UserResource"
        )
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "LoginResponse",
    properties: [
        new OA\Property(
            property: "token",
            description: "Token JWT de autenticação",
            type: "string",
            example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
        ),
        new OA\Property(
            property: "expires_in",
            description: "Tempo de expiração do token em segundos",
            type: "integer",
            example: 3600
        )
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "MessageResponse",
    properties: [
        new OA\Property(
            property: "message",
            type: "string",
            example: "Successfully logged out"
        )
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "PaginationMeta",
    properties: [
        new OA\Property(
            property: "current_page",
            type: "integer",
            example: 1
        ),
        new OA\Property(
            property: "from",
            type: "integer",
            example: 1
        ),
        new OA\Property(
            property: "last_page",
            type: "integer",
            example: 5
        ),
        new OA\Property(
            property: "per_page",
            type: "integer",
            example: 10
        ),
        new OA\Property(
            property: "to",
            type: "integer",
            example: 10
        ),
        new OA\Property(
            property: "total",
            type: "integer",
            example: 50
        ),
    ],
    type: "object"
)]
#[OA\Schema(
    schema: "PaginationLinks",
    properties: [
        new OA\Property(
            property: "first",
            type: "string",
            example: "http://api...?page=1"
        ),
        new OA\Property(
            property: "last",
            type: "string",
            example: "http://api...?page=5"
        ),
        new OA\Property(
            property: "prev",
            type: "string",
            example: null,
            nullable: true
        ),
        new OA\Property(
            property: "next",
            type: "string",
            example: "http://api...?page=2",
            nullable: true
        ),
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "PaginatedResponse",
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/AssociateResource')
        ),
        new OA\Property(
            property: 'links',
            ref: '#/components/schemas/PaginationLinks'
        ),
        new OA\Property(
            property: 'meta',
            ref: '#/components/schemas/PaginationMeta'
        )
    ],
    type: "object"
)]

// ==================== SCHEMAS DE ERRO ====================

#[OA\Schema(
    schema: "ApiError",
    properties: [
        new OA\Property(property: "path", type: "string", example: "/api/auth/login"),
        new OA\Property(property: "method", type: "string", example: "POST"),
        new OA\Property(property: "status", type: "integer", example: 401),
        new OA\Property(property: "message", type: "string", example: "Invalid credentials!"),
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "ValidationError",
    properties: [
        new OA\Property(property: "path", type: "string", example: "/api/auth/register"),
        new OA\Property(property: "method", type: "string", example: "POST"),
        new OA\Property(property: "status", type: "integer", example: 422),
        new OA\Property(property: "message", type: "string", example: "Invalid input data."),
        new OA\Property(
            property: "errors",
            description: "Erros de validação por campo",
            type: "object",
            example: ["email" => ["The email has already been taken."]]
        ),
    ],
    type: "object"
)]

class Schemas {}
