<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssociateCreateRequest;
use App\Http\Requests\AssociateUpdateRequest;
use App\Http\Resources\AssociateResource;
use App\Services\AssociateService;
use Illuminate\Http\JsonResponse;
use Log;
use OpenApi\Attributes as OA;

class AssociateController extends Controller
{
    public function __construct(
        protected AssociateService $service
    ) {}

    #[OA\Post(
        path: '/api/associates',
        summary: 'Cadastrar novo associado',
        security: [['bearerAuth' => []]],
        tags: ['Associates']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/AssociateCreateRequest')
    )]
    #[OA\Response(
        response: 201,
        description: 'Associado criado com sucesso',
        content: new OA\JsonContent(ref: '#/components/schemas/AssociateResource')
    )]
    #[OA\Response(
        response: 422,
        description: 'Erro de validação (CPF inválido, Email duplicado, etc)',
        content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
    )]
    public function createAssociate(AssociateCreateRequest $request): JsonResponse
    {
        Log::info("Trying to register a new Associate");
        $associate = $this->service->create($request->validated());

        Log::info('Associate registered successfully');
        return response()->json(new AssociateResource($associate), 201);
    }



    #[OA\Get(
        path: '/api/associates',
        summary: 'Listar associados (paginado)',
        security: [['bearerAuth' => []]],
        tags: ['Associates']
    )]
    #[OA\Response(
        response: 200,
        description: 'Lista paginada de associados',
        content: new OA\JsonContent(
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
            ]
        )
    )]
    public function getAssociates(): JsonResponse
    {
        Log::info("Trying to fetch a paginated list of Associates");
        $associates = $this->service->getAll();

        Log::info("Associates fetched successfully");
        return AssociateResource::collection($associates)->response();
    }



    #[OA\Put(
        path: '/api/associates/{id}',
        summary: 'Atualizar dados do associado',
        security: [['bearerAuth' => []]],
        tags: ['Associates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/AssociateUpdateRequest'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Associado atualizado com sucesso',
        content: new OA\JsonContent(ref: '#/components/schemas/AssociateResource')
    )]
    #[OA\Response(
        response: 404,
        description: 'Associado não encontrado',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiError')
    )]
    #[OA\Response(
        response: 422,
        description: 'Erro de validação (CPF inválido, Email duplicado, etc)',
        content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
    )]
    public function updateAssociate(AssociateUpdateRequest $request, int $id): JsonResponse
    {
        Log::info("Trying to update an Associate");
        $associate = $this->service->update($id, $request->validated());

        Log::info('Associate updated successfully');
        return response()->json(new AssociateResource($associate), 200);
    }




    #[OA\Delete(
        path: '/api/associates/{id}',
        summary: 'Deletar associado',
        security: [['bearerAuth' => []]],
        tags: ['Associates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Associado deletado com sucesso',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Associate deleted successfully')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Associado não encontrado',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiError')
    )]
    public function deleteAssociate(int $id): JsonResponse
    {
        Log::info("Trying to delete an Associate");
        $this->service->delete($id);

        Log::info('Associate deleted successfully');
        return response()->json(['message' => 'Associate deleted successfully'], 200);
    }
}
