<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;

class AddColumnParentRegistrationRoutePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $routeList = array(  
        542 => 
        array (
          
          'name' => 'Registration',
          'route' => 'registration',
          'parent_route' => NULL,
          'type' => 1,
          'lang_name'=>'student.registration',
          'icon'=>'flaticon-reading'
        ),
        543 => 
        array (
          
          'name' => 'Student List',
          'route' => 'parentregistration.student-list',
          'parent_route' => 'registration',
          'type' => 2,
          'lang_name'=>'common.student_list',
        ),
        544 => 
        array (
          
          'name' => 'View',
          'route' => 'parentregistration/student-view',
          'parent_route' => 'parentregistration.student-list',
          'type' => 3,
        ),
        545 => 
        array (
          
          'name' => 'Approve',
          'route' => 'parentregistration/student-approve',
          'parent_route' => 'parentregistration.student-list',
          'type' => 3,
        ),
        546 => 
        array (
          
          'name' => 'Delete',
          'route' => 'parentregistration/student-delete',
          'parent_route' => 'parentregistration.student-list',
          'type' => 3,
        ),
        547 => 
        array (
          
          'name' => 'Settings',
          'route' => 'parentregistration/settings',
          'parent_route' => 'registration',
          'type' => 2,
          'lang_name'=>'student.settings',
        ),
        548 => 
        array (
          
          'name' => 'Update',
          'route' => 'parentregistration/settings-update',
          'parent_route' => 'parentregistration/settings',
          'type' => 3,
        ),
        );
   
        foreach($routeList as $key=>$item){
            Permission::updateOrCreate([
                'old_id'=>$key,
                'route'=>$item['route'],
                'parent_route'=>$item['parent_route'],
            ],
            [
                'module'=>'ParentRegistration',
                'name'=>isset($item['name']) ? $item['name'] : null,
                'type'=>$item['type'],
                'is_admin'=>1,
                'lang_name'=>isset($item['lang_name']) ? $item['lang_name'] : null,
                'icon'=>isset($item['icon']) ? $item['icon'] : null,
                'is_menu'=> $item['type'] == 1 || $item['type'] ==2 ? 1 : 0,
            ]
            );            
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
