<?php

namespace App\Modules\Promotion\Repositories;

use App\Modules\Promotion\Models\Promotion;
use Illuminate\Pagination\LengthAwarePaginator;

interface CampaignRepositoryInterface {
        public function get(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?Promotion; 
    public function findByTrashed(string $id) : ?Promotion; 
    public function store(array $data) : Promotion; 
    public function update(Promotion $promotion,array $data) : Promotion; 
    public function delete(Promotion $promotion) : bool; 
    public function deletePermanent(Promotion $promotion) : bool; 
    public function restore(Promotion $promotion) : bool; 
}