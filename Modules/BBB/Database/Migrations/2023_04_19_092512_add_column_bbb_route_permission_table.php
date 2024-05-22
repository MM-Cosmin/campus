<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;

class AddColumnBbbRoutePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $routeList = 
        array(  
          850 => 
        array (
          
          'name' => 'BigBlueButton',
          'route' => 'bigbluebutton',
          'parent_route' => NULL,
          'type' => 1,
          'lang_name' => 'bbb::bbb.bbb',
          'icon' => 'flaticon-reading'
        ),
        851 => 
        array (
          
          'name' => 'Virtual Class',
          'route' => 'bbb.virtual-class',
          'parent_route' => 'bigbluebutton',
          'type' => 2,
          'lang_name' => 'common.virtual_class'
        ),
        852 => 
        array (
          
          'name' => 'Add',
          'route' => 'bbb.virtual-class.store',
          'parent_route' => 'bbb.virtual-class',
          'type' => 3,
        ),
        853 => 
        array (
          
          'name' => 'Edit',
          'route' => 'bbb.virtual-class.edit',
          'parent_route' => 'bbb.virtual-class',
          'type' => 3,
        ),
        854 => 
        array (
          
          'name' => 'Delete',
          'route' => 'bbb.virtual-class.delete',
          'parent_route' => 'bbb.virtual-class',
          'type' => 3,
        ),
        855 => 
        array (
          
          'name' => 'Start Class',
          'route' => '',
          'parent_route' => 'bbb.virtual-class',
          'type' => 3,
        ),
        856 => 
        array (
          
          'name' => 'Virtual Meeting',
          'route' => 'bbb.meetings',
          'parent_route' => 'bigbluebutton',
          'type' => 2,
          'lang_name' => 'bbb::bbb.virtual_meeting'
        ),
        857 => 
        array (
          
          'name' => 'Add',
          'route' => 'bbb.meetings.store',
          'parent_route' => 'bbb.meetings',
          'type' => 3,
        ),
        859 => 
        array (
          
          'name' => 'Edit',
          'route' => 'bbb.meetings.edit',
          'parent_route' => 'bbb.meetings',
          'type' => 3,
        ),
        860 => 
        array (
          
          'name' => 'Delete',
          'route' => 'bbb.meetings.delete',
          'parent_route' => 'bbb.meetings',
          'type' => 3,
        ),
        861 => 
        array (
          
          'name' => 'Start Meeting',
          'route' => '',
          'parent_route' => 'bbb.meetings',
          'type' => 3,
        ),
        862 => 
        array (
          
          'name' => 'Class Report',
          'route' => 'bbb.virtual.class.reports.show',
          'parent_route' => 'bigbluebutton',
          'type' => 2,
          'lang_name' => 'bbb::bbb.class_reports'
        ),
        863 => 
        array (
          
          'name' => 'Search',
          'route' => '',
          'parent_route' => 'bbb.virtual.class.reports.show',
          'type' => 3,
        ),
        864 => 
        array (
          
          'name' => 'Meeting Report',
          'route' => 'bbb.meeting.reports.show',
          'parent_route' => 'bigbluebutton',
          'type' => 2,
          'lang_name' => 'bbb::bbb.meeting_reports'
        ),
        865 => 
        array (
          
          'name' => 'Search',
          'route' => '',
          'parent_route' => 'bbb.meeting.reports.show',
          'type' => 3,
        ),
        866 => 
        array (
          
          'name' => 'Settings',
          'route' => 'bbb.settings',
          'parent_route' => 'bigbluebutton',
          'type' => 2,
          'lang_name' => 'bbb::bbb.settings'
        ),
        867 => 
        array (
          
          'name' => 'Update',
          'route' => '',
          'parent_route' => 'bbb.settings',
          'type' => 3,
        ),
        868 => 
        array (
          
          'name' => 'Class Recorded List',
          'route' => 'bbb.class.recording.list',
          'parent_route' => 'bigbluebutton',
          'type' => 2,
          'lang_name' => 'bbb::bbb.class_record_list'
        ),
        869 => 
        array (
          
          'name' => 'Meeting Recorded List',
          'route' => 'bbb.meeting.recording.list',
          'parent_route' => 'bigbluebutton',
          'type' => 2,
          'lang_name' => 'bbb::bbb.meeting_record_list'
        ),
        );
        foreach($routeList as $key=>$item){

            Permission::updateOrCreate([
                'old_id'=>$key,
                'route'=>$item['route'],
                'parent_route'=>$item['parent_route'],
            ],
            [
                'module'=>'BBB',
                'permission_section'=>0,
                'name'=>$item['name'],               
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
