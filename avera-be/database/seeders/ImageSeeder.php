<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('images')->insert([
            [
                'id' => Str::uuid(),
                'owner_type' => 'product',
                'owner_id' => null,
                'disk' => 'cloudinary',
                'path' => 'products/sample-1.jpg',
                'mime_type' => 'image/jpeg',
                'size' => 245678,
                'width' => 1200,
                'height' => 1200,
                'hash' => hash('sha256', 'sample-1'),
                'moderation_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'owner_type' => 'product',
                'owner_id' => null,
                'disk' => 'cloudinary',
                'path' => 'products/sample-2.jpg',
                'mime_type' => 'image/jpeg',
                'size' => 198765,
                'width' => 1200,
                'height' => 1200,
                'hash' => hash('sha256', 'sample-2'),
                'moderation_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
