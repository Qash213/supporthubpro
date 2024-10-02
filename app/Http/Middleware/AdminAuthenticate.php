<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

// use Spatie\Honeypot\ProtectAgainstSpam;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $user = Auth::user();
        $user->update(['last_activity' => now()]);

        $showAlertOnceAfter = now()->addMinutes(setting('admin_users_inactive_auto_logout_time'));
        $logoutOnceafter = now()->addMinutes((int) setting('admin_users_inactive_auto_logout_time') + 1);
        session()->put('last_activity', now());
        session()->put('showAlertOnceAfter', $showAlertOnceAfter);
        session()->put('logoutOnceafter', $logoutOnceafter);

        if (setting('Employe_email_two_fact') == 'on' &&  User::where(['id' => Auth::id(), 'twofactorauth' => 'emailtwofact'])->exists()) {
            if (Auth::check() && Auth::user()->status == '1' && session()->get('admintwofactoremail') == Auth::user()->email) {
                return $next($request);
            } else {
                if (Auth::check() && Auth::user()->status == '1') {
                    return redirect()->route('admin.emailtwofactorlogin', ['email' => Auth::user()->email]);
                } else {

                    Auth::logout();
                    return redirect()->route('login');
                }
            }
        } elseif (setting('Employe_google_two_fact') == 'on' && User::where(['id' => Auth::id(), 'twofactorauth' => 'googletwofact'])->exists()) {

            if (Auth::check() && Auth::user()->status == '1'  && session()->get('admingoogleauthid') == Auth::user()->email) {
                return $next($request);
            } else {
                if (Auth::check() && Auth::user()->status == '1') {
                    return redirect()->route('admin.google2falogin', ['email' => Auth::user()->email]);
                } else {

                    Auth::logout();
                    return redirect()->route('login');
                }
            }
        } else {
            if (Auth::check() && Auth::user()->status == '1') {
                return $next($request);
            } else {
                Auth::logout();
                return redirect()->route('login');
            }
        }
    }
}
