<?php

namespace App\Modules\Order\Contracts;

use App\Modules\Order\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReviewServiceInterface {
    public function get(int $perPage) : LengthAwarePaginator; 
    public function getAdmin(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?Review; 
    public function findBySlug(string $slug) : ?Review; 
    public function findByTrashed(string $id) : ?Review; 
    public function storeAdmin(array $data) : Review; 
    public function storeUser(array $data) : Review; 
    public function update(string $id,array $data) : Review; 
    public function delete(string $id) : bool; 
    public function deletePermanent(string $id) : bool; 
    public function restore(string $id) : bool; 
}