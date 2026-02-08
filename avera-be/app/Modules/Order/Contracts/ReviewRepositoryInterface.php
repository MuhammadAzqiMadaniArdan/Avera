<?php

namespace App\Modules\Order\Contracts;

use App\Modules\Order\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface {
    public function get(int $perPage) : LengthAwarePaginator; 
    public function getAdmin(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?Review; 
    public function findBySlug(string $slug) : ?Review; 
    public function findByTrashed(string $id) : ?Review; 
    public function store(array $data) : Review; 
    public function update(Review $review,array $data) : Review; 
    public function delete(Review $review) : bool; 
    public function deletePermanent(Review $review) : bool; 
    public function restore(Review $review) : bool; 
}