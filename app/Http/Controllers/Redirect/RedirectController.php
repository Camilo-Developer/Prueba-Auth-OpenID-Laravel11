<?php

namespace App\Http\Controllers\Redirect;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RedirectController extends Controller
{
    public function azureLogin(Request $request)    {
        return Socialite::driver('azure')->redirect();
    }

    public function azureCallback()
    {
        $user = Socialite::driver('azure')->user();
        $userExists = User::where('external_id', $user->id)
            ->where('external_auth', 'azure')
            ->orWhere('email',  $user->email)
            ->first();
        if ($userExists) {
            Auth::login($userExists);
        } else {

            $userNew = User::create([
                'name' => $user->user['givenName'] ?? $user->user['displayName'],
                'email' => $user->email,
                'external_id' => $user->id,
                'external_auth' => 'azure',
                'avatar' => $user->avatar ?? NULL,
            ]);

            Auth::login($userNew);
        }
        return redirect()->route('dashboard');
    }

    public function logout(Request $request) 
    {
        Auth::guard()->logout();
        $request->session()->flush();
        $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));
        return redirect($azureLogoutUrl);
    }
}
