<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TodoRequest;
use App\Http\Resources\V1\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return TodoResource::collection(Todo::where('user_id', $request->user()->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        return new TodoResource(Todo::create($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        if ($todo->user_id != request()->user()->id) {
            return response()->json([
                "status" => false,
                "message" => "you dont have permission",
            ], 403);
        }

        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        if ($todo->user_id != request()->user()->id) {
            return response()->json([
                "status" => false,
                "message" => "you dont have permission",
            ], 403);
        }

        $todo->update($request->all());

        return response()->json([
            "status" => true,
            "message" => "todo data with id " . $todo->id . " updated successfully",
            "todo" => new TodoResource($todo),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        if ($todo->user_id != request()->user()->id) {
            return response()->json([
                "status" => false,
                "message" => "you dont have permission",
            ], 403);
        }

        $todo->delete();
        return response()->json([], 204);
    }
}
