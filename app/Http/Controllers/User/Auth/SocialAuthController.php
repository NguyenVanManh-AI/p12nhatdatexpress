<?php

namespace App\Http\Controllers\User\Auth;

use App\CPU\InitUserAccount;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\UserDetail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{

    /**
     * Redirect to google or facebook app api to get user infor
     * @param String $provider
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function redirect($provider)
    {
        try {
            // facebook, google
            return Socialite::driver($provider)->stateless()->redirect();
            // return Socialite::driver($provider)->redirect();
        } catch(\InvalidArgumentException $e) {
            Toastr::error('Đăng nhập không thành công, vui lòng liên hệ Admin!');
            return redirect()->route('home.index');
        }
    }

    /**
     * Get user information from google or facebook response
     * @param String $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function social_login($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            // $socialUser = Socialite::driver($provider)->user();
        } catch(\InvalidArgumentException $e) {
            return redirect()->route('home.index');
        }

        $existUser = null;
        DB::beginTransaction();
        try {
            do {
                $usercode = rand(1000000000, 9999999999);
                $checkUserCode = User::where('user_code', $usercode)->first();

            } while ($checkUserCode);

            if ($provider == 'facebook') {
                $existUser = User::select('user.*')
                    ->join('user_detail', 'user.id', '=', 'user_detail.user_id')
                    ->where('facebook_id', $socialUser->id)
                    ->where('login_method', 2)
                    ->first();

                if (!$existUser) {
                    $userData = [
                        'user_code' => $usercode,
                        'facebook_id' => $socialUser->id,
                        'login_method' => 2,
                        'is_active' => 1,
                        'is_confirmed' => 1,
                        'user_ref_id' => session()->get('user_ref_id'),
                        'created_at' => time()
                    ];
                    $existUser = User::create($userData);

                    $userDetailData = [
                        'fullname' => $socialUser->name,
                        'user_id' => $existUser->id
                    ];
                    UserDetail::create($userDetailData);
                    DB::commit();
                    InitUserAccount::init($existUser);
                }
            }

            if ($provider == 'google') {
                $existUser = User::select('user.*')
                    ->join('user_detail', 'user.id', '=', 'user_detail.user_id')
                    ->where('google_id', $socialUser->id)
                    ->where('login_method', 1)
                    ->first();

                if (!$existUser) {
                    $userData = [
                        'user_code' => $usercode,
                        'google_id' => $socialUser->id,
                        'login_method' => 1,
                        'is_active' => 1,
                        'is_confirmed' => 1,
                        'user_ref_id' => session()->get('user_ref_id'),
                        'created_at' => time()
                    ];
                    $existUser = User::create($userData);

                    $userDetailData = [
                        'fullname' => $socialUser->name,
                        'user_id' => $existUser->id
                    ];
                    UserDetail::create($userDetailData);
                    DB::commit();
                    InitUserAccount::init($existUser);

                }
            }

            if ($existUser) {
                session()->forget('user_ref_id');
                Auth::guard('user')->login($existUser);

                Toastr::success('Đăng nhập thành công!');
                return redirect()->route('user.personal-info');
            }
        } catch (Throwable $throwable) {
            Toastr::error('Đăng nhập không thành công, vui lòng liên hệ Admin!');
            return redirect()->route('home.index');
        }
    }
}
