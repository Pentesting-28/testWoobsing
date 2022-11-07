<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
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
            DB::table('model_has_permissions')->truncate();
            DB::table('permissions')->truncate();
        DB::statement("SET foreign_key_checks=1");

        #################################################################
        ###########  WE TRAVEL THE ARRANGEMENT OF PERMISSIONS ###########
        #################################################################

        foreach ($this->permissions() as $item_permission) {

            ################################################################
            ###########  WE STORE ROLES IN THE PERMISSIONS TABLE ###########
            ################################################################

            Permission::create($item_permission);
        }
    }

    ####################################################################################
    ###########       THIS FUNCTION RETURNS AN ARRAY WITH THE NECESSARY      ###########
    ###########  INFORMATION TO STORE THE PERMISSION IN THE PERMISSION TABLE ###########
    ####################################################################################
    private function permissions()
    {
        return [
            [
                'name'   => 'users.index',
                'module' => 'Users'
            ],
            [
                'name'   => 'users.store',
                'module' => 'Users'
            ],
            [
                'name'   => 'users.show',
                'module' => 'Users'
            ],
            [
                'name'   => 'users.update',
                'module' => 'Users'
            ],
            [
                'name'   => 'users.delete',
                'module' => 'Users'
            ],
        ];
    }
}
