<?php

namespace App\Modules\Store\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\Province;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAddress extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'store_id',
        'store_name',
        'phone_number',
        
        'province_name',
        'city_name',
        'district_name',
        'village_name',
        
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        
        'postal_code',
        'address',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
