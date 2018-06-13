<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialAuthController extends Controller
{
    // Metodo encargado de la redireccion al proveedor
    public function redirectToProvider($provider)
    {

      $scopes = [];
      $scopesFacebook = ['user_gender'];
      $scopesGoogle = [];

      if (strtoupper($provider) == 'FACEBOOK'){
        $scopes = $scopesFacebook;
      }else if (strtoupper($provider) == 'GOOGLE') {
        $scopes = $scopesGoogle;
      }
        return Socialite::driver($provider)->scopes($scopes)->redirect();
    }


    // Metodo encargado de obtener la información del usuario
    public function handleProviderCallback($provider)
    {
        // Obtenemos los datos del usuario
        $social_user = Socialite::driver($provider)->stateless()->user();

        // Comprobamos si el usuario ya existe
        if ($user = User::where('email', $social_user->email)->first()) {
            return $this->authAndRedirect($user); // Login y redirección
        } else {
            // En caso de que no exista creamos un nuevo usuario con sus datos.
            $userGender = "";

            if(isset($social_user->user['gender'])){
              $userGender = $social_user->user['gender'];
            }

            $user = User::create([
                'name' => $social_user->name,
                'email' => $social_user->email,
                'provider' => $provider,
                'provider_id' => $social_user->getId(),
                'avatar' => $social_user->avatar,
                'gender' => $userGender,
            ]);

            return $this->authAndRedirect($user); // Login y redirección
        }
    }


    // Login y redirección
    public function authAndRedirect($user)
    {
        Auth::login($user);

        return redirect()->to('/home');
    }

}
