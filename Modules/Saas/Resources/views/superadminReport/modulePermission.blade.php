@extends('backEnd.master')
@section('mainContent')


<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.module_permission') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('reports.module_permission') </a> 
            </div>
        </div>
    </div>
</section>


<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('saas::saas.institution_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

               

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>

                                @if(session()->has('message-success') != "" ||
                                session()->has('message-danger') != "" || $errors->count() == 1)
                                <tr>
                                    <td colspan="4">
                                        @if(session()->has('message-success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success') }}
                                        </div>
                                        @elseif(session()->has('message-danger'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger') }}
                                        </div>
                                        @endif

                                        
                                            @foreach ($errors->all() as $error)
                                                <div class="alert alert-danger">{{ $error }}</div>
                                            @endforeach
                                        
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th width="50%">@lang('reports.institution')</th>
                                    {{-- <th width="20%">@lang('common.type')</th>
                                    <th width="30%">@lang('reports.school')</th>
 --}}                                <th width="50%">@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($institutions as $institution)

                                
                                
                                    <tr>
                                        <td>{{$institution->school_name}}</td>
                                        {{-- <td>{{$role->type}}</td>
                                        <td>
                                                <select class="primary_select form-control" name="institution" id="institution">
                                                    <option data-display="@lang('common.select_institution') *" value="">@lang('common.select_institution') *</option>
                                                    @foreach($institutions as $institution)
                                                    <option value="{{ $institution->id }}">{{ $institution->school_name }}</option>
                                                    @endforeach
                                                </select>
                                        </td> --}}
                                        <td>
                                               
                                                <a href="{{route('administrator/module-permission', [$institution->id])}}" class="primary-btn small fix-gr-bg"> @lang('reports.assign_permission') </a>
                                          
                                            
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


@endsection
@include('backEnd.partials.data_table_js')