<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class LastUserSession
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

        ##########################################################################################################
        ########### WE SEARCH THROUGH AN ORM QUERY IF THE USER IS REGISTERED THROUGH THE 'EMAIL' FIELD ###########
        ##########################################################################################################

        $user = User::where('email', $request->email)->first();

        ##########################################################################################################
        ########## VALIDATE IF THE USER DOES NOT EXIST THEN WE REDIRECT TO THE USER REGISTRATION SCREEN ##########
        ##########################################################################################################

        if(is_null($user)){
            return redirect('/register');
        }

        ###############################################################
        ########### CREATE A CARBON INSTANCE FROM A STRING. ###########
        ###############################################################

        $date_last_login = Carbon::parse($user->last_login);
        $date_actual = Carbon::parse(Carbon::now());

        #################################################################################################################################################
        ########### WE GET THE DIFFERENCE IN DAYS IN NUMERICAL FORM BETWEEN THE DATE AND TIME OF THE LAST LOGIN AND THE CURRENT DATE AND TIME ###########
        #################################################################################################################################################

        $count_days = $date_actual->diffInDays($date_last_login);

        #############################################################################################################################
        ########## WE VALIDATE THROUGH A TERNARY OPERATOR IF THE DAYS THE USER HAS BEEN WITHOUT AUTHENTICATION ARE GREATER ##########
        ##########   THAN '0' THEN REDIRECT THEM TO A SCREEN CALLED 'SESSIONS' OTHERWISE WE ALLOW THE MIDDLEWARE TO "GO"   ##########
        #############################################################################################################################

        return $count_days > 0
                ? redirect('/sessions')
                : $next($request);
    }
}
