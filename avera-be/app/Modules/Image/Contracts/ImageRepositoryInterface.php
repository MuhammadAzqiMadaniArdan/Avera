<?php

namespace App\Modules\Image\Contracts;

use App\Modules\Image\Models\Image;

interface ImageRepositoryInterface {
    public function store(array $data) : Image;
    public function find(string $id) : ?Image;
    public function findByHash(string $hash) : ?Image;
    public function update(Image $image,array $data) : Image;
}