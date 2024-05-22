<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;

class AddColumnZoomRoutePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $routeList =  array(
            554 => 
            array (
              'name' => 'Virtual Class',
              'route' => 'virtual_class',
              'parent_route' => NULL,
              'type' => 1,
              'lang_name'=>'common.virtual_class',
              'icon'=>'flaticon-reading'
            ),
            555 => 
            array (
              'name' => 'Virtual Class',
              'route' => 'zoom.virtual-class',
              'parent_route' => 'virtual_class',
              'type' => 2,
              'lang_name'=>'common.virtual_class',
            ),
            556 => 
            array (
              'name' => 'Add',
              'route' => 'zoom.virtual-class.store',
              'parent_route' => 'zoom.virtual-class',
              'type' => 3,
            ),
            557 => 
            array (
              'name' => 'Edit',
              'route' => 'zoom.virtual-class.edit',
              'parent_route' => 'zoom.virtual-class',
              'type' => 3,
            ),
            558 => 
            array (
              'name' => 'Delete',
              'route' => 'zoom.virtual-class.destroy',
              'parent_route' => 'zoom.virtual-class',
              'type' => 3,
            ),
            559 => 
            array (
              'name' => 'Start Class',
              'route' => 'zoom.virtual-class.join',
              'parent_route' => 'zoom.virtual-class',
              'type' => 3,
            ),
            560 => 
            array (
              'name' => 'Virtual Meeting',
              'route' => 'zoom.meetings',
              'parent_route' => 'virtual_class',
              'type' => 2,
              'lang_name'=>'zoom::zoom.virtual_meeting',
            ),
            561 => 
            array (
              'name' => 'Add',
              'route' => 'zoom.meetings.store',
              'parent_route' => 'zoom.meetings',
              'type' => 3,
            ),
            562 => 
            array (
              'name' => 'Edit',
              'route' => 'zoom.meetings.edit',
              'parent_route' => 'zoom.meetings',
              'type' => 3,
            ),
            563 => 
            array (
              'name' => 'Delete',
              'route' => 'zoom.meetings.destroy',
              'parent_route' => 'zoom.meetings',
              'type' => 3,
            ),
            564 => 
            array (
              'name' => 'Join Meeting',
              'route' => 'zoom.virtual-meeting.join',
              'parent_route' => 'zoom.meetings',
              'type' => 3,
            ),
            565 => 
            array (
              'name' => 'Class Report',
              'route' => 'zoom.virtual.class.reports.show',
              'parent_route' => 'virtual_class',
              'type' => 2,
              'lang_name'=>'zoom::zoom.class_reports',
            ),
            566 => 
            array (
              'name' => 'Search',
              'route' => '',
              'parent_route' => 'zoom.virtual.class.reports.show',
              'type' => 3,
            ),
            567 => 
            array (
              'name' => 'Meeting Report',
              'route' => 'zoom.meeting.reports.show',
              'parent_route' => 'virtual_class',
              'type' => 2,
              'lang_name'=>'zoom::zoom.meeting_reports',
            ),
            568 => 
            array (
              'name' => 'Search',
              'route' => '',
              'parent_route' => 'zoom.meeting.reports.show',
              'type' => 3,
            ),
            569 => 
            array (
              'name' => 'Settings',
              'route' => 'zoom.settings',
              'parent_route' => 'virtual_class',
              'type' => 2,
              'lang_name'=>'zoom::zoom.settings',
            ),
            570 => 
            array (
              'name' => 'Update',
              'route' => '',
              'parent_route' => 'zoom.settings',
              'type' => 3,
            ),
        );        

        foreach($routeList as $key=>$item){
          Permission::updateOrCreate([               
                'route'=>$item['route'],
                'parent_route'=>$item['parent_route'],
            ],
            [
                'old_id'=>$key,
                'name'=>isset($item['name']) ? $item['name'] : null,
                'module'=>'Zoom',
                'is_admin'=>1,
                'type'=>$item['type'],
                'lang_name'=>isset($item['lang_name']) ? $item['lang_name'] : null,
                'icon'=>isset($item['icon']) ? $item['icon'] : null,
                'is_menu'=> $item['type'] == 1 || $item['type'] ==2 ? 1 : 0,
                'permission_section'=>0,
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
