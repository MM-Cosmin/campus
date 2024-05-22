
@extends('backEnd.master')
@section('title')
    @lang('student.student_report')
@endsection

@section('mainContent')
@push('css')
<style>
    table.dataTable thead .sorting_asc::after,
    table.dataTable thead .sorting::after,
    table.dataTable thead .sorting_desc::after {
    top: 10px !important;
    left: 0px !important;
}
</style>
@endpush
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.student_report') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('student.student_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/student-list', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'parent-registration']) }}
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
            
@if(isset($student_records))

 {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'enctype' => 'multipart/form-data'])}}

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('student.student_report')</h3>
                            </div>
                        </div>
                    </div>

                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-12 ">
                            <x-table>
                                <table id="table_id" class="display school-table" cellspacing="0" width="100%">
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
                                            <th>@lang('common.class') (@lang('common.section'))</th>
                                         
                                            <th>@lang('student.admission_no')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('student.father_name')</th>
                                            <th>@lang('common.date_of_birth')</th>
                                            <th>@lang('common.gender')</th>
                                      
                                            <th>@lang('common.phone')</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                     
                                        @foreach($student_records as $record)
                                        <tr>
                                            <td>
                                               {{$record->class->class_name}}
                                                ( {{$record->section != ""? $record->section->section_name:""}} )</td>
                                            <td>{{@$record->student->admission_no}}</td>
                                            <td>{{@$record->student->first_name.' '.@$record->student->last_name}}</td>
                                            <td>{{@$record->student->parents !="" ? @$record->student->parents->fathers_name:""}}</td>
                                            <td>
                                               
                                            {{@$record->student->date_of_birth != ""? dateConvert(@$record->student->date_of_birth):''}}
    
                                            </td>
                                            <td>{{@$record->student->gender != ""? @$record->student->gender->base_setup_name:""}}</td>
                                          
                                            <td>{{@$record->student->mobile}}</td>
                                         
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

@endif

    </div>
  
</section>


@endsection
@include('backEnd.partials.data_table_js')
{{-- @section('script')
<script src="{{asset('public/backEnd/saas/js1/custom.js')}}"></script>
@endsection --}}
