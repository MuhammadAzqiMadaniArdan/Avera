<?php

namespace App\Modules\Banner\Repositories;

use App\Modules\Banner\Models\Banner;
use Illuminate\Pagination\LengthAwarePaginator;

interface BannerRepositoryInterface {
    public function get(int $perPage) : LengthAwarePaginator; 
    public function getAdmin(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?Banner; 
    public function findBySlug(string $slug) : ?Banner; 
    public function findByTrashed(string $id) : ?Banner; 
    public function store(array $data) : Banner; 
    public function update(Banner $banner,array $data) : Banner; 
    public function delete(Banner $banner) : bool; 
    public function deletePermanent(Banner $banner) : bool; 
    public function restore(Banner $banner) : bool; 
}