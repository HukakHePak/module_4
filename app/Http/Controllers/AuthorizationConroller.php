<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorizationConroller extends Controller
{
    public function auth(Request $request)
    {
        $user = DB::table('user')->where('username', $request->input('username'));

        if($user->first()->password !== $request->input('password')) {
            return \response()->json(['message' => 'Неверные учетные данные'], 400);
        }

        if($user->first()->api_token !== '') {
            return \response()->json(['message' => 'Пользователь уже аутентифицирован']);
        }

        $token = $request->input('accessToken');//Str::random(60);
        $hashed = \hash('sha256', $token);

        $user->update(['api_token' => $hashed]);

        return \response()->json(['token' => $token]);
    }

    public function out(Request $request)
    {
        DB::table('user')->where('username', $request->user()->username)->update(['api_token' => '']);

        return \response()->json(['message' => 'Успешный выход']);
    }
}
