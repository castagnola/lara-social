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

    /**
     * Funcion inicial para inicial el proceso de acceso con Socialite
     * @Autor Fabio Castagnola
     * @param $service
     * @return mixed
     */
    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();


    }

    /**
     * EndPoint de vuelta para validar las credenciales del usuario y acceso a la aplicación
     * @Autor Fabio castagnola
     * @param $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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
