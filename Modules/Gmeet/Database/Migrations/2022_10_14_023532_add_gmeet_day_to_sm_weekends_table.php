<?php

use App\SmSchool;
use App\SmWeekend;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class AddGmeetDayToSmWeekendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_weekends', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_weekends', 'gmeet_day')) {
                $table->string('gmeet_day')->nullable();
            }
        });
        $schools = SmSchool::all();
        foreach ($schools as $school) {
            $days = SmWeekend::where('school_id', $school->id)->get();
            foreach ($days as $day) {
                $day->gmeet_day = strtoupper(substr($day->name, 0, 2));
                $day->save();
            }
        }

        try {
            $module_infos = [
                [1250, 60, 0, '1', 0, 'G-Meet', '', 'Gmeet', 'flaticon-reading', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1251, 60, 1250, '2', 0, 'Virtual Class', 'g-meet.virtual-class.index', 'virtual_class', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],

                [1252, 60, 1251, '3', 0, 'Add', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1253, 60, 1251, '3', 0, 'Edit', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1254, 60, 1251, '3', 0, 'Delete', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1255, 60, 1251, '3', 0, 'Upload Recorded Video', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],

                [1256, 60, 1250, '2', 0, 'Virtual Meeting', 'g-meet.virtual-meeting.index', 'virtual_meeting', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],

                [1257, 60, 1256, '3', 0, 'Add', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1259, 60, 1256, '3', 0, 'Edit', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1260, 60, 1256, '3', 0, 'Delete', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1261, 60, 1256, '3', 0, 'Upload Recorded Video', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1270, 60, 1256, '3', 0, 'Join Meeting', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],

                [1262, 60, 1250, '2', 0, 'Class Report', 'g-meet.virtual.class.reports.show', 'class_reports', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1263, 60, 1262, '3', 0, 'Search', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],

                [1264, 60, 1250, '2', 0, 'Meeting Report', 'g-meet.virtual.meeting.reports.show', 'meeting_reports', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],
                [1265, 60, 1264, '3', 0, 'Search', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],

                [1266, 60, 1250, '2', 0, 'Settings', 'g-meet.settings.index', 'settings', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],

                [1267, 60, 1266, '3', 0, 'Update', '', '', '', 1, 1, 1, 1, '2022-10-17 02:21:21', '2022-10-17 04:24:22'],

            ];
            // insert
            foreach ($module_infos as $key => $info) {
                $exist = InfixModuleInfo::find($info[0]);
                if ($exist) {
                    $module_info = InfixModuleInfo::find($info[0]);
                } else {
                    $module_info = new InfixModuleInfo();
                }
                $module_info->id = $info[0];
                $module_info->module_id = $info[1];
                $module_info->parent_id = $info[2];
                $module_info->type = $info[3];
                $module_info->is_saas = $info[4];
                $module_info->name = $info[5];
                $module_info->route = $info[6];
                $module_info->lang_name = $info[7];
                $module_info->icon_class = $info[8];
                $module_info->active_status = $info[9];
                $module_info->created_by = $info[10];
                $module_info->updated_by = $info[11];
                $module_info->school_id = $info[12];
                $module_info->created_at = $info[13];
                $module_info->updated_at = $info[14];
                $module_info->save();
            }
            // for admins
            $admins = [1250, 1251, 1253, 1252, 1254, 1255, 1256, 1257, 1259, 1260, 1261, 1262, 1263, 1264, 1265];
            foreach ($admins as $key => $value) {
                $check = InfixPermissionAssign::where('module_id', $value)->where('role_id', 5)->first();
                if (empty($check)) {
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 5;
                    $permission->save();
                }
            }

            //for teacher

            $teachers = [1250, 1251, 1252, 1253, 1254, 1255, 1256, 1257, 1259, 1260, 1261, 1262, 1263, 1264, 1265, 1270];

            foreach ($teachers as $key => $value) {
                $check = InfixPermissionAssign::where('module_id', $value)->where('role_id', 4)->first();
                if (empty($check)) {
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 4;
                    $permission->save();
                }

            }
            // for receiptionists
            $receiptionists = [1250, 1256, 1270];
            foreach ($receiptionists as $key => $value) {
                $check = InfixPermissionAssign::where('module_id', $value)->where('role_id', 7)->first();
                if (empty($check)) {
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 7;
                    $permission->save();
                }

            }

            // for librarians

            $librarians = [1250, 1256, 1270];

            foreach ($librarians as $key => $value) {
                $check = InfixPermissionAssign::where('module_id', $value)->where('role_id', 8)->first();
                if (empty($check)) {
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 8;
                    $permission->save();
                }

            }
            // drivers

            $drivers = [1250, 1256, 1270];
            foreach ($drivers as $key => $value) {
                $check = InfixPermissionAssign::where('module_id', $value)->where('role_id', 8)->first();
                if (empty($check)) {
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 9;
                    $permission->save();
                }
            }
            // accountants
            $accountants = [1250, 1256, 1270];
            foreach ($accountants as $key => $value) {
                $check = InfixPermissionAssign::where('module_id', $value)->where('role_id', 6)->first();
                if (empty($check)) {
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 6;
                    $permission->save();
                }

            }

            //for students

            $student_parent_module_infos =
                [
                [1250, 2033, 0, '1', 1, 'G-Meet', '', 'gmeet', 'flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
                [1251, 2033, 1250, '2', 1, 'Virtual Class', 'g-meet/virtual-class', 'virtual_class', '', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
                // paret
                [3105, 3033, 0, '1', 2, 'G-Meet', '', 'gmeet', 'flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
                [3106, 3033, 3105, '2', 2, 'Virtual Class', 'g-meet/virtual-class/child/{id}', 'virtual_class', '', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
                [3107, 3033, 3105, '2', 2, 'Virtual Meeting', 'g-meet/meetings/parent', 'virtual_meeting', '', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            ];
            foreach ($student_parent_module_infos as $key => $info) {
                $exist = InfixModuleStudentParentInfo::find($info[0]);
                if ($exist) {
                    $module_info = InfixModuleStudentParentInfo::find($info[0]);
                } else {
                    $module_info = new InfixModuleStudentParentInfo();
                }
                $module_info->id = $info[0];
                $module_info->module_id = $info[1];
                $module_info->parent_id = $info[2];
                $module_info->type = $info[3];
                $module_info->user_type = $info[4];
                $module_info->name = $info[5];
                $module_info->route = $info[6];
                $module_info->lang_name = $info[7];
                $module_info->icon_class = $info[8];
                $module_info->active_status = $info[9];
                $module_info->created_by = $info[10];
                $module_info->updated_by = $info[11];
                $module_info->school_id = $info[12];
                $module_info->created_at = $info[13];
                $module_info->updated_at = $info[14];
                $module_info->save();
            }
            $students = [1250, 1251];
            foreach ($students as $key => $value) {
                $check = InfixPermissionAssign::where('module_id', $value)->where('role_id', 2)->first();
                if (empty($check)) {
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 2;
                    $permission->save();
                }

            }

            //for parents
            $parents = [3105, 3106, 3107];
            foreach ($parents as $key => $value) {
                $check = InfixPermissionAssign::where('module_id', $value)->where('role_id', 3)->first();
                if (empty($check)) {
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission->module_info = InfixModuleStudentParentInfo::find($value)->name;
                    $permission->role_id = 3;
                    $permission->save();
                }

            }

        } catch (\Throwable$th) {
            Log::info($th);
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
