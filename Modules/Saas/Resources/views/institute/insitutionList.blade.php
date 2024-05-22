@extends('backEnd.master')
@section('title') 
    @lang('saas::saas.institution_list')
@endsection

@section('mainContent')
@push('css')
    <style type="text/css">
        #selectStaffsDiv, .forStudentWrapper {
            display: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 26px;
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
            height: 24px;
            width: 24px;
            left: 4px;
            bottom: 1px;
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

        tr {
            border-bottom: 1px solid #ddd;
        }

        td {
            padding: 10px 10px 10px 30px !important;
        }
        table.dataTable thead .sorting_desc::after {
            top: 15px !important;
            left: 28px !important;
        }
    </style>
@endpush
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('saas::saas.institution_list')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('saas::saas.institution_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class='row'>
                        <div class="offset-lg-9 col-lg-3 text-right mb-20">
                            <a href="{{route('administrator/institution-create')}}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('common.add_new')
                            </a>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="main-title">
                                <h3 class="mb-0">
                                    @lang('saas::saas.institution_list')
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('saas::saas.domain')</th>
                                            <th>@lang('saas::saas.custom_domain')</th>
                                            @if(isSubscriptionEnabled())
                                                <th>@lang('saas::saas.package_name')</th>
                                            @endif
                                            <th>@lang('common.email')</th>
                                            <th>@lang('saas::saas.start_date')</th>
                                            <th>@lang('common.details')</th>
                                            <th>@lang('saas::saas.is_approved')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $count=1; @endphp
                                    @foreach($data as $row)
                                        <tr id='{{ $row->id }}'>
                                            <td>{{@$count++}}</td>
                                            <td>{{@$row->school_name}}</td>
                                            <td>{{@$row->domain}}</td>
                                            <td>{{@$row->custom_domain}}</td>
                                            @if(isSubscriptionEnabled())
                                                <td>
                                                    @php
                                                        $package = activePackage($row);
                                                    @endphp
                                                    <button class="primary-btn small fix-gr-bg">{{$package? $package->name : __('saas::saas.not_assigned')}}</button>
                                                </td>
                                            @endif
                                            <td> {{@$row->email}} </td>
                                            <td data-sort="{{ $row->starting_date }}">
                                                {{  @$row->starting_date != ""? dateConvert($row->starting_date):''}}</td>
                                            <td>
                                                <a href="{{route('administrator/institution-details', @$row->id)}}">
                                                    <span class="ti-view-grid icongrediant"></span>
                                                </a>
                                            </td>
                                            <td>
                                                  <label class="switch_toggle">
                                                    <input type="checkbox" class="switch-input-institution-approve" {{@$row->active_status == 1? 'checked':''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                           
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                        @lang('common.select')
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if( (moduleStatusCheck('SaasRolePermission') == True))
                                                            <a href="{{route('saasSchoolSwitch', $row->id)}}" class="dropdown-item">
                                                                @lang('common.dashboard')
                                                            </a>
                                                        @endif
    
                                                        @if(auth()->id() == 1)
                                                                <a href="{{route('secret-login', $row->id)}}" class="dropdown-item" target="_blank">
                                                                    @lang('saas::saas.login_to_dashboard')
                                                                </a>
                                                            @endif
                                                        <a class="dropdown-item" href="{{route('administrator/institution-edit', [$row->id])}}">@lang('common.edit')</a>
                                                        @if($row->id != 1)
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#deleteClassModal{{@$row->id}}" href="">@lang('common.delete')</a>
                                                        @endif
    
                                                    <!-- Start if subscription module enable -->
                                                        @if(isSubscriptionEnabled())
                                                            <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="@lang('saas::saas.assign_package_for') {{@$row->school_name}}" href="{{route('subscription/add-payment', [@$row->id])}}">
                                                                @lang('saas::saas.assign_package')
                                                            </a>
    
                                                            <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="@lang('saas::saas.assign_module_and_menu_for') {{@$row->school_name}}" href="{{route('subscription/add-module', [@$row->id])}}">
                                                                @lang('saas::saas.assign_module_and_menu')
                                                            </a>
                                                        @endif
                                                    <!-- End if subscription module enable -->
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
    
                                        <div class="modal fade admin-query" id="deleteClassModal{{@$row->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('saas::saas.delete_institute')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">
                                                                @lang('common.cancel')
                                                            </button>
                                                            <a href="{{route('administrator/institution-delete', [$row->id])}}" class="text-light">
                                                                <button class="primary-btn fix-gr-bg" type="submit">
                                                                    @lang('common.delete')
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                    @endforeach
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@push('script')
    <script src="{{url('/')}}/Modules\Saas\Resources\assets\js\saas.js"></script>



    <script>
        $(document).on('change', '.select_all', function(){
            let hook = $(this).val();
            $('.'+hook+'_checkbox').prop('checked', $(this).is(':checked'));
        });

        $(document).on('change', '.menus_checkbox', function(){
            let total = $('.menus_checkbox').length;
            let checked = $('.menus_checkbox:checked').length;

            console.log('changed');

            $("input[value='menus']").prop('checked', total === checked);
        });

        $(document).on('change', '.modules_checkbox', function(){
            let total = $('.modules_checkbox').length;
            let checked = $('.modules_checkbox:checked').length;

            console.log('changed');

            $("input[value='modules']").prop('checked', total === checked);
        });
    </script>
@endpush