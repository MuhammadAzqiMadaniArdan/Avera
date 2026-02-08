<?php

namespace App\Modules\Category\Contracts;

use App\Modules\Category\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryServiceInterface {
    public function get() : LengthAwarePaginator; 
    public function getAdmin(array $filters) : LengthAwarePaginator; 
    public function getByTrashed(array $filters) : LengthAwarePaginator; 
    public function find(string $id) : ?Category; 
    public function findBySlug(string $slug) : ?Category; 
    public function findByTrashed(string $id) : ?Category; 
    public function storeAdmin(array $data) : Category; 
    public function storeUser(array $data) : Category; 
    public function update(string $id,array $data) : Category; 
    public function delete(string $id) : bool; 
    public function deletePermanent(string $id) : bool; 
    public function restore(string $id) : bool; 
}