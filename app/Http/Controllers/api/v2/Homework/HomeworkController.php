<?php

namespace App\Http\Controllers\api\v2\Homework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmHomework;
use App\SmStudent;

class HomeworkController extends Controller
{
    public function adminTeacherhomework()
    {
        $all_homeworks = SmHomework::with(['subjects' => function($q){
            $q->select('id', 'subject_name');
        }])->select('created_at', 'submission_date', 'evaluation_date', 'active_status', 'marks', 'subject_id');      
        $all_homeworks->where('school_id', app('school')->id)->orderby('id','DESC')->where('academic_id', getAcademicId());
        if (teacherAccess()) {
            $homeworkLists = $all_homeworks->where('created_by', auth()->user()->id)->get();
        } else {
            $homeworkLists = $all_homeworks->get();
        }     
 

        $response = [
            'success' => true,
            'data'    => $homeworkLists
        ];
        return response()->json($response, 200);
    }


    public function studentHomework($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        $homeworkLists = SmHomework::with(['subjects' => function($q){
                                $q->select('id', 'subject_name');
                            }])->select('created_at', 'submission_date', 'evaluation_date', 'active_status', 'marks', 'subject_id')
                            ->where('school_id', auth()->user()->school_id)
                            ->where(function ($que) use ($record) {
                                return $que->where('class_id', $record->class_id)
                                    ->orWhereNull('class_id');
                            })
                            ->where(function ($que) use ($record) {
                                return $que->where('section_id', $record->section_id)
                                    ->orWhereNull('section_id');
                            })
                            ->where('course_id', '=', null)
                            ->where('chapter_id', '=', null)
                            ->where('lesson_id', '=', null)
                            ->where('academic_id', getAcademicId())
                            ->where('school_id', auth()->user()->school_id)
                            ->get();

        $response = [
            'success' => true,
            'data'    => $homeworkLists
        ];
        return response()->json($response, 200);
    }

    public function parentHomework($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        
        $homeworkLists = SmHomework::with(['subjects' => function($q){
            $q->select('id', 'subject_name');
        }])->select('created_at', 'submission_date', 'evaluation_date', 'active_status', 'marks', 'subject_id')
        ->where('school_id', auth()->user()->school_id)
        ->where(function ($que) use ($record) {
            return $que->where('class_id', $record->class_id)
                ->orWhereNull('class_id');
        })
        ->where(function ($que) use ($record) {
            return $que->where('section_id', $record->section_id)
                ->orWhereNull('section_id');
        })
        ->where('course_id', '=', null)
        ->where('chapter_id', '=', null)
        ->where('lesson_id', '=', null)
        ->where('academic_id', getAcademicId())
        ->where('school_id', auth()->user()->school_id)
        ->get();

        $response = [
            'success' => true,
            'data'    => $homeworkLists
        ];
        return response()->json($response, 200);
    }

    public function studentHomeworkView($class_id, $section_id, $homework_id)
    {
        $homeworkDetails = SmHomework::where('class_id', '=', $class_id)->where('section_id', '=', $section_id)->where('id', '=', $homework_id)->first();
        $response = [
            'success' => true,
            'data'    => $homeworkDetails
        ];
        return response()->json($response, 200);
    }

    public function studentHomeworkFileDownload($id)
    {
        $homeworkDetails = SmHomework::findOrFail($id);
        $file_path = $homeworkDetails->file;

        return response()->download($file_path);
    }
}
