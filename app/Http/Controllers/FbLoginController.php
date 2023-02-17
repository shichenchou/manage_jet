<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class FbLoginController extends Controller
{

    public function fbLogin(Request $request)
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function fbLoginCallback(Request $request)
    {
        $userInfo = Socialite::driver('facebook')->stateless()->user();
        $userInfo_data = $userInfo->user;
        $user_id = isset($userInfo_data['id']) ? (int)$userInfo_data['id'] : 0;
        $user_mail = isset($userInfo_data['email']) ? $userInfo_data['email'] : '';

        if (User::where('fb_id',  $user_id)->exists()) {

            Auth::guard('web')->login(User::where('fb_id', $user_id)->first());

        } else if (User::where('email', $user_mail)->exists()) {

            User::where('email', $user_mail)->update([
                'fb_id' => $user_id
            ]);

            Auth::guard('web')->login(User::where('email', $user_mail)->first());

        } else {
            $user_name = isset($userInfo_data['name']) ? $userInfo_data['name'] : '';

            User::create([
                'name' => $user_name,
                'email' => $user_mail,
                'password' => Hash::make(uniqid('FB_')),
                'fb_id' => $user_id
            ]);

            Auth::guard('web')->login(User::where('fb_id', $user_id)->first());
        }

        return redirect('/dashboard');
    }
}
