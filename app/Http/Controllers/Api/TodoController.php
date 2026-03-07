<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;


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

    public function show($id)
    {
        $todo = Todo::findOrFail($id);

        return $this->successResponse(
            new TodoResource($todo),
            'Todo retrieved successfully',
            200
        );
    }

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
