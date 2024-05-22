<?php

namespace Modules\Saas\Entities;

use App\Models\SchoolModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SmSubscriptionPayment extends Model
{
    protected $fillable = [];

    public function package(){
		return $this->belongsTo('Modules\Saas\Entities\SmPackagePlan', 'package_id', 'id');
	}

	public function school(){
		return $this->belongsTo('App\SmSchool', 'school_id', 'id');
	}

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        static::created(function ($model) {
            $this->updateSchoolModule($model);
        });

        static::updated(function ($model) {
            $this->updateSchoolModule($model);
        });

        static::deleting(function ($model) {
            $this->updateSchoolModule($model, 'remove');
        });
    }

    public function updateSchoolModule($model, $action = 'add'){

        Cache::forget('active_package' . $model->school_id);
    if($model->approve_status == 'paid' && $model->buy_type == 'instantly'){
        $school_module = SchoolModule::where('school_id', $model->school_id)->first();
        $modules = $model->package->modules;
        $menus = $model->package->menus;

        if(!$school_module){
            $school_module = new SchoolModule();
            $school_module->school_id = $model->school_id;
        } else{
            if($action == 'remove'){
                $modules = collect($school_module->modules)->diff($modules)->toArray();
                $menus = collect($school_module->menus)->diff($menus)->toArray();
            } else{
                $modules = collect(array_merge($modules, $school_module->modules))->unique()->toArray();
                $menus = collect(array_merge($menus, $school_module->menus))->unique()->toArray();
            }
        }

        $school_module->modules = $modules;
        $school_module->menus = $menus;
        $school_module->save();
    }

        Cache::forget('school_modules' . $model->school_id);
    }
}
