@extends('backEnd.master')
@section('title')
@lang('style.background_settings')
@endsection
@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('style.background_settings')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('style.background_settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            @if(isset($visitor))
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{route('visitor')}}" class="primary-btn small fix-gr-bg">
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
                                <h3 class="mb-30">
                                    @lang('common.add')   @lang('style.style')
                                </h3>
                            </div>
                            @if(isset($visitor))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'school-background-settings-update',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'school-background-settings-store',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">





                                   {{--  <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <select class="primary_select form-control{{ $errors->has('style') ? ' is-invalid' : '' }}" name="style" id="style">
                                                <option data-display="@lang('style.select_position') *" value="">@lang('style.select_position') *</option>
                                                <option value="1">Dashboard Background</option>
                                                <option value="2">Login Page Background</option>
                                            </select>
                                            @if ($errors->has('style'))
                                            <span class="invalid-feedback invalid-select" role="alert">
                                                <strong>{{ $errors->first('style') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div> --}}




                                    <div class="row mt-20">
                                        <div class="col-lg-12"> 
                                            <select class="primary_select form-control" name="background_type" id="background-type">
                                                <option data-display="@lang('style.background_type') *" value="">@lang('style.background_type') *</option>            
                                                <option value="color">Color</option>
                                                <option value="image">Image (1920x1400)</option>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="row mt-40" id="background-color">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input class="primary_input_field{{ $errors->has('color') ? ' is-invalid' : '' }}" type="color" name="color" autocomplete="off" value="{{isset($visitor)? $visitor->purpose: old('color')}}">
                                                <input type="hidden" name="id" value="{{isset($visitor)? $visitor->id: ''}}">
                                                <label>@lang('style.color')<span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('color'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('color') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>






                                    <div class="row no-gutters input-right-icon mt-35" id="background-image">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field" id="placeholderInput" type="text" placeholder="{{isset($visitor)? ($visitor->file != ""? getFilePath3($visitor->file):'Background Image *'):'Background Image *'}}"
                                                       readonly>
                                                <span class="focus-border"></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="browseFile">@lang('common.browse')</label>
                                                <input type="file" class="d-none" id="browseFile" name="image">
                                            </button>
                                        </div>
                                    </div>


                                    


                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit">
                                                <span class="ti-check"></span>
                                                @if(isset($visitor))
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
                                <h3 class="mb-0">@lang('common.view')</h3>
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
                                        <td colspan="5">
                                            @if(session()->has('message-success-delete'))
                                                <div class="alert alert-success">
                                                    @lang('style.deleted_message')
                                                </div>
                                            @elseif(session()->has('message-danger-delete'))
                                                <div class="alert alert-danger">
                                                    @lang('style.error_message')
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>@lang('style.title')</th>
                                    <th>@lang('common.type')</th>
                                    <th>@lang('style.background_image')</th> 
                                    <th>@lang('common.status')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($background_settings as $background_setting)
                                    <tr>
                                        <td>{{$background_setting->title}}</td>
                                        <td><p class="primary-btn small tr-bg">{{$background_setting->type}}</p></td>
                                        <td>
                                            @if($background_setting->type == 'image')
                                            <img src="{{asset($background_setting->image)}}" width="200px" height="100px">
                                            @else
                                             <div style="width: 200px; height: 100px; background-color:{{$background_setting->color}} "></div>
                                            @endif
                                        </td> 
                                        <td>
                                            <div class="col-md-12">
                                            
                                            @if($background_setting->is_default==1) 
                                                <a  class="primary-btn small fix-gr-bg " href="{{route('administrator/background_setting-status',$background_setting->id)}}"> Activated </a> 
                                            @else
                                                <a  class="primary-btn small tr-bg" href="{{route('school-background_setting-status',$background_setting->id)}}"> Make Default</a> 

                                           

                                            @endif
                                        </div>
                                        </td>

                                        <td>
                                            @if($background_setting->id==1 || $background_setting->id==2)
                                            <p class="primary-btn small tr-bg">Not Allowed</p>
                                            @else

                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    @lang('common.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" data-toggle="modal"
                                                       data-target="#deletebackground_settingModal"
                                                       href="#">@lang('common.delete')</a>
                                                </div>
                                            </div>

                                            <div class="modal fade admin-query" id="deletebackground_settingModal">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('common.delete')</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>

                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">@lang('common.cancel')
                                                                </button>

                                                                <a href="{{route('school-background-setting-delete',$background_setting->id)}}"
                                                                   class="primary-btn fix-gr-bg">@lang('common.delete')</a>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
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
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')