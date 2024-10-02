<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;
use laravelLara\lskusd\utils\trait\TraitCheckHelperAPI;

class DataRecovery
{
    use TraitCheckHelperAPI;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if((setting('newupdate') != 'V4.0') && (setting('newupdate') != 'updated4.0')){
            if($request->path() != 'newupdate'){
                return redirect()->route('admin.newupdate');
            }
            return $next($request);
        }else{
            //     return $next($request);
            // }

            // if(recursion() || represent() || setting('newupdate') == 'updated3.1' || setting('newupdate') == 'updated3.2' || setting('newupdate') == 'updated3.2V' || setting('newupdate') == 'updated3.1.1'  || setting('newupdate') == 'updated3.1.2' || setting('newupdate') == 'updated3.3'){
            //     return redirect()->route('admin.newupdate');
            // }else{
            if(setting('update_setting') == null){
                if($request->is('admin/*')){
                    return redirect()->route('admin.testinginfo');
                }else{
                    return $next($request);
                }
            }
            return $next($request);
        }
    }
}
