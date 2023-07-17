<?php

namespace App\Http\Middleware\User;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('user')->user()->user_type_id)
        {
            Toastr::error('Vui lòng chọn loại tài khoản, để sử dụng chức năng!');
            return  redirect()->route('user.personal-info');

        }
        return $next($request);
    }
}
