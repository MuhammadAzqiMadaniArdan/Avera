<?php

namespace App\Modules\Category\Enum;

enum CategoryStatus : string {
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case REJECTED = 'rejected';

    public static function values() : array
    {
        return array_column(self::cases(),'value'); 
            // catatan : 
            // ambil semua data case dan jadikan sebagai array biasa = return = ['active','pending','rejected']
    }
}