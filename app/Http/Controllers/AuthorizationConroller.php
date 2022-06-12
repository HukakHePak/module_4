<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthorizationConroller extends Controller
{
    public function auth(Request $request)
    {
        try {
            $login = $request->all();

            $table = DB::table('user')->where('username', $login['username']);
            $user = $table->first();

            if($user->password !== $login['password']) {
                return \response()->json(['message' => 'Неверные учетные данные'], 400);
            }

            if($user->api_token !== '') {
                return \response()->json(['message' => 'Пользователь уже аутентифицирован']);
            }

            $token = 'test-token';//Str::random(60);
            $hashed = \hash('sha256', $token);

            $table->update(['api_token' => $hashed]);

            return \response()->json(['token' => $token]);
        } catch(\Exception $e) {
            return \response()->json(['message' => 'Неверные данные'], 400);
        }
    }

    public function out(Request $request)
    {
        DB::table('user')->where('username', $request->user()->username)->update(['api_token' => '']);

        return \response()->json(['message' => 'Успешный выход']);
    }
}
