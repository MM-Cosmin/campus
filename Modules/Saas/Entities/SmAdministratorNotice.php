<?php

namespace Modules\Saas\Entities;

use App\SmSchool;
use Illuminate\Database\Eloquent\Model;

class SmAdministratorNotice extends Model
{
    public static function getInstitutionName($institution){
        $getRoleName = SmSchool::select('school_name')
            ->where('id', $institution)
            ->first();

        if (isset($getRoleName)) {
            return $getRoleName;
        } else {
            return false;
        }
    }

    public function getInstitute()
    {
        $ids =explode(',',$this->inform_to);
        return SmSchool::whereIn('id',$ids)->get(['school_name']);
    }
}
