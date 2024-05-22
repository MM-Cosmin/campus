<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\AssignPermission;
use Modules\RolePermission\Entities\Permission;

class AddAccountIdZoomSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zoom_settings', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'account_id')) {
                $table->string('account_id')->nullable();
            }
        });
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'zoom_account_id')) {
                $table->string('zoom_account_id')->nullable();
            }
        });
        Schema::table('zoom_settings', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'school_id')) {
                $table->string('school_id')->nullable()->default(1);
            }
        });
        Schema::table('zoom_virtual_class', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'join_url')) {
                $table->text('join_url')->nullable();
            }
        });
        Schema::table('zoom_meetings', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'join_url')) {
                $table->text('join_url')->nullable();
            }
        });
        $studentsPermissions = Permission::where('module', 'Zoom')->where('is_student', 1)->where('is_menu', 1)->get(['id', 'name']);
        $parentPermissions = Permission::where('module', 'Zoom')->where('is_parent', 1)->where('is_menu', 1)->get(['id', 'name']);
        foreach($studentsPermissions as $studentPermission) {
           AssignPermission::updateOrCreate([
                'permission_id'=>$studentPermission->id,
                'role_id'=>2
            ]);
        }
        foreach($parentPermissions as $parentPermission) {
           AssignPermission::updateOrCreate([
                'permission_id'=>$parentPermission->id,
                'role_id'=>3
            ]);
        }
        $admins = [554, 555, 556, 557, 558, 559, 560, 561, 562, 563, 564, 565, 566, 567, 568, 569, 570];
        $adminPermissions = Permission::whereIn('old_id', $admins)->get(['id', 'route']);
        foreach($adminPermissions as $adminPermission) {
            AssignPermission::updateOrCreate([
                 'permission_id'=>$adminPermission->id,
                 'role_id'=>5
             ]);
         }
        $teachers= [ 554, 555, 556, 557, 558, 559, 560, 561, 562, 563, 564, 565, 566, 567];
        $teachersPermissions = Permission::whereIn('old_id', $teachers)->get(['id', 'route']);
        foreach($teachersPermissions as $teacherPermission) {
            AssignPermission::updateOrCreate([
                 'permission_id'=>$teacherPermission->id,
                 'role_id'=>4
             ]);
         }
        $receiptionists=[554, 560, 564];
        $receiptionistsPermissions = Permission::whereIn('old_id', $receiptionists)->get(['id', 'route']);
        foreach($receiptionistsPermissions as $receiptionistsPermission) {
            AssignPermission::updateOrCreate([
                 'permission_id'=>$receiptionistsPermission->id,
                 'role_id'=>7
             ]);
         }
        $librarians= [554, 560, 564];
        $librariansPermissions = Permission::whereIn('old_id', $librarians)->get(['id', 'route']);
        foreach($librariansPermissions as $librarianPermission) {
            AssignPermission::updateOrCreate([
                 'permission_id'=>$librarianPermission->id,
                 'role_id'=>8
             ]);
         }
        $drivers =[554, 560, 564];
        $driversPermissions = Permission::whereIn('old_id', $drivers)->get(['id', 'route']);
        foreach($driversPermissions as $driverPermission) {
            AssignPermission::updateOrCreate([
                 'permission_id'=>$driverPermission->id,
                 'role_id'=>9
             ]);
         }
        $accountants=[554, 560, 564];
        $accountantsPermissions = Permission::whereIn('old_id', $accountants)->get(['id', 'route']);
        foreach($accountantsPermissions as $accountPermission) {
            AssignPermission::updateOrCreate([
                 'permission_id'=>$accountPermission->id,
                 'role_id'=>6
             ]);
         }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
}
