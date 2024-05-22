<?php

namespace Modules\ParentRegistration\Entities;

use Illuminate\Database\Eloquent\Model;

class SmStudentRegistration extends Model
{
    protected $fillable = [];

     public function class(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }

	public function section(){
		return $this->belongsTo('App\SmSection', 'section_id', 'id');
	}

	public function academicYear(){
		return $this->belongsTo('App\SmAcademicYear', 'academic_id', 'id');
	}

	public function gender(){
		return $this->belongsTo('App\SmBaseSetup', 'gender_id', 'id');
	}
	public function school(){
		return $this->belongsTo('App\SmSchool', 'school_id', 'id');
	}
	
  // for online registration -abunayem
	public function religion()
    {
        return $this->belongsTo('App\SmBaseSetup', 'religion_id', 'id');
    }

    public function bloodGroup()
    {
        return $this->belongsTo('App\SmBaseSetup', 'bloodgroup_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\SmStudentCategory', 'student_category_id', 'id');
    }


    public function group()
    {
        return $this->belongsTo('App\SmStudentGroup', 'student_group_id', 'id');
    }

	
    public function route()
    {
        return $this->belongsTo('App\SmRoute', 'route_list_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\SmVehicle', 'vechile_id', 'id');
    }

    public function dormitory()
    {
        return $this->belongsTo('App\SmDormitoryList', 'dormitory_id', 'id');
    }

    public function sections()
    {
        return $this->hasManyThrough('App\SmSection', 'App\SmClassSection', 'class_id', 'id', 'class_id', 'section_id');
    }

    public function leadCity()
    {
        
        return $this->belongsTo('Modules\Lead\Entities\LeadCity', 'lead_city', 'id')->withDefault();
        
    }
    public function source()
    {
        return $this->belongsTo('Modules\Lead\Entities\Source', 'source_id', 'id')->withDefault();
    
    }
	
	
}
