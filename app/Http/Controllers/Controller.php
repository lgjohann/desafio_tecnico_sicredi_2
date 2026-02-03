<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'Documentação da API de Autenticação e Associados',
    title: 'API Desafio Sicredi - Documentação',
    contact: new OA\Contact(
        email: 'guilherme@api.com'
    )
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Servidor Local'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    description: 'Insira o token JWT no formato: Bearer {seu-token}',
    name: 'Authorization',
    in: 'header',
    bearerFormat: 'JWT',
    scheme: 'bearer'
)]
#[OA\Tag(
    name: 'Authentication',
    description: 'Endpoints de autenticação'
)]
#[OA\Tag(
    name: 'Associates',
    description: 'Endpoints para gerenciamento de Associados'
)]
abstract class Controller
{
    //
}
