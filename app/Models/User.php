<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "user";

//     public function create(Request $request)
//     {
//         $user = new User();
//         $user->username = $request->input('username');
//         return $user;
//     }

    public function __construct(Request $request, $token)
    {
        parent::__construct([]);
        $this->setAttribute('username', $request->input('username'));
        $this->setAttribute('password', $request->input('password'));
        $this->setRememberToken($token);
        //$this->accessToken = $token;
    }


    protected $fillable = [
        //'name',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'accessToken',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];
}
