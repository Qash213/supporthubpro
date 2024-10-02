<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class MaintananceModeMiddleware
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
        $excludedRoutes = [
            'admin/*',
            'admin',
            'livechat/*',
            'image/*',
            'imagedownload/*',
            'emailtoticket/*',
            'emtcimageurl/*',
            'emtcimagedownload/*',
            'emailtoticketdownload/*',
            'getProfile/*',
            'getimage/*',
            'notificationsreading',
            'badgecount',
            'markasreadcount',
            'notificationalerts',
            'timeupdate',
            'captcha',
        ];

        try {
            DB::connection()->getPdo();
            if(!DB::getSchemaBuilder()->hasTable('settings')){

                return $next($request);
            }else{

                if ( setting('MAINTENANCE_MODE') == 'on' && !$this->isExcludedRoute($request, $excludedRoutes) ){

                    return response()->view('errors.503', [], 503);
                }
                return $next($request);
            }
        } catch (\Exception $e) {
            return $next($request);

        }
    }

    private function isExcludedRoute(Request $request, array $excludedRoutes): bool
    {
        foreach ($excludedRoutes as $route) {
            if (Str::is($route, $request->path())) {
                return true;
            }
        }

        return false;
    }
}
