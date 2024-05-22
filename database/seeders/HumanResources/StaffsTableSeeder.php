<?php

namespace Database\Seeders\HumanResources;

use App\User;
use App\SmRoute;
use App\SmStaff;
use App\Models\SmExpertTeacher;
use Illuminate\Database\Seeder;

class StaffsTableSeeder  extends Seeder
{

    public function run($school_id , $count = 10){
       

        User::factory()->times($count)->create([
            'school_id' => $school_id,
        ])->each( function ($userStaff) use ($school_id) {
            SmStaff::factory()->times(1)->create([
                'user_id' => $userStaff->id,
                'email' => $userStaff->email,
                'first_name' => $userStaff->first_name,
                'last_name' => $userStaff->last_name,
                'full_name' => $userStaff->full_name,
                'school_id' => $school_id,
                'role_id' =>rand(4,9),
            ])->each(function($s){
                $s->staff_no = $s->id;
                $s->save();
                
                $i = 0;
                SmExpertTeacher::factory()->times(1)->create([
                    'staff_id' => $s->id,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'position' => $i++,
                ]);
            });
        });

        User::factory()->times($count)->create([
            'school_id' => $school_id,
        ])->each( function ($userStaff) use ($school_id) {
            SmStaff::factory()->times(1)->create([
                'user_id' => $userStaff->id,
                'email' => $userStaff->email,
                'first_name' => $userStaff->first_name,
                'last_name' => $userStaff->last_name,
                'full_name' => $userStaff->full_name,
                'school_id' => $school_id,
                'role_id' =>4,
            ])->each(function($s){
                $s->staff_no = $s->id;
                $s->save();
            });
        });
    }

}