@extends('backEnd.master')
@section('title')
@lang('common.academic_year')
@endsection
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.academic_year')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('common.academic_year')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($academic_year))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('academic-year')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif   
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($academic_year))
                                    @lang('common.edit')
                                @else
                                    @lang('common.add')
                                @endif
                                @lang('common.academic_year')
                            </h3>
                        </div>
                        @if(isset($academic_year))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('academic-year-update',$academic_year->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'academic-year',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @if(session()->has('message-success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success') }}
                                        </div>
                                        @elseif(session()->has('message-danger'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger') }}
                                        </div>
                                        @endif
                                        <div class="primary_input">
                                            <input class="primary_input_field{{ $errors->has('year') ? ' is-invalid' : '' }}"
                                                type="text" name="year" autocomplete="off" value="{{isset($academic_year)? $academic_year->year:''}}">
                                            <input type="hidden" name="id" value="{{isset($academic_year)? $academic_year->id: ''}}">
                                            <label>@lang('system_settings.year') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('year'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('year') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input class="primary_input_field{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text" name="title" autocomplete="off" value="{{isset($academic_year)? $academic_year->title:''}}">
                                            <input type="hidden" name="id" value="{{isset($academic_year)? $academic_year->id: ''}}">
                                            <label> @lang('system_settings.year_title')<span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('title'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters input-right-icon mt-40">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field date form-control{{ $errors->has('starting_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                placeholder=" @lang('system_settings.starting_date') *" name="starting_date" value="{{isset($academic_year)? date('m/d/Y',strtotime($academic_year->starting_date)): date('m/d/Y')}}">
                                            <span class="focus-border"></span>
                                            @if ($errors->has('starting_date'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('starting_date') }}</strong>
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
                                <div class="row no-gutters input-right-icon mt-40">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input class="primary_input_field date form-control{{ $errors->has('ending_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                placeholder="@lang('system_settings.ending_date')*" name="ending_date" value="{{isset($academic_year)? date('m/d/Y',strtotime($academic_year->ending_date)): date('m/d/Y')}}">
                                            <span class="focus-border"></span>
                                            @if ($errors->has('ending_date'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('ending_date') }}</strong>
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
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @if(isset($academic_year))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
                                            @endif

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0"> @lang('system_settings.academic_year_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                                @if(session()->has('message-success-delete') != "" ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="6">
                                        @if(session()->has('message-success-delete'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success-delete') }}
                                        </div>
                                        @elseif(session()->has('message-danger-delete'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger-delete') }}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>@lang('system_settings.year')</th>
                                    <th>@lang('system_settings.title')</th>
                                    <th>@lang('system_settings.starting_date')</th>
                                    <th>@lang('system_settings.ending_date')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach(academicYears() as $academic_year)
                                <tr>
                                    <td>{{$academic_year->year}}</td>
                                    <td>{{$academic_year->title}}</td>
                                    <td>{{date('jS M, Y', strtotime($academic_year->starting_date))}}</td>
                                    <td>{{date('jS M, Y', strtotime($academic_year->ending_date))}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('common.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{route('academic-year-edit', [$academic_year->id])}}">@lang('common.edit')</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteAcademicYearModal{{$academic_year->id}}"
                                                    href="#">@lang('common.delete')</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               <!--  -->

                                <div class="modal fade admin-query" id="deleteAcademicYearModal{{$academic_year->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('system_settings.delete_academic_year')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                     
                                                    {{ Form::open(['route' => array('academic-year-delete',$academic_year->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                 <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                 {{ Form::close() }}
                                                     
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')