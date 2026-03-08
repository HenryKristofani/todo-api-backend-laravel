<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TodoQueryController extends Controller
{
    #[OA\Get(
        path: '/api/v1/todos',
        tags: ['Todos'],
        summary: 'Get todos with pagination and filters',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1), example: 1),
            new OA\Parameter(name: 'completed', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['true', 'false', '1', '0']), example: 'true'),
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'), example: 'meeting'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Todos retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function index(Request $request)
    {
        $validated = $request->validate([
            'page' => 'nullable|integer|min:1',
            'completed' => 'nullable|in:true,false,1,0',
            'search' => 'nullable|string|max:255',
        ]);

        $query = Todo::query();

        if (array_key_exists('completed', $validated)) {
            $completed = in_array((string) $validated['completed'], ['true', '1'], true);
            $query->where('completed', $completed);
        }

        if (!empty($validated['search'])) {
            $query->where('title', 'like', '%' . $validated['search'] . '%');
        }

        $todos = $query->latest()->paginate(10)->withQueryString();

        $items = collect($todos->items())->map(function (Todo $todo) use ($request) {
            return (new TodoResource($todo))->toArray($request);
        });

        return response()->json([
            'success' => true,
            'message' => 'Todos retrieved successfully',
            'data' => [
                'items' => $items,
                'pagination' => [
                    'current_page' => $todos->currentPage(),
                    'last_page' => $todos->lastPage(),
                    'per_page' => $todos->perPage(),
                    'total' => $todos->total(),
                ],
            ],
        ], 200);
    }
}
