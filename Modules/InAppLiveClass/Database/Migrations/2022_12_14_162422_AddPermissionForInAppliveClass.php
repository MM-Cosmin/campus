<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

class AddPermissionForInAppliveClass extends Migration
{
    public function up()
    {
        $routes = [
            ['name' => 'In-App Live Class', 'route' => 'inappliveclass', 'type' => 1, 'parent_route' => null, 'module' => 'InAppLiveClass'],
            ['name' => 'Setting', 'route' => 'inappliveclass.setting', 'type' => 2, 'parent_route' => 'inappliveclass', 'module' => 'InAppLiveClass'],
        ];
        if (function_exists('permissionUpdateOrCreate')) {
            permissionUpdateOrCreate($routes);
        }
    }

    public function down()
    {
        //
    }
}
