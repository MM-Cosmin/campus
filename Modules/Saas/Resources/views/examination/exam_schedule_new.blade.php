@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam_schedule')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="#">@lang('exam.exam_schedule')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>
                @if(userPermission(218))
                    <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                        <a href="{{route('exam_schedule_create')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('exam.add_exam_schedule')
                        </a>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12">
                   
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_schedule_report_search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                        <option data-display="Select Exam *" value="">Select Exam *</option>
                                        @foreach($exam_types as $exam)
                                            <option value="{{$exam->id}}">{{$exam->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('exam'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('exam') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}"  {{( old("class") == $class->id ? "selected":"")}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_section_div">
                                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                    </select>
                                    @if ($errors->has('section'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('section') }}</strong>
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
        </div>
    </section>
@if(isset($assign_subjects))

<section class="mt-20">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-6 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('exam.exam_schedule')</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <a href="{{route('exam_schedule_print', [$exam_id, $class_id, $section_id])}}" class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i> Print</a>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <table class="display school-table school-table-style" cellspacing="0" width="100%">
                    <thead>
                        @if(session()->has('success') != "" || session()->has('danger') != "")
                        <tr>
                            <td colspan="20">
                                @if(session()->has('success') != "")
                            
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            
                                @else

                                <div class="alert alert-success">
                                    {{ session()->get('danger') }}
                                </div>

                            </td>

                            @endif
                        </tr>
                        @endif
                        <tr>
                            <th width="10%">@lang('common.date')</th>
                            @foreach($exam_periods as $exam_period)
                            <th>{{$exam_period->period}}<br>{{date('h:i A', strtotime($exam_period->start_time)).'-'.date('h:i A', strtotime($exam_period->end_time))}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
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
                        
                    </tbody>
                </table>
            </div>
        </div>  
    </div>
</section>

@endif



@endsection
