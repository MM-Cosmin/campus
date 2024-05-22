<!DOCTYPE html>
<html lang="en">
<head>
    <title>Progress Card </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<style>
    th {
        border: 1px solid #ddd;
        text-align: center;
        padding: 5px !important;
        font-size: 11px;
    }

    td {
        text-align: center;
        padding: 5px !important;
        font-size: 11px;
    }

    td.subject-name {
        text-align: left;
        padding-left: 10px !important;
    }


    .studentInfoTable {
        width: 100%;
        padding: 0px !important;
    }

    .studentInfoTable td {
        padding: 0px !important;
        text-align: left;
        padding-left: 15px !important;
    }

    h4 {
        text-align: left !important;
    }
</style>
<body>
<div class="container-fluid">
    <table style="width: 100%; border: 0px;">
        <tr>
            <td style="width: 30%">
                <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt="">
            </td>
            <td style="text-align: left; width: 70%">
                <h3 class="text-white"> {{isset($school_name)?$school_name:'Infix School Management ERP'}} </h3>
                <p class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
            </td>
        </tr>
    </table>


    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="single-report-admit">
                            <div class="card">

                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="offset-2 col-md-8">

                                                <table class="table">
                                                    <tr>
                                                        <td>
                                                            <p class="text-center">Student Info</p>
                                                            <hr>
                                                            <table class="studentInfoTable">
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        Name of Student :
                                                                    </td>
                                                                    <td>
                                                                        {{$student_detail->full_name}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        Roll Number :
                                                                    </td>
                                                                    <td>
                                                                        {{$student_detail->roll_no}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        Admission Number :
                                                                    </td>
                                                                    <td>
                                                                        {{$student_detail->admission_no}}
                                                                    </td>
                                                                </tr>


                                                            </table>
                                                        </td>
                                                        <td style="padding-left: 30px">
                                                            <p class="text-center">Exam Info</p>
                                                            <hr>
                                                            <table class="studentInfoTable">

                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        Academic Class :
                                                                    </td>
                                                                    <td>
                                                                        {{@$class->class_name}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        Academic Section :
                                                                    </td>
                                                                    <td>
                                                                        {{@$section->section_name}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        Date of birth :
                                                                    </td>
                                                                    <td>
                                                                        {{$student_detail->date_of_birth}}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <h4 style="text-align: center;">Progress card report</h4>
                                        <hr>


                                        <table class="w-100 mt-40 mb-20 table   table-bordered marksheet">
                                            <thead>
                                            <tr style="text-align: center;">
                                                <th rowspan="2">@lang('common.subjects')</th>
                                                @foreach($exams as $exam)
                                                    @php
                                                        $exam_type = $exam->examType;
                                                    @endphp
                                                    <th colspan="2"
                                                        style="text-align: center;">{{$exam_type->title}}</th>
                                                @endforeach
                                                <th rowspan="2">@lang('exam.result')</th>
                                                <th rowspan="2">@lang('exam.grade')</th>
                                                <th rowspan="2">@lang('exam.gpa')</th>

                                            </tr>
                                            <tr style="text-align: center;">
                                                @foreach($assinged_exam_types as $assinged_exam_type)

                                                    <th>@lang('exam.marks')</th>
                                                    <th>@lang('exam.grade')</th>

                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>


                                            @php
                                                $total_fail = 0;
                                                $total_marks = 0;
                                            @endphp
                                            @foreach($subjects as $data)
                                                <tr style="text-align: center">
                                                    <td>{{$data->subject !=""?$data->subject->subject_name:""}}</td>
                                                    <?php
                                                    $totalSumSub = 0;
                                                    $totalSubjectFail = 0;
                                                    $TotalSum = 0;
                                                    foreach($assinged_exam_types as $assinged_exam_type){

                                                    $result = $data->markBySubject->where('exam_term_id', $assinged_exam_type)->where('student_id', $student_id);
                                                    if (!empty($result)) {
                                                        $final_results = $data->resultBySubject->where('exam_type_id', $assinged_exam_type)->where('student_id', $student_id)->first();

                                                    }

                                                    if($result->count() > 0){
                                                    ?>
                                                    <td>
                                                        @php

                                                            if($final_results != ""){
                                                                echo $final_results->total_marks;
                                                                $totalSumSub = $totalSumSub + $final_results->total_marks;
                                                                $total_marks = $total_marks + $final_results->total_marks;

                                                            }else{
                                                                echo 0;
                                                            }

                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @php

                                                            if($final_results != ""){
                                                                if($final_results->total_gpa_grade == "F"){
                                                                    $totalSubjectFail++;
                                                                    $total_fail++;
                                                                }
                                                                echo $final_results->total_gpa_grade;
                                                            }else{
                                                                echo '-';
                                                            }

                                                        @endphp
                                                    </td>
                                                    <?php
                                                    }else{ ?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <?php

                                                    }
                                                    }
                                                    ?>

                                                    <td>{{$totalSumSub}}</td>
                                                    <td>
                                                        @php
                                                            if($totalSubjectFail > 0){
                                                                echo 'F';
                                                            }else{
                                                                $totalSumSub = $totalSumSub / count($assinged_exam_types);

                                                                $mark_grade = markGpa($totalSumSub);

                                                                echo @$mark_grade->grade_name;
                                                            }
                                                        @endphp
                                                    </td>

                                                    <td>
                                                        @php
                                                            if($totalSubjectFail > 0){
                                                                echo 'F';
                                                            }else{

                                                                $mark_grade = markGpa($totalSumSub);

                                                                echo @$mark_grade->gpa;
                                                            }
                                                        @endphp
                                                    </td>

                                                </tr>
                                            @endforeach
                                            @php
                                                $colspan = 4 + count($assinged_exam_types) * 2;

                                            @endphp
                                            <tr>
                                                <td colspan="{{$colspan / 2 - 1}}"
                                                    class="text-center">@lang('exam.total_marks')</td>
                                                <td colspan="{{$colspan / 2 + 1}}"
                                                    class="text-center">{{$total_marks}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{$colspan / 2 - 1}}"
                                                    class="text-center">@lang('exam.total_grade')</td>
                                                <td colspan="{{$colspan / 2 + 1}}" class="text-center">
                                                    @php
                                                        if($total_fail != 0){




                                                            echo 'F';
                                                        }else{
                                                            $total_exam_subject = count($subjects) + count($assinged_exam_types);
                                                            $average_mark = $total_marks / $total_exam_subject;

                                                            $average_grade = markGpa($totalSumSub);

                                                            echo @$average_grade->grade_name;
                                                        }
                                                    @endphp

                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{$colspan / 2 - 1}}"
                                                    class="text-center">@lang('reports.total_gpa')</td>
                                                <td colspan="{{$colspan / 2 + 1}}" class="text-center">
                                                    @php
                                                        if($total_fail != 0){
                                                            echo '0.00';
                                                        }else{
                                                            $total_exam_subject = count($subjects) + count($assinged_exam_types);
                                                            $average_mark = $total_marks / $total_exam_subject;

                                                            $average_grade = markGpa($totalSumSub);

                                                            echo @$average_grade->gpa;
                                                        }
                                                    @endphp

                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>
