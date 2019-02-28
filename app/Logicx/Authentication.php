<?php
declare(strict_types=1);

namespace Autodrive\Logic;

class Authentication {

    public static function members($phone_number, $password) {

    }

    public static function process($request) {
        $credentials = request(['email', 'password']);
        $user = \Autodrive\User::find(1);
        if (! $token = auth()->claims([
            'aud' => 'admin'
        ])->setTTL(30)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($token, 200);
    }

}
