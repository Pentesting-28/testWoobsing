<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Fortify::ignoreRoutes(); //It will tell Fortify to ignore the build-in routes.
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });


        ###################################################################################################################
        ########### REGISTER A CALLBACK THAT IS RESPONSIBLE FOR VALIDATING INCOMING AUTHENTICATION CREDENTIALS. ###########
        ###################################################################################################################


        Fortify::authenticateUsing(function (Request $request) {

            ##########################################################################################################
            ########### WE SEARCH THROUGH AN ORM QUERY IF THE USER IS REGISTERED THROUGH THE 'EMAIL' FIELD ###########
            ##########################################################################################################

            $user = User::where('email', $request->email)->first();

            #################################################################################################
            ########### VALIDATE IF THE USER EXISTS AND VERIFY THAT THE PASSWORD MATCHES THE HASH ###########
            #################################################################################################

            if ($user && Hash::check($request->password, $user->password)) {

                ##########################################################################################
                ########### WE UPDATE THE VALUE OF THE last_login FIELD FOR EACH SESSION START ###########
                ##########################################################################################

                $user->update([
                    'last_login' => Carbon::now()->toDateTimeString(),
                ]);

                return $user;
            }
        });
    }
}
