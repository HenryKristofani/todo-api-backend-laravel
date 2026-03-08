<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use OpenApi\Attributes as OA;


class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();

        return $this->successResponse(
            TodoResource::collection($todos),
            'Todos retrieved successfully',
            200
        );
    }

    #[OA\Post(
        path: '/api/v1/todos',
        tags: ['Todos'],
        summary: 'Create a todo',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Prepare sprint meeting'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Todo created successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $todo = Todo::create($validated);

        return $this->successResponse(
            new TodoResource($todo),
            'Todo created successfully',
            201
        );
    }

    #[OA\Get(
        path: '/api/v1/todos/{id}',
        tags: ['Todos'],
        summary: 'Get todo by id',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: '019cc9a0-26f9-7008-9025-b1bd546e1207'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Todo retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Resource not found'),
        ]
    )]
    public function show($id)
    {
        $todo = Todo::findOrFail($id);

        return $this->successResponse(
            new TodoResource($todo),
            'Todo retrieved successfully',
            200
        );
    }

    #[OA\Put(
        path: '/api/v1/todos/{id}',
        tags: ['Todos'],
        summary: 'Update todo by id',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: '019cc9a0-26f9-7008-9025-b1bd546e1207'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Prepare sprint retrospective'),
                    new OA\Property(property: 'completed', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Todo updated successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Resource not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'completed' => 'boolean'
        ]);

        $todo->update($validated);

        return $this->successResponse(
            new TodoResource($todo),
            'Todo updated successfully',
            200
        );
    }

    #[OA\Delete(
        path: '/api/v1/todos/{id}',
        tags: ['Todos'],
        summary: 'Delete todo by id',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: '019cc9a0-26f9-7008-9025-b1bd546e1207'),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Todo deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Resource not found'),
        ]
    )]
    public function destroy($id)
    {
        Todo::findOrFail($id)->delete();

        return response()->noContent();
    }

    private function successResponse(mixed $data, string $message, int $status)
    {
        if ($data instanceof TodoResource || $data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            return $data
                ->additional([
                    'success' => true,
                    'message' => $message,
                ])
                ->response()
                ->setStatusCode($status);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
