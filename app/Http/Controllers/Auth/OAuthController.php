<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        if (!$this->validateProvider($provider)) {
            return response()->json(['error' => 'Provider does not exist']);
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function providerCallback($provider): View
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            $user = User::firstOrNew(['email' => $socialUser->getEmail()]);

            if (!$user->exists) {
                $user->name = $socialUser->getName();
                $user->save();
            }

            Auth::login($user);

            return view('callback', ['status' => 'authenticated']);
        } catch (\Exception $e) {
            return view('callback', ['status' => 'canceled']);
        }
    }

    protected function validateProvider($provider): bool
    {
        $data = ['name' => $provider];
        $validator = Validator::make($data, [
            'name' => 'required|exists:providers,name'
        ]);

        return !$validator->fails();
    }
}
