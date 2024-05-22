@extends('backEnd.master')
@section('title')
@lang('exam.exam_attendance')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam_attendance') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="#">@lang('exam.exam_attendance')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
                @if(userPermission(221))
                    <div class="col-lg-6 text-right col-md-6">
                        <a href="{{route('exam_attendance_create')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('exam.attendance_create')
                        </a>
                    </div>
                    @endif
                </div>

            <div class="row">
                <div class="col-lg-12">
                    @if(session()->has('message-success') != "" || session()->has('message-danger') != "")
                        @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                        @endif
                        @if(session()->has('message-danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('message-danger') }}
                        </div>
                        @endif
                    @endif
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_attendance', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                    <div class="col-lg-3 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                        <option data-display="@lang('exam.select_exam') *" value="">@lang('exam.select_exam') *</option>
                                        @foreach($exams as $exam)
                                            <option value="{{$exam->id}}">{{$exam->title}}</option>
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
                                        <option value="{{$class->id}}"  {{( old('class') == $class->id ? "selected":"")}}>{{$class->class_name}}</option>
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
                                    <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" id="select_subject" name="subject">
                                        <option data-display="@lang('common.select_subjects') *" value="">@lang('common.select_subjects')*</option>
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
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        @if(isset($exam_attendance_childs))
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('exam.exam_attendance')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id_table" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    @if(session()->has('message-danger') != "")
                                    <tr>
                                        <td colspan="9">
                                            @if(session()->has('message-danger'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('message-danger') }}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th width="25%">@lang('student.admission_no')</th>
                                        <th width="25%">@lang('student.student_name')</th>
                                        <th width="25%">@lang('student.roll_no')</th>
                                        <th width="25%">@lang('exam.attendance')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($exam_attendance_childs as $student)
                                    <tr>
                                        <td>{{$student->studentInfo !=""?$student->studentInfo->admission_no:""}}<input type="hidden" name="id[]" value="{{$student->student_id}}"></td>
                                        <td>{{$student->studentInfo !=""?$student->studentInfo->first_name.' '.$student->studentInfo->last_name:""}}</td>
                                        <td>{{$student->studentInfo !=""?$student->studentInfo->roll_no:""}}</td>
                                        <td>
                                            @if($student->attendance_type == 'P')
                                             <button class="primary-btn small bg-success text-white border-0">@lang('exam.present')</button>
                                             @else
                                             <button class="primary-btn small bg-danger text-white border-0">@lang('exam.absent')</button>
                                             @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </section>

@endsection
@include('backEnd.partials.data_table_js')