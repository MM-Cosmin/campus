<?php

namespace Modules\Saas\Entities;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class SmPackagePlan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'menus' => 'array',
        'modules' => 'array'
    ];

    public function packageFeatures()
    {
        return $this->hasMany('Modules\Saas\Entities\SmPackagePlanFeature', 'package_plan_id', 'id');
    }

    public static function isSubscriptionAutheticate()
    {

        if (Auth::check() && Auth::user()->school_id != 1) {

            $is_trial = SmSubscriptionPayment::orderBy('id', 'desc')->where('school_id', Auth::user()->school_id)->first();

            $now_time = date('Y-m-d');
            $now_time = date('Y-m-d', strtotime($now_time . ' + 1 days'));

            if (@$is_trial->payment_type == "trial") {

                if ($now_time <= $is_trial->end_date) {
                    return true;
                } else {
                    return false;
                }

            } else {
                $is_paid = SmSubscriptionPayment::orderBy('id', 'desc')->where('approve_status', 'approved')->where('school_id', Auth::user()->school_id)->first();
                if ($is_paid != "") {
                    if ($now_time <= $is_paid->end_date) {

                        return true;

                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    public static function student_limit($school_id = '')
    {

        if ($school_id == '') {
            $school_id = Auth::user()->school_id;
        }

        $purchase_packages = SmSubscriptionPayment::where('school_id', $school_id)->get();

        $last_record = SmSubscriptionPayment::orderBy('id', 'desc')->where('approve_status', 'approved')->where('school_id', $school_id)->first();


        foreach ($purchase_packages as $purchase_package) {
            if ($purchase_package->approve_status == 'approved') {

                if ($purchase_package->buy_type == 'instantly') {
                    // 
                    if ($purchase_package->id == $last_record->id && $last_record->buy_type == 'instantly') {
                        //$status = 'Active';
                        $student = $purchase_package->package->student_quantity;
                    } else {

                        $now_time = date('Y-m-d');

                        if ($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $last_record->buy_type == 'buy_now') {

                            //$status = 'Active';
                            $student = $purchase_package->package->student_quantity;
                        }
                    }
                }

                if ($purchase_package->buy_type == 'buy_now') {

                    $now_time = date('Y-m-d');

                    if ($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $last_record->buy_type == 'buy_now') {
                        //$status = 'Active';
                        $student = $purchase_package->package->student_quantity;
                    }
                }

                if ($purchase_package->payment_type == 'trial') {

                    if ($purchase_packages->count() == 1) {
                        //$status = 'Active/Trial';
                        $student = $purchase_package->package->student_quantity;
                    }
                }

            } else {

                if ($purchase_package->payment_type == 'trial') {

                    if ($purchase_packages->count() == 1) {
                        //$status = 'Active/Trial';
                        $student = $purchase_package->package->student_quantity;
                    }
                }

            }
        }


        return @$student;
    }

    public static function staff_limit($school_id = '')
    {

        if ($school_id == '') {
            $school_id = Auth::user()->school_id;
        }

        $purchase_packages = SmSubscriptionPayment::where('school_id', $school_id)->get();

        $last_record = SmSubscriptionPayment::orderBy('id', 'desc')->where('approve_status', 'approved')->where('school_id', $school_id)->first();


        foreach ($purchase_packages as $purchase_package) {
            if ($purchase_package->approve_status == 'approved') {

                if ($purchase_package->buy_type == 'instantly') {
                    //
                    if ($purchase_package->id == $last_record->id && $last_record->buy_type == 'instantly') {
                        //$status = 'Active';
                        $staff = $purchase_package->package->staff_quantity;
                    } else {

                        $now_time = date('Y-m-d');

                        if ($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $last_record->buy_type == 'buy_now') {

                            //$status = 'Active';
                            $staff = $purchase_package->package->staff_quantity;
                        }
                    }
                }

                if ($purchase_package->buy_type == 'buy_now') {

                    $now_time = date('Y-m-d');

                    if ($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $last_record->buy_type == 'buy_now') {
                        //$status = 'Active';
                        $staff = $purchase_package->package->staff_quantity;
                    }
                }

            } else {

                if ($purchase_package->payment_type == 'trial') {

                    if ($purchase_packages->count() == 1) {
                        //$status = 'Active/Trial';
                        $staff = $purchase_package->package->staff_quantity;
                    }
                }

            }
        }


        return @$staff;
    }

    public function getModule($module)
    {
        return Arr::get($this->modules, $module);
    }
}