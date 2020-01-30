<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;


class SocialLoginController extends Controller


{
    protected $redirectTo = '/home';
//    public function __construct()
//    {
//       $this->middleware('social');
//    }

    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();


    }

    public function handleProviderCallback($service)
    {
        $userSocial = Socialite::driver($service)->stateless()->user();

        $findUser = User::where('email', $userSocial->email)->first();

        if ($findUser) {
            Auth::login($findUser, true);
            return redirect($this->redirectTo);
        } else {
            $user = new User;
            $user->name = $userSocial->name;
            $user->email = $userSocial->email;
            $user->password = bcrypt(123456);
            $user->save();
            Auth::login($findUser, true);

            return redirect($this->redirectTo);
        }


    }
}
