<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // Show all todos
    public function index()
    {
        $todos = Todo::latest()->get();

        return response()->json($todos);
    }

    // Add a new todo
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = Todo::create([
            'title' => $request->title,
            'completed' => false,
        ]);

        return response()->json($todo, 201);
    }

    // Update todo
    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'completed' => 'sometimes|boolean',
        ]);

        $todo->update($request->only([
            'title',
            'completed',
        ]));

        return response()->json($todo);
    }

    // Delete todo
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully'
        ]);
    }
}