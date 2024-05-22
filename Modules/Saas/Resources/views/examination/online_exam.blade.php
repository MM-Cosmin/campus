@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.online_exam')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
             <a href="#">@lang('exam.online_exam')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($online_exam))
            @if(userPermission(239))
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('online-exam')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                </div>
            </div>
            @endif
        @endif
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($online_exam))
                                    @lang('common.edit')
                                @else
                                    @lang('common.add')
                                @endif
                                @lang('exam.online_exam')
                            </h3>
                        </div>
                        @if(isset($online_exam))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('online-exam-update',$online_exam->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                            @if(userPermission(239))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'online-exam',
                            'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                        @endif
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        
                                        <div class="primary_input">
                                            <input class="primary_input_field{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text" name="title" autocomplete="off"  value="{{isset($online_exam)? $online_exam->title: old('title')}}">
                                            <input type="hidden" name="id"  value="{{isset($online_exam)? $online_exam->id: ''}}">
                                            <label>@lang('exam.exam_title') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('title'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                            @foreach($classes as $class)
                                                <option value="{{$class->id}}" {{isset($online_exam)? ($class->id == $online_exam->class_id? 'selected':''): (old('class') == $class->id? 'selected':'')}}>{{$class->class_name}}</option>

                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('class') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 mt-30-md" id="select_section_div">
                                        <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                            <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                            @if(isset($online_exam))
                                                @foreach($sections as $section)
                                                    <option value="{{$section->id}}" {{$online_exam->section_id == $section->id? 'selected': ''}}>{{$section->section_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('section'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('section') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12" id="select_subject_div">
                                        <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" id="select_subject" name="subject">
                                            <option data-display="@lang('common.select_subjects') *" value="">@lang('common.select_subjects')  *</option>
                                            @if(isset($online_exam))
                                                @foreach($subjects as $subject)
                                                    <option value="{{$subject->id}}" {{$online_exam->subject_id == $subject->id? 'selected': ''}}>{{$subject->subject_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('subject'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('subject') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row no-gutters input-right-icon mt-25">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field date form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" id="startDate" type="text" name="date" autocomplete="off" value="{{isset($online_exam)? date('m/d/Y', strtotime($online_exam->date)): (old('date') != ""? old('date'): date('m/d/Y'))}}" >
                                            <label>@lang('common.date')  <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('date'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('date') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="start-date-icon"></i>
                                        </button>
                                    </div>
                                    
                                </div>
                                <div class="row no-gutters input-right-icon mt-25">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field time form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}" type="text" name="start_time" value="{{isset($online_exam)? $online_exam->start_time: old('start_time')}}">
                                            <label>@lang('exam.start_time')</label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('start_time'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('start_time') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="" type="button">
                                            <i class="ti-timer"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row no-gutters input-right-icon mt-25">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field time  form-control{{ $errors->has('end_time') ? ' is-invalid' : '' }}" type="text" name="end_time"  value="{{isset($online_exam)? $online_exam->end_time: old('end_time')}}">
                                                <label>@lang('exam.end_time')</label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('end_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('end_time') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-timer"></i>
                                            </button>
                                        </div>
                                    </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field{{ $errors->has('percentage') ? ' is-invalid' : '' }}"
                                                type="text" name="percentage" autocomplete="off" value="{{isset($online_exam)? $online_exam->percentage: old('percentage')}}">
                                            <input type="hidden" name="id" value="{{isset($group)? $group->id: ''}}">
                                            <label>@lang('exam.minimum_percentage') *</label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('percentage'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('percentage') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <textarea class="primary_input_field{{ $errors->has('instruction') ? ' is-invalid' : '' }}" cols="0" rows="4" name="instruction">{{isset($online_exam)? $online_exam->instruction: old('instruction')}}</textarea>
                                            <label>@lang('exam.instruction') <span>*</span></label>
                                            
                                            @if($errors->has('instruction'))
                                                <span class="error text-danger"><strong>{{ $errors->first('instruction') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                               @php 
                                  $tooltip = "";
                                  if(userPermission(239)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($online_exam))
                                                @lang('common.update')
                                            @else
                                                @lang('exam.save')
                                            @endif
                                            @lang('exam.online_exam')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="url" value="{{Request::url()}}">
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('exam.online_exam_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                               
                                <tr>
                                    <th>@lang('exam.title')</th>
                                    <th>@lang('common.class_Sec')</th>
                                    <th>@lang('common.subject')</th>
                                    <th>@lang('exam.exam_date')</th>
                                    <th>@lang('common.status')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($online_exams as $online_exam)
                                <tr>
                                    <td>{{$online_exam->title}}</td>
                                    <td>
                                        @php
                                        if($online_exam->class !="" && $online_exam->section !="" ){
                                         echo $online_exam->class->class_name.'  ('.$online_exam->section->section_name.')';
                                        }
                                        @endphp
                                       </td>
                                    <td>{{$online_exam->subject!=""?$online_exam->subject->subject_name:""}}</td>
                                    <td>{{$online_exam->date != ""? dateConvert($online_exam->date):''}} <br> @lang('common.time'): {{date("h:i A", strtotime($online_exam->start_time)).' - '.date("h:i A", strtotime($online_exam->end_time))}}</td>
                                    <td>
                                        @if($online_exam->status == 0)
                                         <button class="primary-btn small bg-warning text-white border-0">@lang('common.pending')</button>
                                         @else
                                         <button class="primary-btn small bg-success text-white border-0">@lang('exam.published')</button>
                                         @endif
                                    </td>
                                    <td >
                                        <div class="dropdown d-flex">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('common.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(userPermission(242))
                                                    <a class="dropdown-item" href="{{route("manage_online_exam_question", [$online_exam->id])}}">@lang('exam.manage_question')</a>
                                                @endif
                                                @if($online_exam->end_date_time < $present_date_time && $online_exam->status == 1)
                                                    @if(userPermission(243))
                                                        <a class="dropdown-item" href="{{route("online_exam_marks_register", [$online_exam->id])}}">@lang('exam.marks_register')</a>
                                                    @endif
                                                @endif
                                                @if(userPermission(240))
                                                <a class="dropdown-item" href="{{route("online-exam-edit",$online_exam->id)}}">@lang('common.edit')</a>
                                                @endif
                                                @if(userPermission(241))
                                                <a class="dropdown-item deleteOnlineExam" data-toggle="modal" href="#" data-id="{{$online_exam->id}}" data-target="#deleteOnlineExam">@lang('common.delete')</a>
                                                @endif

                                            </div>

                                            @if($online_exam->status == 0)
                                            <a href="{{route('online_exam_publish', [$online_exam->id])}}">
                                                 <button class="primary-btn small bg-success text-white border-0">@lang('exam.published_now') </button>
                                             </a>
                                             @else
                                             
                                             @endif
                                             @if($online_exam->end_date_time < $present_date_time && $online_exam->status == 1)
                                                 @if(userPermission(244))
                                                 <a class="ml-3" href="{{route('online_exam_result', [$online_exam->id])}}">
                                                    <button class="primary-btn small bg-info text-white border-0">@lang('reports.result')</button>
                                                </a>
                                                @endif
                                            @endif
                                        </div>
                                        
                                    </td>
                                </tr>
                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade admin-query" id="deleteOnlineExam" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('common.delete_online_exam')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'online-exam-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" id="online_exam_id">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>



@endsection
@include('backEnd.partials.data_table_js')