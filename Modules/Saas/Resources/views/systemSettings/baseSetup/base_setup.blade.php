@extends('backEnd.master')
@section('title')
@lang('system_settings.base_setup')
@endsection 
@section('mainContent')
@push('css')
<link rel="stylesheet" href="{{asset('/Modules/RolePermission/public/css/style.css')}}">
<style type="text/css">
    .erp_role_permission_area {
    display: block !important;
}

.single_permission {
    margin-bottom: 0px;
}
.erp_role_permission_area .single_permission .permission_body > ul > li ul {
    display: grid;
    margin-left: 8px;
    grid-template-columns: repeat(3, 1fr);
    /* grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); */
}
.erp_role_permission_area .single_permission .permission_body > ul > li ul li {
    margin-right: 15px;
   
}
.mesonary_role_header{
    column-count: 2;
    column-gap: 30px;
}
.single_role_blocks {
    display: inline-block;
    background: #fff;
    box-sizing: border-box;
    width: 100%;
    margin: 0 0 5px;
}
.erp_role_permission_area .single_permission .permission_body > ul > li {
  padding: 15px 25px 12px 25px;
}
.erp_role_permission_area .single_permission .permission_header {
  padding: 20px 35px 0px 25px;
  position: relative;
}
@media (min-width: 320px) and (max-width: 1199.98px) { 
    .mesonary_role_header{
    column-count: 1;
    column-gap: 30px;
}
 }
@media (min-width: 320px) and (max-width: 767.98px) { 
    .erp_role_permission_area .single_permission .permission_body > ul > li ul {
    grid-template-columns: repeat(2, 1fr);
    grid-gap:10px
    /* grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); */
    }
 }
.permission_header{
    position: relative;
}

.arrow::after {
    position: absolute;
    content: "\e622";
    top: 50%;
    right: 12px;
    height: auto;
    font-family: 'themify';
    color: #fff;
    font-size: 18px;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    right: 22px;
}
.arrow.collapsed::after {
    content: "\e61a";
    color: #fff;
    font-size: 18px;
}
.erp_role_permission_area .single_permission .permission_header div {
    position: relative;
    top: -5px;
    position: relative;
    z-index: 999;
}
.erp_role_permission_area .single_permission .permission_header div.arrow {
    position: absolute;
    width: 100%;
    z-index: 0;
    left: 0;
    bottom: 0;
    top: 0;
    right: 0;
}
.erp_role_permission_area .single_permission .permission_header div.arrow i{
    color:#FFF;
    font-size: 20px;
}
.rtl .arrow::after {
    right: auto;
    left: 22px;
}
.rtl .common-radio:empty ~ label{
    float: right !important;
}

.rtl .erp_role_permission_area .single_permission .permission_body > ul > li ul li {
    margin-right: 0;
}
.rtl .erp_role_permission_area .single_permission .permission_body > ul > li ul label {
	
	white-space: nowrap;
}
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting:after {
    top: 10px !important;
    left: 4px !important;
}
</style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.base_setup')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.base_setup')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        @if(isset($base_setup))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('base_setup')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($base_setup))
                                    @lang('system_settings.edit_base_setup')
                                @else
                                    @lang('system_settings.add_base_setup')

                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($base_setup))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'base_setup_update',
                        'method' => 'POST']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'base_setup_store',
                        'method' => 'POST']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <select class="primary_select form-control{{ $errors->has('base_group') ? ' is-invalid' : '' }}"
                                            name="base_group">
                                            <option data-display="@lang('system_settings.base_group') *" value="">@lang('system_settings.base_group')*</option>
                                            @foreach($base_groups as $base_group)
                                            @if(isset($base_setup))
                                            <option value="{{$base_group->id}}"
                                                {{$base_group->id == $base_setup->base_group_id? 'selected': ''}}>{{$base_group->name}}</option>
                                            @else
                                            <option value="{{$base_group->id}}">{{$base_group->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @if($errors->has('base_group'))
                                        <span class="text-danger" role="alert">
                                            {{ $errors->first('base_group') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row  mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label>@lang('common.name') <span>*</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" value="{{isset($base_setup)? $base_setup->base_setup_name: ''}}">
                                            <input type="hidden" name="id" value="{{isset($base_setup)? $base_setup->id: ''}}">
                                          
                                           
                                            @if ($errors->has('name'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @if(isset($base_setup))
                                                @lang('system_settings.update_base_setup')
                                            @else
                                                @lang('system_settings.save_base_setup')
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
                            <h3 class="mb-0">@lang('system_settings.base_setup_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row base-setup">
                    <div class="col-lg-12">
                        <x-table>
                            <table class="display school-table school-table-data" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="33%">@lang('system_settings.base_type')</th>
                                        <th width="33%">@lang('common.label')</th>
                                        <th width="33%">@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="pr-0">
                                            @php $i = 0; @endphp
                                                @foreach($base_groups as $base_group)
                                                <div class="erp_role_permission_area ">
                                                    <div class="single_role_blocks">
                                                        <div class="single_permission" id="">
                                                            <div class="permission_header d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    <label for="Main_Module{{$base_group->id}}">{{$base_group->name}}</label>
                                                                </div>
                                                                <div class="arrow collapsed" data-toggle="collapse" data-target="#Role{{$base_group->id}}"></div>
                                                            </div>
                                                            @php
                                                                $base_setups = $base_group->baseSetups;
                                                            @endphp
                                                            <div id="Role{{$base_group->id}}" class="collapse">
                                                                <div  class="permission_body">
                                                                    <ul>
                                                                        <li>
                                                                            @foreach($base_setups as $base_setup)
                                                                            <div class="row py-3 border-bottom align-items-center">
                                                                                <div class="offset-lg-4 col-lg-4">{{$base_setup->base_setup_name}}</div>
                                                                                <div class="col-lg-4">
                                                                                    <x-drop-down>
                                                                                            <a class="dropdown-item" href="{{route('base_setup_edit', [$base_setup->id])}}">@lang('common.edit')</a>
                                                                                            <a class="dropdown-item deleteBaseSetupModal" href="#" data-toggle="modal" data-target="#deleteBaseSetupModal" data-id="{{$base_setup->id}}">@lang('common.delete')</a>
                                                                                    </x-drop-down>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </td>
                                            <td></td>
                                            <td></td>
                                    </tr>
                                </tbody>
                            </table>
                       </x-table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<div class="modal fade admin-query" id="deleteBaseSetupModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('system_settings.delete_base_setup')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'base_setup_delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" value="" id="base_setup_id">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>



@endsection
@include('backEnd.partials.data_table_js')