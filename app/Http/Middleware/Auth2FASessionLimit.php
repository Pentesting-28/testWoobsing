<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Auth2FASessionLimit
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
        #############################################################################################
        ########### VALIDATE IF THE CURRENT USER IS AUTHENTICATED IN OUR APPLICATION ################
        #############################################################################################

        if(!auth()->check()){
            return redirect('/login');
        }

        #############################################################################################
        ############ VALIDATE IF THE USER HAS TWO-FACTOR AUTHENTICATION CREDENTIALS #################
        #############################################################################################

        if (! is_null($request->user()->two_factor_secret) ||
            ! is_null($request->user()->two_factor_recovery_codes) ||
            ! is_null($request->user()->two_factor_confirmed_at)) {

            #############################################################################################
            ############ WE CUSTOMIZE THE USER SESSION TIME WITH A MAXIMUM OF 30 MINUTES ################
            #############################################################################################

            $lifetime = 30;
            config(['session.lifetime' => $lifetime]);
        }

        #############################################################################################
        ############ IF THE ABOVE CONDITIONS ARE NOT MET WE ALLOW THE MIDDLEWARE TO "GO" ############
        #############################################################################################

        return $next($request);
    }
}
