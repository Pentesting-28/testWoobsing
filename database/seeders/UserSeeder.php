<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ##########################################################
        ####################  TRUNCATE TABLES ####################
        ##########################################################

    	DB::statement("SET foreign_key_checks=0");
            DB::table('users')->truncate();
        DB::statement("SET foreign_key_checks=1");


        #############################################################################################
        ###########             WE DECLARE VARIABLES AND ASSIGN VALUES LIKE:              ###########
        ########### - ALL USERS TO GENERATE THAT CONTAINS THE PRIVATE FUNCTION users()    ###########
        ########### - ALL THE ROLES STORED IN OUR DATABASE, DIFFERENT BY THE "NAME" FIELD ###########
        #############################################################################################

        $users_all = $this->users();
        $all_roles_in_database = Role::get()->pluck('name');

        ###########################################################
        ###########  WE TRAVEL THE ARRANGEMENT OF USERS ###########
        ###########################################################
        for ($i=0; $i < count($users_all); $i++) {

            ##########################################################
            ###########  WE STORE USERS IN THE USERS TABLE ###########
            ##########################################################

            $user = User::create($users_all[$i]);

            ##############################################################################
            ###########  WE ASSIGN ROLE TO EACH USER STORED IN THE USERS TABLE ###########
            ##############################################################################

            $user->assignRole($all_roles_in_database[$i]);
        }
    }

    ##########################################################################
    ###########  THIS FUNCTION RETURNS AN ARRAY WITH THE NECESSARY ###########
    ###########  INFORMATION TO STORE THE USERS IN THE USERS TABLE ###########
    ##########################################################################
    private function users()
    {
        return [
            [
                'name'     => 'Admin',
                'email'    => 'admin@mail.com',
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'password' => bcrypt("secret")
            ],
            [
                'name'     => 'Moderator',
                'email'    => 'moderator@mail.com',
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'password' => bcrypt("secret")
            ]
        ];
    }
}
