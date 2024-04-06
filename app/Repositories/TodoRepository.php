<?php

namespace App\Repositories;
use App\Interfaces\TodoRepositoryInterface;
use App\Models\Todo;

class TodoRepository implements TodoRepositoryInterface
{
    public function index() {
        return Todo::all();
    }

    public function getById($id) {
        return Todo::findOrFail($id);
    }

    public function store(array $todo) {
        return Todo::create($todo);
    }

    public function update(array $data, $id) {
        return Todo::findOrFail($id)->update($data);
    }

    public function destroy($id) {
        return Todo::destroy($id);
    }
}
