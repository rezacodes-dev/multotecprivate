<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

class IfAdminLogIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ck = Session::get('is_ar_admin_logged_in');
        $session_uid = Session::get('ar_login_user_id');
        
        if($ck == "yes" && Auth::check() && Auth::user()->id == $session_uid)
        {
            $response = $next($request);
            // return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            // ->header('Pragma','no-cache')
            // ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');

            $response->headers->set('Access-Control-Allow-Origin' , '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
            $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
            $response->headers->set('Pragma','no-cache');
            $response->headers->set('Expires','Fri, 01 Jan 1990 00:00:00 GMT');

            return $response;
        }
        else
        {
            return redirect()->route('dashboard_login')->with('msg','!!! UnAuthorized Access !!!');
        }
    }
}
