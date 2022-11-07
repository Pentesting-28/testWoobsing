<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
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
            DB::table('model_has_roles')->truncate();
            DB::table('roles')->truncate();
        DB::statement("SET foreign_key_checks=1");

        ###########################################################
        ###########  WE TRAVEL THE ARRANGEMENT OF ROLES ###########
        ###########################################################

        foreach ($this->roles() as $item_role) {

            ##########################################################
            ###########  WE STORE ROLES IN THE ROLES TABLE ###########
            ##########################################################

            $role = Role::create($item_role);

            $permissions = Permission::when(($role->name === 'Moderator'), function ( $query ) {
                                $query->where('name', '<>', 'users.delete');
                           })
                           ->pluck('id');

            ##############################################################################################
            ###########  WE SYNCHRONIZE THE PERMISSIONS TO EACH ROLE STORED IN THE ROLES TABLE ###########
            ##############################################################################################

            $role->syncPermissions($permissions);
        }
    }

    ##########################################################################
    ###########  THIS FUNCTION RETURNS AN ARRAY WITH THE NECESSARY ###########
    ###########  INFORMATION TO STORE THE ROLES IN THE ROLES TABLE ###########
    ##########################################################################
    private function roles()
    {
        return [
            [
                'name' => 'Admin'
            ],
            [
                'name' => 'Moderator'
            ]
        ];
    }
}
