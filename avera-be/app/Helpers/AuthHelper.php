<?php


namespace App\Helpers;

use Illuminate\Http\Request;

class AuthHelper {
    public static function user(Request $request)
    {
        return $request->attributes->get('identity');
    }
    public static function uuid(Request $request)
    {
        return self::user($request)['id'] ?? null;
    }
    public static function email(Request $request)
    {
        return self::user($request)['email'] ?? null;
    }
}