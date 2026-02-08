<?php

namespace App\Modules\Location\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'rajaongkir_id',
        'city_id',
        'name'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
