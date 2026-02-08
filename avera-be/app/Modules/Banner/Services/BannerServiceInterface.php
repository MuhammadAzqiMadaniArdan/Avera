<?php

namespace App\Modules\Banner\Services;

use App\Modules\Banner\Models\Banner;
use Illuminate\Pagination\LengthAwarePaginator;

interface BannerServiceInterface {
        public function get(int $perPage) : LengthAwarePaginator; 
    public function getAdmin(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?Banner; 
    public function findBySlug(string $slug) : ?Banner; 
    public function findByTrashed(string $id) : ?Banner; 
    public function storeAdmin(array $data) : Banner; 
    public function storeUser(array $data) : Banner; 
    public function update(string $id,array $data) : Banner; 
    public function delete(string $id) : bool; 
    public function deletePermanent(string $id) : bool; 
    public function restore(string $id) : bool; 
}