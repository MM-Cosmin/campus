<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\Permission;

class AddColumnAppSliderRoutePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $routeList = array (
            930 => array(
                
                'name'=>'App Slider',
                'route'=>'appslider.index',
                'parent_route'=>'front_settings',
                'type'=>2,
            ),
            931 => array(
                
                'name'=>'View',
                'route'=>'appslider.index.view',
                'parent_route'=>'appslider.index',
                'type'=>3,
            ),
            932 => array(
                
                'name'=>'Add',
                'route'=>'appslider.store',
                'parent_route'=>'appslider.index',
                'type'=>3,
            ),
            933 => array(
                
                'name'=>'Edit',
                'route'=>'appslider.edit',
                'parent_route'=>'appslider.index',
                'type'=>3,
            ),
            934 => array(
                
                'name'=>'Delete',
                'route'=>'appslider.delete',
                'parent_route'=>'appslider.index',
                'type'=>3,
            ),
        
        );
        foreach($routeList as $key=>$item){           
            $permission = new Permission;
            $permission->module = 'AppSlider';
            $permission->name = $item['name'];
            $permission->route = $item['route'];
            $permission->parent_route = $item['parent_route'];
            $permission->type = $item['type'];
            $permission->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
