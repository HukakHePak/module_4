<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class AuthorizationConroller extends Controller
{
    public function auth(Request $request)
    {
        $user = DB::table('user')->where('username', $request->input('username'));

        if($user->first()->password !== $request->input('password')) {
            return ['error' => 'invalid password'];
        }

        $token = $request->input('accessToken');//Str::random(60);
        $hashed = \hash('sha256', $token);

        $user->update(['api_token' => $hashed]);
        return ['token' => $token];
    }

    public function out(Request $request)
    {
        DB::table('user')->where('username', $request->user()->username)->update(['api_token' => '']);

        return ['status' => 'ok']; //redirect to success;
        //dd(Auth::user()->setRememberToken(null) );
    }
}
