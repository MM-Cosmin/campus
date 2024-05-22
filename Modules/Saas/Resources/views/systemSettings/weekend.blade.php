
@extends('backEnd.master')
@section('title')
@lang('common.weekend')
@endsection
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.weekend')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('common.weekend')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($editData))
                                    @lang('common.edit_holiday')
                                @else
                                    @lang('common.add_holiday')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('weekend-update',$editData->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'weekendForm']) }}
                                    <input type="hidden" name="id" value="{{$editData->id}}">
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    @if(session()->has('message-success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message-success') }}
                                    </div>
                                    @elseif(session()->has('message-danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message-danger') }}
                                    </div>
                                    @endif
                                    <div class="col-lg-12 mb-20 mt-10 {{!isset($editData)? 'disabledbutton':''}}">
                                        <div class="primary_input">
                                            <input class="primary_input_field{{ $errors->has('holiday_title') ? ' is-invalid' : '' }}"
                                            type="text" name="name" autocomplete="off" value="{{isset($editData)? $editData->name : '' }}" readonly="true">
                                            <label>@lang('common.title') <span>*</span> </label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>


                                <div class="row mt-25 {{!isset($editData)? 'disabledbutton':''}}">
                                    <div class="col-lg-12"> 
                                        <div class="primary_input">
                                            <input type="checkbox" id="weekend" class="common-checkbox" name="weekend" value="" {{isset($editData)?($editData->is_weekend == 1? 'checked':''):''}}>
                                            <label for="weekend">@lang('common.weekend')</label>
                                        </div>
                                    </div>
                                </div>


                                
                                <div class="row mt-40 {{!isset($editData)? 'disabledbutton':''}}">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>

                                            @if(isset($editData))
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
                    <div class="main-title mt_4">
                        <h3 class="mb-30">@lang('common.day_list')</h3>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <table id="" class="display school-table school-table-style" cellspacing="0" width="100%">

                        <thead>
                            @if(session()->has('message-success-delete') != "" ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="5">
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
                            <th>@lang('common.name')</th>
                            <th>@lang('common.weekend')</th>
                            <th>@lang('common.action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($weekends as $weekend)
                        <tr>
                            <td>{{$weekend->name}}</td>
                            <td>
                                @if($weekend->is_weekend == 1)
                                <button class="primary-btn small fix-gr-bg">
                                    yes
                                </button>
                                @else
                                    {{'No'}}
                                @endif


                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                        @lang('common.select')
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">

                                        <a class="dropdown-item" href="{{route('weekend-edit',$weekend->id)}}">@lang('common.edit')</a>

                                    </div>
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
@endsection
