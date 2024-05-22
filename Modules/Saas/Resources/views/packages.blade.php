@extends('backEnd.master')
@section('title')
@lang('saas::saas.subscription_package')
@endsection
@section('mainContent')
@php  $setting = App\SmGeneralSettings::where('school_id', Auth::user()->school_id)->first(); if(!empty(@$setting->currency_symbol)){ @$currency = @$setting->currency_symbol; }else{ @$currency = '$'; } @endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saas::saas.package') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('saas::saas.subscription')</a>
                <a href="#">@lang('saas::saas.package')</a>
            </div>
        </div>
    </div>
    
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($package))
         
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('subscription/packages')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($package))
                                    @lang('common.edit')
                                @else
                                    @lang('common.add')
                                @endif
                                @lang('saas::saas.package')
                            </h3>
                        </div>
                        @if(isset($package))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subscription/package-update', 'method' => 'POST']) }}
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
                                        <div class="primary_input">
                                            <label>@lang('common.name') <span>*</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" autocomplete="off" value="{{isset($package)? $package->name:old('name')}}">
                                            <input type="hidden" name="id" value="{{@$package->id}}">
                                         
                                           
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label>@lang('saas::saas.duration_days') <span>*</span></label>
                                            <input oninput="numberCheck(this)" class="primary_input_field form-control{{ @$errors->has('duration') ? ' is-invalid' : '' }}" type="number" name="duration" autocomplete="off" value="{{isset($package)? $package->duration_days:old('duration')}}">
                                            
                                           
                                            @if ($errors->has('duration'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('duration') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label>@lang('saas::saas.trial_days') <span></span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('trial_days') ? ' is-invalid' : '' }}" type="number" name="trial_days" autocomplete="off" value="{{isset($package)? $package->trial_days:old('trial_days')}}" step="any">
                                           
                                           
                                            @if ($errors->has('trial_days'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('trial_days') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label>@lang('saas::saas.student_quantity') <span>*</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('student_quantity') ? ' is-invalid' : '' }}" type="number" name="student_quantity" autocomplete="off" value="{{isset($package)? $package->student_quantity:old('student_quantity')}}">
                                           
                                           
                                            @if ($errors->has('student_quantity'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('student_quantity') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label>@lang('saas::saas.staff_quantity') <span>*</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('staff_quantity') ? ' is-invalid' : '' }}" type="number" name="staff_quantity" autocomplete="off" value="{{isset($package)? $package->staff_quantity:old('staff_quantity')}}">
                                           
                                           
                                            @if ($errors->has('staff_quantity'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('staff_quantity') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label>@lang('saas::saas.price') ({{@generalSetting()->currency_symbol}})<span>*</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('price') ? ' is-invalid' : '' }}" type="text" name="price" autocomplete="off" value="{{isset($package)? $package->price:old('price')}}" step="any">
                                           
                                           
                                            @if ($errors->has('price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-15">
                                    @foreach($permissions as $hook => $values)
                                        @if($value_count = count($values))
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <h3>{{ __('saas::saas.'.$hook.'_permission') }}</h3>
                                                    @php
                                                        $checked_all = isset($package) && $package->$hook && count($package->$hook) == $value_count;
                                                    @endphp
                                                        <div class="mt-2">
                                                            <input type="checkbox" id="select_all_{{$hook}}" value="{{$hook}}" class="common-checkbox relationButton select_all" {{ $checked_all ? 'checked' : '' }} >
                                                            <label for="select_all_{{$hook}}">@lang('common.select_all')</label>
                                                        </div>

                                                </div>
                                            </div>
                                        @endif
                                        @foreach($values as $key => $value)
                                    <div class="col-lg-12">
                                        <div class="">
                                            @php
                                                $checked = isset($package) && $package->$hook && in_array($key, $package->$hook);
                                            @endphp
                                            <input type="checkbox" name="{{ $hook }}[]" id="{{$hook}}_{{$key}}" value="{{$key}}" class="common-checkbox relationButton {{ $hook.'_checkbox' }}" {{ $checked ? 'checked' : '' }} >
                                            <label for="{{$hook}}_{{$key}}">@lang($value)</label>
                                        </div>
                                    </div>
                                        @endforeach
                                    @endforeach
                                </div>

                                <div class="row mt-15">
   
                                 <div class="col-lg-10">
                                    <div class="main-title">
                                        <h5>@lang('saas::saas.add_features') </h5>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <button type="button" class="primary-btn icon-only fix-gr-bg" onclick="addRowFeature();" id="addRowBtn">
                                    <span class="ti-plus pr-2"></span></button>
                                </div>
                            </div>
                            <div class="feature_section">
                                @if(isset($package))
                                @foreach($package->packageFeatures as $packageFeature)
                                <div class="row mt-15" id="single-feature">
                                    <div class="col-md-10">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control" type="text" name="feature[]" autocomplete="off" placeholder="Write feature" value="{{@$packageFeature->feature}}">
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="primary-btn icon-only fix-gr-bg" type="button" id="row_remove">
                                                 <span class="ti-trash"></span>
                                            </button>
                                    </div>
                                </div>

                                @endforeach
                                
                                @else
                                <div class="row mt-15" id="single-feature">
                                    <div class="col-md-10">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control" type="text" name="feature[]" autocomplete="off" placeholder="Write feature">
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="primary-btn icon-only fix-gr-bg" type="button" id="row_remove">
                                            <span class="ti-trash"></span>
                                        </button>
                                    </div>
                                </div>
                                @endif

                            </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <select class="primary_select form-control {{ $errors->has('status') ? ' is-invalid' : '' }}" id="active_status" name="status">
                                            <option data-display="@lang('common.select_status') *" value="">@lang('common.select_status') *</option>
                                           
                                                <option value="1" {{isset($package)? ($package->active_status == 1? 'selected':''):'selected'}}>@lang('saas::saas.active')</option>
                                                <option value="0" {{isset($package)? ($package->active_status == 0? 'selected':''):''}}>@lang('saas::saas.inactive')</option>

                                        </select>
                                        @if ($errors->has('status'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                

                                
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @if(isset($package))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
                                            @endif
                                            @lang('saas::saas.packages')
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
                            <h3 class="mb-0">@lang('saas::saas.package_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                            <thead>
                               @if(session()->has('message-success-delete') != "" ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="3">
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
                                    <th>@lang('common.duration')</th>
                                    <th>@lang('saas::saas.trial_duration')</th>
                                    <th>@lang('saas::saas.student_quantity')</th>
                                    <th>@lang('saas::saas.staff_quantity')</th>
                                    <th>@lang('common.status')</th>
                                    <th>@lang('saas::saas.price') ({{@generalSetting()->currency_symbol}})</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach(@$packages as $package)
                                <tr>
                                    <td>{{@$package->name}}</td>
                                    <td>{{@$package->duration_days}}</td>
                                    <td>{{@$package->trial_days}}</td>
                                    <td>{{@$package->student_quantity}}</td>
                                    <td>{{@$package->staff_quantity}}</td>
                                    <td>{{@$package->active_status == 1? 'active':'inactive'}}</td>
                                    <td>{{number_format(@$package->price, 2)}}</td>
                                    <td>
                                        <x-drop-down>
                                                <a class="dropdown-item" href="{{route('subscription.purchaseHistory', [@$package->id])}}">@lang('saas::saas.purchase_history')</a>
                                                <a class="dropdown-item" href="{{route('subscription/package-edit', [@$package->id])}}">@lang('common.edit')</a>
                                                <a class="dropdown-item" href="{{route('subscription/package-view', [@$package->id])}}">@lang('common.view')</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteSectionModal{{@$package->id}}"  href="#">@lang('common.delete')</a>


                                                <a class="dropdown-item" data-toggle="modal" data-target="#assignSectionModal{{@$package->id}}"  href="#">@lang('saas::saas.assign_package')</a>
                                        </x-drop-down>
                                    </td>
                                </tr>
                                  <div class="modal fade admin-query" id="deleteSectionModal{{@$package->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('common.delete_package')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                    <a href="{{route('subscription/package-delete', [@$package->id])}}" class="text-light">
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                     </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade admin-query" id="assignSectionModal{{@$package->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('saas::saas.assign_package')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('saas::saas.are_you_sure_to_assign_package_for_all_school')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                    <a href="{{route('subscription/assign-package', [@$package->id])}}" class="text-light">
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('saas::saas.yes')</button>
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
@section('script')
<script type="text/javascript">
     addRowFeature = () => {
       var newRow = "<div class='row mt-15' id='single-feature'>";
            newRow += "<div class='col-md-10'>";
                newRow +=     "<div class='primary_input'>";
                 newRow +=    "<input class='primary_input_field form-control' type='text' name='feature[]'autocomplete='off' placeholder='Write feature'>";
                   newRow +=  "<span class='focus-border'></span>";
                 newRow +=    "</div>";
                 newRow +=    "</div>";
                 newRow +=   " <div class='col-md-2'>";
                newRow +=     "<button class='primary-btn icon-only fix-gr-bg' type='button' id='row_remove'>";
                newRow +=     "<span class='ti-trash'></span>";
                newRow +=     "</button>";
                newRow +=     "</div>";
                newRow +=     "</div>";


            $(".feature_section").append(newRow);


    }


    $(document).on("click", "#row_remove", function() {
        $(this).closest(($('#single-feature').remove()));
    });

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
@endsection
