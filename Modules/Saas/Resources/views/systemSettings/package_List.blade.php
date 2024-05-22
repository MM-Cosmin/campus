@extends('backEnd.master')
@section('title')
Institution List
@endsection
@section('mainContent')
    <style type="text/css">
        #selectStaffsDiv, .forStudentWrapper {
            display: none;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 10px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background: linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
        }

        input:focus + .slider {
            box-shadow: 0 0 1px linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .icongrediant {
            background: -webkit-linear-gradient(#7c33ff, #c438d9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
        }

        .switcher_btn form {
            display: flex;
            align-items: center;
            margin-left: 30px;
        }

        .switcher_btn label {
            margin-bottom: 0;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 59px;
            height: 25px;
            margin-left: 10px;
        }

    </style>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>Institution List</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">Institution List</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">@if(isset($sectionId))
                                        @lang('common.edit')
                                    @else
                                        @lang('common.add')
                                    @endif
                                    @lang('common.class')
                                </h3>
                            </div>
                            @if(isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subscription/package-update', 'method' => 'POST']) }}
                                <input type="hidden" name="id" value="{{isset($editData)? $editData->id: ''}}">
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subscription/package-store', 'method' => 'POST']) }}
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


                                        </div>
                                    </div>
                                    <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input class="primary_input_field{{ $errors->has('package_name') ? ' is-invalid' : '' }}"
                                                       type="text" name="package_name" autocomplete="off"
                                                       value="{{isset($editData)? $editData->package_name: ''}}">
                                                <label>@lang('comoon.package_name') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('package_name'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('package_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input oninput="numberCheckWithDot(this)" class="primary_input_field{{ $errors->has('monthly_price') ? ' is-invalid' : '' }}"
                                                       type="text" step="any" name="monthly_price" autocomplete="off"
                                                       value="{{isset($editData)? $editData->monthly_price: ''}}">
                                                <label>@lang('saas::saas.monthly_price') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('monthly_price'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('monthly_price') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input oninput="numberCheckWithDot(this)" class="primary_input_field{{ $errors->has('quarterly_price') ? ' is-invalid' : '' }}"
                                                       type="text" step="any" name="quarterly_price"
                                                       autocomplete="off"
                                                       value="{{isset($editData)? $editData->quarterly_price: ''}}">
                                                <label>@lang('saas::saas.quarterly_price') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('quarterly_price'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('quarterly_price') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input oninput="numberCheckWithDot(this)" class="primary_input_field{{ $errors->has('yearly_price') ? ' is-invalid' : '' }}"
                                                       type="text" step="any" name="yearly_price" autocomplete="off"
                                                       value="{{isset($editData)? $editData->yearly_price: ''}}">
                                                <label>@lang('saas::saas.yearly_price') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('yearly_price'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('yearly_price') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input oninput="numberCheckWithDot(this)" class="primary_input_field{{ $errors->has('lifetime_price') ? ' is-invalid' : '' }}"
                                                       type="text" step="any" name="lifetime_price" autocomplete="off"
                                                       value="{{isset($editData)? $editData->lifetime_price: ''}}">
                                                <label>@lang('saas::saas.lifetime_price') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('lifetime_price'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('lifetime_price') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <textarea name="feature" id="" cols="30" rows="5"
                                                          class="primary_input_field{{ $errors->has('feature') ? ' is-invalid' : '' }}">{{isset($editData)? $editData->feature:''}}</textarea>

                                                <label>@lang('saas::saas.feature') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('feature'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('feature') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    @if(isset($editData))
                                        <div class="row mt-40">
                                            <div class="col-lg-12">
                                                <div class="primary_input">

                                                    <select class="primary_select form-control{{ $errors->has('active_status') ? ' is-invalid' : '' }}"
                                                            id="" name="active_status">
                                                        <option data-display="@lang('common.status') *"
                                                                value="">@lang('common.status') *
                                                        </option>
                                                        <option value="1" {{isset($editData)? $editData->active_status==1? 'selected':'':'' }}>
                                                            Active
                                                        </option>
                                                        <option value="2" {{isset($editData)? $editData->active_status==0? 'selected':'':'' }}>
                                                            Inactive
                                                        </option>
                                                    </select>
                                                    @if ($errors->has('active_status'))
                                                        <span class="invalid-feedback invalid-select" role="alert">
                                                                <strong>{{ $errors->first('active_status') }}</strong>
                                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit">
                                                <span class="ti-check"></span>
                                                @if(isset($section))
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
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    Institution List
                                </h3>
                            </div>

                            <div class="white-box">
                                <div class="row">
                                    <div class="col-lg-12">

                                        <table id="table_id" class="display school-table" cellspacing="0" width="100%"
                                               style="box-shadow: none">

                                            <thead>

                                            <tr>
                                                <th>@lang('common.sl')</th>
                                                <th>@lang('common.package_name')</th>
                                                <th>@lang('saas::saas.monthly_price') </th>
                                                <th>@lang('saas::saas.quarterly_price') </th>
                                                <th>@lang('saas::saas.yearly_price') </th>
                                                <th>@lang('saas::saas.lifetime_price') </th>
                                                <th>@lang('saas::saas.feature')</th>
                                                <th>@lang('common.status')</th>
                                                <th class="text-right">@lang('common.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $count =1; @endphp
                                            @foreach($data as $row)
                                                <tr>
                                                    <td>{{$count++}}</td>
                                                    <td>{{$row->package_name}}</td>
                                                    <td> {{number_format((float)$row->monthly_price, 2, '.', '')}} </td>
                                                    <td> {{number_format((float)$row->quarterly_price, 2, '.', '')}} </td>
                                                    <td> {{number_format((float)$row->yearly_price, 2, '.', '')}} </td>
                                                    <td> {{number_format((float)$row->lifetime_price, 2, '.', '')}} </td>
                                                    <td> {!! $row->feature !!}  </td>
                                                    <td>
                                                        @if($row->active_status==1)
                                                            <button class="primary-btn small fix-gr-bg">Active</button>
                                                        @else
                                                            <button class="primary-btn small bg-danger text-white border-0">
                                                                Inactive
                                                            </button>
                                                        @endif
                                                    </td>

                                                    <td valign="top">

                                                        <div class="dropdown">
                                                            <button type="button" class="btn dropdown-toggle"
                                                                    data-toggle="dropdown">
                                                                @lang('common.select')
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item"
                                                                   href="{{route('subscription/package-edit', [$row->id])}}">@lang('common.edit')</a>

                                                                <a class="dropdown-item" data-toggle="modal"
                                                                   data-target="#deleteClassModal{{$row->id}}"
                                                                   href="{{route('subscription/package-delete', [$row->id])}}">@lang('common.delete')</a>
                                                            </div>
                                                        </div>

                                                        <div class="modal fade admin-query"
                                                             id="deleteClassModal{{$row->id}}">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">@lang('common.delete_package')</h4>
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal">&times;
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="text-center">
                                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                        </div>

                                                                        <div class="mt-40 d-flex justify-content-between">
                                                                            <button type="button"
                                                                                    class="primary-btn tr-bg"
                                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                                            <a href="{{route('subscription/package-delete', [$row->id])}}"
                                                                               class="text-light">
                                                                                <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
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
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')