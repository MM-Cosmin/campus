<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;

class AddColumnGmeetRoutePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $routeList = 
        array(  1250 => 
        array (
          'name' => 'G-Meet',
          'route' => 'g-meet',
          'parent_route' => NULL,
          'type' => 1,
          'lang_name' => 'gmeet::gmeet.gmeet',
          'icon' => 'flaticon-reading'
        ),
        1251 => 
        array (
          'name' => 'Virtual Class',
          'route' => 'g-meet.virtual-class.index',
          'parent_route' => 'g-meet',
          'type' => 2,
          'lang_name' => 'common.virtual_class'
        ),
        1252 => 
        array (
          'name' => 'Add',
          'route' => 'g-meet.virtual-class.store',
          'parent_route' => 'g-meet.virtual-class.index',
          'type' => 3,
        ),
        1253 => 
        array (
          'name' => 'Edit',
          'route' => 'g-meet.virtual-class.edit',
          'parent_route' => 'g-meet.virtual-class.index',
          'type' => 3,
        ),
        1254 => 
        array (
          'name' => 'Delete',
          'route' => 'g-meet.virtual-class.destroy',
          'parent_route' => 'g-meet.virtual-class.index',
          'type' => 3,
        ),
        1255 => 
        array (
          'name' => 'Upload Recorded Video',
          'route' => 'g-meet.upload-document',
          'parent_route' => 'g-meet.virtual-class.index',
          'type' => 3,
        ),
        1256 => 
        array (
          'name' => 'Virtual Meeting',
          'route' => 'g-meet.virtual-meeting.index',
          'parent_route' => 'g-meet',
          'type' => 2,
          'lang_name' => 'common.virtual_meeting'
        ),
        1257 => 
        array (
          'name' => 'Add',
          'route' => 'g-meet.virtual-meeting.store',
          'parent_route' => 'g-meet.virtual-meeting.index',
          'type' => 3,
        ),
        1259 => 
        array (
          'name' => 'Edit',
          'route' => 'g-meet.virtual-meeting.edit',
          'parent_route' => 'g-meet.virtual-meeting.index',
          'type' => 3,
        ),
        1260 => 
        array (
          'name' => 'Delete',
          'route' => 'g-meet.virtual-meeting.destroy',
          'parent_route' => 'g-meet.virtual-meeting.index',
          'type' => 3,
        ),
        1261 => 
        array (
          'name' => 'Upload Recorded Video',
          'route' => 'g-meet.virtual-meeting.upload',
          'parent_route' => 'g-meet.virtual-meeting.index',
          'type' => 3,
        ),
        1262 => 
        array (
          'name' => 'Class Report',
          'route' => 'g-meet.virtual.class.reports.show',
          'parent_route' => 'g-meet',
          'type' => 2,
          'lang_name' => 'gmeet::gmeet.class_reports'
        ),
        1263 => 
        array (
          'name' => 'Search',
          'route' => '',
          'parent_route' => 'g-meet.virtual.class.reports.show',
          'type' => 3,
        ),
        1264 => 
        array (
          'name' => 'Meeting Report',
          'route' => 'g-meet.virtual.meeting.reports.show',
          'parent_route' => 'g-meet',
          'type' => 2,
          'lang_name' => 'gmeet::gmeet.meeting_reports'
        ),
        1265 => 
        array (
          'name' => 'Search',
          'route' => '',
          'parent_route' => 'g-meet.virtual.meeting.reports.show',
          'type' => 3,
        ),
        1266 => 
        array (
          'name' => 'Settings',
          'route' => 'g-meet.settings.index',
          'parent_route' => 'g-meet',
          'type' => 2,
          'lang_name' => 'common.settings'
        ),
        1267 => 
        array (
          'name' => 'Update',
          'route' => '',
          'parent_route' => 'g-meet.settings.index',
          'type' => 3,
        ),
        1270 => 
        array (
          'name' => 'Join Meeting',
          'route' => '',
          'parent_route' => 'g-meet.virtual-meeting.index',
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
                'module'=>'Gmeet',
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
