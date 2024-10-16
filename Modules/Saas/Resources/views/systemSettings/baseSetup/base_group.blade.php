@extends('backEnd.master')

@section('title')
@lang('admin.base_group')
@endsection
@section('mainContent')


<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('admin.base_group') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('admin.base_group')</a>
            </div>
        </div>
    </div>
</section>


<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($base_group))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('base_group')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($base_group))
                                    @lang('admin.edit_base_group')

                                @else
                                    @lang('admin.add_base_group')

                                @endif
                              
                            </h3>
                        </div>
                        @if(isset($base_group))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'base_group_update',
                        'method' => 'POST']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'base_group_store',
                        'method' => 'POST']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row  mt-25">
                                    <div class="col-lg-12">
                                     
                                        <div class="primary_input">
                                            <input class="primary_input_field{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off" value="{{isset($base_group)? $base_group->name: ''}}">
                                            <input type="hidden" name="id" value="{{isset($base_group)? $base_group->id: ''}}">
                                            <label>@lang('common.name') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @if(isset($base_group))
                                                @lang('admin.update_base_group')
                                            @else
                                                @lang('admin.save_base_group')
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
                            <h3 class="mb-0">@lang('admin.base_group_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                             
                                <tr>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($base_groups as $base_group)
                                <tr>
                                    <td>{{$base_group->name}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('common.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{route('administrator_base_group_edit', [$base_group->id])}}">@lang('common.edit')</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteBaseGroupModal{{$base_group->id}}"
                                                    href="#">@lang('common.delete')</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                  <div class="modal fade admin-query" id="deleteBaseGroupModal{{$base_group->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('admin.delete_base_group')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                    <a href="{{route('base_group_delete', [$base_group->id])}}" class="text-light">
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                     </a>
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
