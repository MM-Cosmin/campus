<?php

namespace Modules\Saas\Database\Seeders;

use App\User;
use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmStyle;
use App\SmParent;
use App\SmSchool;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmVisitor;
use App\SmExamType;
use App\SmFeesType;
use App\SmLanguage;
use App\SmClassRoom;
use App\SmClassTime;
use App\SmExamSetup;
use App\SmFeesGroup;
use App\SmMarkStore;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\SmMarksGrade;
use App\SmResultStore;
use App\GlobalVariable;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmExamSchedule;
use App\SmAssignSubject;
use App\SmQuestionGroup;
use App\SmExamAttendance;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use Faker\Factory as Faker;
use App\SmExamAttendanceChild;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\Saas\Entities\SaasSchoolModulePermissionAssign;

class SaasDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // $this->call("OthersTableSeeder");
    }
}
