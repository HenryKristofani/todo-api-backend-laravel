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

        return TodoResource::collection($todos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $todo = Todo::create($validated);

        return new TodoResource($todo);
    }

    public function show($id)
    {
        $todo = Todo::findOrFail($id);

        return new TodoResource($todo);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'completed' => 'boolean'
        ]);

        $todo->update($validated);

        return new TodoResource($todo);
    }

    public function destroy($id)
    {
        Todo::findOrFail($id)->delete();

        return response()->json([
            "status" => "success",
            "message" => "Todo deleted"
        ]);
    }
}
