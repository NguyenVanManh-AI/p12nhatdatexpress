<?php

namespace App\Http\Middleware\Visitor;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckVisitor
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
        if (!$request->session()->has('visited_expired') || time() > $request->session()->get('visited_expired')){
            $start_date = strtotime('today', mktime(0,0,0));
            $TIME_OF_DATE = 86399;
            $exists = DB::table('access_statistic')->where('ip', $request->ip())->whereBetween('access_at', [$start_date, $start_date + $TIME_OF_DATE])->count();
            if ($exists == 0)
            {
                DB::table('access_statistic')->insert([
                    'ip' => $request->ip(),
                    'user-agent' => $request->header('User-Agent'),
                    'access_at' => time()
                ]);
            }
            session()->put('visited_expired', $start_date + $TIME_OF_DATE);
        }

        return $next($request);
    }
}
