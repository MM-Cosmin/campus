@extends('backEnd.master')
@section('title')
@lang('exam.marks_grade')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Examinations </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">Examinations</a>
                <a href="{{route('marks_register')}}">Marks Register</a>
                <a href="{{route('marks_register_create')}}">Fill Marks</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">Select Criteria </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('message-success') != "")
                    @if(session()->has('message-success'))
                    <div class="alert alert-success">
                        {{ session()->get('message-success') }}
                    </div>
                    @endif
                @endif
                 @if(session()->has('message-danger') != "")
                    @if(session()->has('message-danger'))
                    <div class="alert alert-danger">
                        {{ session()->get('message-danger') }}
                    </div>
                    @endif
                @endif
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'marks_register_create', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                            <div class="col-lg-3 mt-30-md">
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="Select Exam *" value="">Select Exam *</option>
                                    @foreach($exam_types as $exam_type)
                                        <option value="{{$exam_type->id}}" {{isset($exam_id)? ($exam_id == $exam_type->id? 'selected':''):''}}>{{$exam_type->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('exam') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('class') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md" id="select_section_div">
                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                </select>
                                @if ($errors->has('section'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="col-lg-3 mt-30-md" id="select_subject_div">
                                <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject" id="select_subject" name="subject">
                                    <option data-display="Select subject *" value="">Select subject *</option>
                                </select>
                                @if ($errors->has('subject'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    search
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

@if(isset($students))


<section class="mt-20">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-6 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">Fill Marks</h3>
                </div>
            </div>
        </div>


    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'marks_register_store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'marks_register_store']) }} 


        <input type="hidden" name="exam_id" value="{{$exam_id}}">
        <input type="hidden" name="class_id" value="{{$class_id}}">
        <input type="hidden" name="section_id" value="{{$section_id}}">
        <input type="hidden" name="subject_id" value="{{$subject_id}}"> 
        }
        <div class="row">
            <div class="col-lg-12">
                <table class="display school-table school-table-style" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <th rowspan="2" >Admission No.</th>
                            <th rowspan="2" >Roll No.</th>
                            <th rowspan="2" >Student</th>
                            <th colspan="{{$number_of_exam_parts}}"> {{$subjectNames->subject_name}}</th> 
                            <th rowspan="2">Is Absent</th>
                        </tr>
                        <tr>
                            @foreach($marks_entry_form as $part)
                            <th>{{$part->exam_title}} ( {{$part->exam_mark}} ) </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>    
                              
                        @php $colspan = 3; $counter = 0;  @endphp
                        @foreach($students as $student)
                         
                        @php
                            $absent_check = App\SmMarksRegister::is_absent_check($exam_id, $class_id, $section_id, $subject_id, $student->id);
                        @endphp
                        <tr>
                            <td>
                                <input type="hidden" name="student_ids[]" value="{{$student->id}}">
                                <input type="hidden" name="student_rolls[{{$student->id}}]" value="{{$student->roll_no}}">
                                <input type="hidden" name="student_admissions[{{$student->id}}]" value="{{$student->admission_no}}">
                                {{$student->admission_no}}
                            </td>
                            <td>{{$student->roll_no}}</td>
                            <td>{{$student->full_name}}</td>
                            @php 
                            $entry_form_count=0; 
                            @foreach($marks_entry_form as $part)
                            @php $d = 5 + rand()%5;   @endphp
                            <td>
                                <div class="primary_input mt-10">
                                <input type="hidden" name="exam_setup_ids[]" value="{{$part->id}}">
                                <?php $search_mark = App\SmMarkStore::get_mark_by_part($student->id, $part->exam_term_id, $part->class_id, $part->section_id, $part->subject_id, $part->id); ?>
                                    <input class="primary_input_field marks_input" type="text" name="marks[{{$student->id}}][{{$part->id}}]" value="{{!empty($search_mark)?$search_mark:0}}" {{$absent_check->attendance_type == 'A'? 'readonly':''}}>
                                    <input class="primary_input_field marks_input" type="hidden" name="exam_Sids[{{$student->id}}][{{$entry_form_count++}}]" value="{{$part->id}}">
                                    <label>{{$part->exam_title}} Mark</label>
                                    <span class="focus-border"></span>
                                </div>                                
                            </td>
                            @endforeach

                             <?php $is_absent_check = App\SmMarkStore::is_absent_check($student->id, $part->exam_term_id, $part->class_id, $part->section_id, $part->subject_id); ?>

                            <td>
                                <div class="primary_input">
                                    

                                    @if($absent_check->attendance_type == 'P')
                                    <button class="primary-btn small fix-gr-bg" type="button">@lang('exam.present')</button>
                                    @else
                                    <button class="primary-btn small bg-danger text-white border-0" type="button">@lang('exam.absent')</button>
                                    @endif

                                    {{-- <input type="checkbox" id="subject_{{$student->id}}_{{$student->admission_no}}" class="common-checkbox" name="abs[{{$student->id}}]" value="1" {{$absent_check->attendance_type == 'A'? 'checked':''}}{{$is_absent_check == 1? 'checked':''}} {{$absent_check->attendance_type == 'A'? 'disabled':''}}>
                                    <label for="subject_{{$student->id}}_{{$student->admission_no}}">Yes</label> --}}


                                    @if($absent_check->attendance_type == 'A')
                                    <input type="hidden" name="absent_students[]" value="{{$student->id}}">
                                    @endif
                                </div>
                                    
                            </td>

                        </tr>
                        @endforeach 
                    </tbody>
                </table>
                @if(userPermission(224))
                    <div class="col-lg-12 mt-20 text-right">
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <span class="ti-check"></span>
                            Save
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endif

@endsection
