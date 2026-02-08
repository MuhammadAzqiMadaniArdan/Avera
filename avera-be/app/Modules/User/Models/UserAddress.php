<?php

namespace App\Modules\User\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\Province;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'recipient_phone',
        
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
        'other',

        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
