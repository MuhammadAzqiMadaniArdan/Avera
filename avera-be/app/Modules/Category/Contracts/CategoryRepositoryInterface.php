<?php

namespace App\Modules\Category\Contracts;

use App\Modules\Category\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface {
    public function get() : LengthAwarePaginator; 
    public function getAdmin(array $filters) : LengthAwarePaginator; 
    public function getByTrashed(array $filters) : LengthAwarePaginator; 
    public function find(string $id) : ?Category; 
    public function findBySlug(string $slug) : ?Category; 
    public function findByTrashed(string $id) : ?Category; 
    public function store(array $data) : Category; 
    public function update(Category $category,array $data) : Category; 
    public function delete(Category $category) : bool; 
    public function deletePermanent(Category $category) : bool; 
    public function restore(Category $category) : bool; 
}