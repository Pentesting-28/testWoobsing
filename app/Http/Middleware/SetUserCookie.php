<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class SetUserCookie
{
    private const IP_CONSTANT = "127.0.0.1";
    private const ROLE_ID = 1;

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

        ###################################################################################################################
        ########### WE DECLARE VARIABLES WITH THE ID OF THE AUTHENTICATED USER AND THE IP ADDRESS OF THE CLIENT ###########
        ###################################################################################################################

        $user_id = $request->user()->id;
        $ip_adress_source = $request->getClientIp();

        #########################################################################################################
        ########## WE LOOK FOR THE AUTHENTICATED USER THROUGH AN ORM QUERY, WHERE WE FILTER IT IF THEY ##########
        ########## HAVE AN ASSIGNED ROLE AND IF THEY MEET THE CONDITION OF HAVING THE ROLE WITH ID '1' ##########
        #########################################################################################################

        $user_role = User::where('id',$user_id)
            ->withWhereHas('roles', function ($query) {
                $query->whereId(self::ROLE_ID);
            })
            ->first();

        #########################################################################################################
        ########## VALIDATE IF THE RESULT OF THE QUERY CONTAINING THE VARIABLE $user_role IS DIRECTING ##########
        ########## NULL AND IF THE CLIENT'S IP ADDRESS IS STRICTLY EQUAL TO "127.0.0.1" THEN CREATE A  ##########
        ##########                  COOKIE CALLED 'origin_sesion' AND ASSIGN IT A VALUE                ##########
        #########################################################################################################

        if(!is_null($user_role) && $ip_adress_source === self::IP_CONSTANT){
            $value = 'Si Posee el Rol y la Direccion Ip';
            Cookie::queue('origin_sesion', $value );
        }

        #############################################################################################
        ############ IF THE ABOVE CONDITIONS ARE NOT MET WE ALLOW THE MIDDLEWARE TO "GO" ############
        #############################################################################################

        return $next($request);
    }
}
