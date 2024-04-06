<?php

namespace App\Interfaces;
use App\Models\Todo;

interface TodoRepositoryInterface
{
    public function index();
    public function getById($id);
    public function store(array $data);
    public function update(array $data, $id);
    public function destroy($id);
}
