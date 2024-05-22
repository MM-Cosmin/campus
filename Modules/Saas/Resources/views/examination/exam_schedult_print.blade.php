<!DOCTYPE html>
<html lang="en">
<head>
  <title>Exam Schedule </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<style>
 table,th,tr,td{
     font-size: 11px !important;
 }
 
</style>
<body>
 

@php 
    $exam=App\SmExamType::find($exam_id);
    $class=App\SmClass::find($class_id);
    $section=App\SmSection::find($section_id);
@endphp
<div class="container-fluid"> 
                    
                    <table  cellspacing="0" width="100%">
                        <tr>
                            <td> 
                                <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt=""> 
                            </td>
                            <td> 
                                <h3 style="font-size:22px !important" class="text-white"> {{isset($school_name)?$school_name:'Infix School Management ERP'}} </h3> 
                                <p style="font-size:18px !important" class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p> 
                                <p style="font-size:15px !important" class="text-white mb-0"> Exam Schedule </p> 
                          </td>
                            <td style="text-aligh:center"> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray;" align="left" class="text-white">Exam :  {{ $exam->title}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Class: {{ $class->class_name}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Section: {{ $section->section_name}} </p> 
                               
                          </td>
                        </tr>
                    </table>

                    <hr>
           
                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                    
                         
                        <tr>
                            <th width="10%">@lang('common.date')</th>
                            @foreach($exam_periods as $exam_period)
                            <th>{{$exam_period->period}}<br>{{date('h:i A', strtotime($exam_period->start_time)).'-'.date('h:i A', strtotime($exam_period->end_time))}}</th>
                            @endforeach
                        </tr>
               
                        @foreach($exam_dates as $exam_date)
                        <tr>
                            <td>{{$exam_date != ""? dateConvert($exam_date):''}}</td>
                            @foreach($exam_periods as $exam_period)

                            @php

                                $assigned_routine = App\SmExamSchedule::examScheduleSubject($class_id, $section_id, $exam_id, $exam_period->id, $exam_date);

                            @endphp
                            
                            <td>
                            @if($assigned_routine != "")

                                <div class="col-lg-6">
                                    <span class="">{{@$assigned_routine->classRoom->room_no}}</span>
                                    <br>
                                    <span class="">
                                        
                                        {{@$assigned_routine->subject->subject_name}}

                                    </span></br>
                                    
                            @endif
                            </td>

                            @endforeach
                                
                        </tr>
                        @endforeach
                        
                </table>
        </div>  
 

</body>
</html>
    
