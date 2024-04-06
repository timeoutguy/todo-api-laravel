<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Interfaces\TodoRepositoryInterface;
use App\Models\Todo;
use DB;
use Log;

class TodoController extends Controller
{

    private TodoRepositoryInterface $todoRepositoryInterface;

    public function __construct(TodoRepositoryInterface $todoRepositoryInterface)
    {
        $this->todoRepositoryInterface = $todoRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->todoRepositoryInterface->index();

        return ApiResponseClass::sendResponses(TodoResource::collection($data), '', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreTodoRequest $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        $todo = [
            'title' => $request->title,
            'description'=> $request->description,
        ];

        DB::beginTransaction();
        try {
            $todo = $this->todoRepositoryInterface->store($todo);

            DB::commit();
            return ApiResponseClass::sendResponses(new TodoResource($todo), 'Todo created', 201);
        } catch(\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $todo = $this->todoRepositoryInterface->getById($id);

        return ApiResponseClass::sendResponses(new TodoResource($todo),'',200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UpdateTodoRequest $request, Todo $todo)
    {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, $id)
    {
        $updateTodo = [
            'title'=> $request->title,
            'description'=> $request->description,
            'completed'=> $request->completed,
        ];

        DB::beginTransaction();

        try {
            $updateTodo = $this->todoRepositoryInterface->update($updateTodo, $id);
            DB::commit();

            $todo = $this->todoRepositoryInterface->getById($id);
            return ApiResponseClass::sendResponses(new TodoResource($todo), '', 201);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->todoRepositoryInterface->destroy($id);
            DB::commit();

            return ApiResponseClass::sendResponses('Product delete successfully', '', 204);
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e);
        }
    }
}
