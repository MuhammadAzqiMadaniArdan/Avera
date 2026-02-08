<?php

namespace App\Modules\Location\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
     use HasFactory;

    protected $fillable = [
        'rajaongkir_id',
        'name'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
