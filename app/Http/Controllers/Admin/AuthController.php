<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
use App\Models\AdminFeature;
use App\Models\UserAdminFeature;

class AuthController extends Controller
{
    public function dashboard(){
        return view('dashboard');
    }

    public function login(){
        return view('guest.login');
    }

    public function loginPost(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all());
        }
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();

            // check if user level is for apps
            if ($user->level > 2)
                return redirect()->back()->withErrors("Credential Invalid #CI541");

            // get token
            $token = $user->createToken($user->email,['admin'])->accessToken;

            if ($token) {
                // get feature
                $features = [];
                if ($user->level == "1")
                    $features = AdminFeature::get()->toArray() ?? [];
                else{
                    $data_feature = UserAdminFeature::where('id_user', $user->id)->with('adminFeature')->get()->toArray() ?? [];
                    foreach ($data_feature as $key => $value) {
                        array_push($features, $value['admin_feature'][0] ?? null);
                    }
                }

                session([
                    'access_token'      => 'Bearer '.$token,
                    'name'              => $user->name,
                    'email'             => $user->email,
                    'level'             => $user->level,
                    'granted_features'  => array_column($features, 'key'),
                ]);

                return redirect()->route('admin.dashboard');
            }else
                return redirect()->back()->withErrors("Credential Invalid #CI541");
        }else
            return redirect()->back()->withErrors("Credential Invalid #CI543");

        return redirect()->back()->withErrors("Credential Invalid #CI544");
    }

    public function logout(Request $request) {
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }
}