<?php

namespace App\Modules\Store\Contracts;

use App\Modules\Store\Models\Store;
use Illuminate\Pagination\LengthAwarePaginator;

interface StoreRepositoryInterface {
    public function get(int $perPage) : LengthAwarePaginator; 
    public function getAdmin(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?Store; 
    public function findBySlug(string $slug) : ?Store; 
    public function findByTrashed(string $id) : ?Store; 
    public function store(array $data) : Store; 
    public function update(Store $store,array $data) : Store; 
    public function delete(Store $store) : bool; 
    public function deletePermanent(Store $store) : bool; 
    public function restore(Store $store) : bool; }