<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;

class MailCampaignMiddleware
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
        $enabledMailCampaign = getEnabledMailCampaign();

        return $enabledMailCampaign
            ? $next($request)
            : redirect()->route('user.personal-info');
    }
}
