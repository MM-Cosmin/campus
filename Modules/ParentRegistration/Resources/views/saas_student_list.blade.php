@extends('backEnd.master')
@section('title')
@lang('common.student_list')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20 up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('parentregistration::parentRegistration.manage_student')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('parentregistration::parentRegistration.new_registration')</a>
                <a href="#">@lang('common.student_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
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
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'parentregistration/saas-student-list', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'parent-registration']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                 <div class="col-lg-3">
                                    <select class="primary_select  form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" id="select-school">
                                        <option data-display="@lang('common.select_institution')" value="">@lang('common.select_institution') *</option>
                                        @foreach($institutions as $institution)
                                        <option value="{{ $institution->id }}">{{ $institution->school_name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('institution'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('institution') }}
                                    </span>
                                    @endif
                                </div>

                                <div class="col-lg-3 mt-30-md" id="academic-year-div">

                                    <select class="primary_select  form-control" name="academic_year" id="select-academic-year">
                                        <option data-display="Select Academic Year" value="">@lang('common.select_academic_year')</option>

                                        
                                    </select>
                                       
                                </div>

                                <div class="col-lg-3 mt-30-md" id="class-div">
                                    <select class="primary_select  {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select-class" name="class">
                                        <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                        
                                    </select>
                                    @if ($errors->has('class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md" id="section-div">
                                    <select class="primary_select {{ $errors->has('current_section') ? ' is-invalid' : '' }}" id="select-section" name="section">
                                        <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                    </select>
                                </div>
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
            
            @if (@$students)
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('common.student_list') ({{@$students ? @$students->count() : 0}})</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        @if(session()->has('message-success') != "" ||
                                        session()->get('message-danger') != "")
                                        <tr>
                                            <td colspan="10">
                                                @if(session()->has('message-success'))
                                                <div class="alert alert-success">
                                                    {{ session()->get('message-success') }}
                                                </div>
                                                @elseif(session()->has('message-danger'))
                                                <div class="alert alert-danger">
                                                    {{ session()->get('message-danger') }}
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('common.class_sec')</th>
                                            <th>@lang('common.academic_year')</th>
                                            <th>@lang('common.date_of_birth')</th>
                                            <th>@lang('student.guardian_name')</th>
                                            <th>@lang('student.guardian_mobile')</th>
                                            <th>@lang('common.actions')</th>
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        @foreach(@$students as $student)
                                        <tr>
                                            <td>{{$student->first_name.' '.$student->last_name}}</td>
    
                                            <td>{{@$student->class->class_name}}({{@$student->section->section_name}})</td>
                                            <td>{{@$student->academicYear->year}}</td>
                                            <td  data-sort="{{strtotime($student->date_of_birth)}}" >
                                               
                                            {{$student->date_of_birth != ""? dateConvert($student->date_of_birth):''}}
    
                                            </td>
                                            <td>{{$student->guardian_name}}</td>
                                            <td>{{$student->guardian_mobile}}</td>
                                            <td>
                                                <x-drop-down/>
    
                                                    <a class="dropdown-item" href="{{route('parentregistration/student-view', [$student->id])}}"  data-id="{{$student->id}}"  >@lang('common.view')</a>
    
                                                     <a onclick="deleteId({{$student->id}});" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="{{$student->id}}"  >@lang('common.approve')</a>
    
                                                     <a onclick="enableId({{$student->id}});" class="dropdown-item" href="#" data-toggle="modal" data-target="#enableStudentModal" data-id="{{$student->id}}"  >@lang('common.delete')</a>
                                                   
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        @endforeach
    
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
    </div>
</section>

<div class="modal fade admin-query" id="deleteStudentModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('parentregistration::parentRegistration.student_approve')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_approve')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'parentregistration/student-approve', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <input type="hidden" name="id" value="{{@$student->id}}" id="student_delete_i">  {{-- using js in main.js --}}
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.approve')</button>
                     {{ Form::close() }}
                </div>

            </div>

        </div>
    </div>
</div>

<div class="modal fade admin-query" id="enableStudentModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('parentregistration::parentRegistration.delete_student')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'parentregistration/student-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" value="" id="student_enable_i">  {{-- using js in main.js --}}
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@include('backEnd.partials.data_table_js')