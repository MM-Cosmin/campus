@extends('backEnd.master')
@section('mainContent')
@php
    $modules = [];
    $module_links = [];
    $permissions = App\SmRolePermission::where('role_id', Auth::user()->role_id)->get(); 
    foreach($permissions as $permission){ $module_links[] = $permission->module_link_id; $modules[] = $permission->moduleLink->module_id;}
    $modules = array_unique($modules);


    $generalSetting=App\SmGeneralSettings::where('id',1)->first();
    $currency_symbol = $generalSetting->currency_symbol;

    if(isset($generalSetting->logo)){  $logo = $generalSetting->logo;  }
    else{ $logo = 'public/uploads/settings/logo.png'; } 
@endphp
<section class="mb-40 up_dashboard">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h3 class="mb-30">
                            @lang('common.welcome') {{Auth::user()->full_name}}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @if(in_array(225, $module_links) ||  Auth::user()->role_id == 7)
                <div class="col-lg-3 col-md-6">
                    <a class="d-block" href="{{url('suppliers')}}">
                        <div class="white-box single-summery">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>
                                        @lang('saas::saas.ticket_system')
                                    </h3>
                                    <p class="mb-0">
                                        @lang('exam.result') {{ App\Ticket::where('user_id',Auth::user()->id)->count() }} @lang('saas::saas.ticket_system')
                                    </p>
                                </div>
                                <h1 class="gradient-color2">
                                        {{ App\Ticket::where('user_id',Auth::user()->id)->count() }}
                                </h1>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
    
    
                @if(in_array(226, $module_links) ||  Auth::user()->role_id == 7)
                <div class="col-lg-3 col-md-6">
                    <a class="d-block" href="{{url('customers')}}">
                        <div class="white-box single-summery">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>
                                            @lang('saas::saas.active_ticket_system')
                                    </h3>
                                    <p class="mb-0">
                                        @lang('exam.result') {{ App\Ticket::where('user_id',Auth::user()->id)->where('active_status',0)->count() }} @lang('saas::saas.active_ticket_system')
                                    </p>
                                </div>
                                <h1 class="gradient-color2">
                                        {{ App\Ticket::where('user_id',Auth::user()->id)->where('active_status',0)->count() }}
                                </h1>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                    @if(in_array(2, $module_links) ||  Auth::user()->role_id == 7)
                <div class="col-lg-3 col-md-6">
                    <a class="d-block" href="{{route('staff_directory')}}">
                        <div class="white-box single-summery">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>
                                            @lang('saas::saas.completed_ticket_system')
                                    </h3>
                                    <p class="mb-0">
                                        @lang('exam.result') {{ App\Ticket::where('user_id',Auth::user()->id)->where('active_status',1)->count() }} @lang('saas::saas.completed_ticket_system')
                                    </p>
                                </div>
                                <h1 class="gradient-color2">
                                        {{ App\Ticket::where('user_id',Auth::user()->id)->where('active_status',1)->count() }}
                                </h1>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection
