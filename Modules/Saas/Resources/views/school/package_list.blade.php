@extends('backEnd.master')
@section('title')
@lang('saas::saas.subscription_package')
@endsection
@section('mainContent')
<style type="text/css">
    .button-inherit{
        width: inherit;
    }
    .width-full{
        width: 100%;
    }
</style>
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

<section class="mb-40">
    <div class="container-fluid">
        <div class="row justify-content-between">
        <div class="col-12 p-0">
        <table id="table_id_table" class="display school-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>@lang('saas::saas.package_name') </th>
                    <th>@lang('saas::saas.remain_days') </th>
                    <th>@lang('common.status') </th>
                    <th>@lang('saas::saas.started_date') </th>
                    <th>@lang('saas::saas.renewal_date') </th>
                </tr>
            </thead>
            
                @foreach($purchase_packages as $purchase_package)
                @php 
                   if($last_record != "" && $last_record->buy_type == 'instantly' && $last_record->approve_status == 'approved'){

                        if($purchase_package->payment_type == 'trial'){
                            $total_days  = $purchase_package->package->trial_days;
                        }else{
                            $total_days  = @$purchase_package->package->duration_days;
                        }

                        $now_time = date('Y-m-d');
                        $now_time =  date('Y-m-d', strtotime($now_time. ' + 1 days'));

                        if(date('Y-m-d') >= $purchase_package->start_date){
                            $datediff    = strtotime($now_time) - strtotime($purchase_package->start_date);
                            $used_days   = round($datediff / (60 * 60 * 24));
                            $remain_days = $total_days - $used_days;

                            if($remain_days < 0){
                                $remain_days = 0;
                            }

                        }else{
                            $remain_days = $total_days;
                        }

                        if($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $purchase_package->id == $last_record->id && $purchase_package->approve_status == 'approved'){
                            $status = 'Active';
                            $color = 'text-success';
                            $active_package = $purchase_package->package_id;
                        }else{
                            $status = 'Inactive';
                            $color = 'text-danger';
                        }


                    }elseif($last_record != "" && $last_record->buy_type == 'buy_now' && $last_record->approve_status == 'approved'){


                        if($purchase_package->payment_type == 'trial'){
                            $total_days  = $purchase_package->package->trial_days;
                        }else{
                            $total_days  = $purchase_package->package->duration_days;
                        }
                        $now_time = date('Y-m-d');
                        $now_time =  date('Y-m-d', strtotime($now_time. ' + 1 days'));

                        if(date('Y-m-d') >= $purchase_package->start_date){
                            $datediff    = strtotime($now_time) - strtotime($purchase_package->start_date);
                            $used_days   = round($datediff / (60 * 60 * 24));
                            $remain_days = $total_days - $used_days;

                            if($remain_days < 0){
                                $remain_days = 0;
                            }

                        }else{
                            $remain_days = $total_days;
                        }



                        if($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $purchase_package->approve_status == 'approved' && $last_Active->id == $purchase_package->id){

                            $status = 'Active';
                            $color = 'text-success';
                            $active_package = $purchase_package->package_id;
                        }else{
                            $status = 'Inactive';
                            $color = 'text-danger';
                        }



                    }elseif($last_record != "" && $last_record->payment_type == 'trial'){

                        $total_days  = $purchase_package->package->trial_days;

                        $now_time = date('Y-m-d');
                        $now_time =  date('Y-m-d', strtotime($now_time. ' + 1 days'));
                        $datediff    = strtotime($now_time) - strtotime($purchase_package->start_date);
                        $used_days   = round($datediff / (60 * 60 * 24));
                        $remain_days = $total_days - $used_days;

                        if($remain_days < 0){
                            $remain_days = 0;
                        }

                        
                        if($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $purchase_package->payment_type == 'trial'){

                            $status = 'Trial';
                            $color = 'text-success';
                            
                        }else{
                            $remain_days  = $purchase_package->package->duration_days;
                            $status = 'Inactive';
                            $color = 'text-danger';
                        }
                        

                    }else{

                        $remain_days = $purchase_package->package->duration_days;
                        if($remain_days < 0){
                            $remain_days = 0;
                        }   

                        $status = 'Inactive';
                        $color = 'text-danger';

                    }


                @endphp
                <tbody>
                <tr>
                    <td>{{@$purchase_package->package->name}} </td>
                    <td>{{@$remain_days}} @lang('saas::saas.days')</td>
                    <td><span class="{{@$color}}"><strong>{{@$status}}</strong></span> </td>
                    <td>{{ !empty($purchase_package->start_date)? dateConvert($purchase_package->start_date):''}} </td>
                    <td>{{ !empty($purchase_package->start_date)? dateConvert($purchase_package->end_date):''}} </td>
                </tr>
                </tbody>
                @endforeach
            </table>
        </div>
        </div>

    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">


        <div class="row">
            @php($permissions = planPermissions())
            @foreach($packages as $package)
            <div class="col-md-4 col-sm-6">
                <div class="white-box">
                        <div class="single_feature">
                            <div class="single_feature_part">
                                <div class="text-center fixed_height">
                                    <span class="single_feature_icon"><h2>{{@$package->name}}</h2></span>
                                    <hr>
                                    <ul class="list-unstyled components mt-30">
                                        @foreach($package->packageFeatures as $packageFeature)
                                        <li>{{@$packageFeature->feature}}.</li>
                                        @endforeach
                                    </ul>
                                    @if($package->menus && count($package->menus))
                                    <span class="single_feature_icon"><h4>{{__('saas::saas.get_access_to_menu')}}</h4></span>
                                    <ul class="list-unstyled components mt-2">
                                        @foreach($package->menus as $menu)
                                            <li class="border-bottom">{{ __(gv($permissions['menus'], $menu)) }}</li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    @if($package->modules && count($package->modules))
                                    <span class="single_feature_icon"><h4>{{__('saas::saas.get_access_to_modules')}}</h4></span>
                                    <ul class="list-unstyled components mt-2">
                                        @foreach($package->modules as $module )
                                            <li class="border-bottom">{{ $module }}</li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    <p class="primary-btn custome-button fix-gr-bg btn_1 mt-50"> ${{number_format(@$package->price, 2)}} for {{@$package->duration_days}} days </p>

                                </div>
                                
                                @if(@$active_package != $package->id)
                                <div class="row mt-40">
                                    <a class="width-full" href="{{route('subscription/buy-now', [$package->id, 'instantly'])}}">
                                            <div class="col-lg-12 text-center">
                                                <button class="button-inherit primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                        @lang('saas::saas.instant_upgrade')
                                                </button>
                                        </div>
                                    </a>
                                </div>
                                @endif
                           
                            <div class="row mt-40">
                                <a class="width-full" href="{{route('subscription/buy-now', [$package->id, 'buy_now'])}}">
                                    <div class="col-lg-12 text-center">
                                        
                                            <button class="button-inherit primary-btn fix-gr-bg">
                                                <span class="ti-check"></span>
                                                
                                                    @lang('saas::saas.buy_now')

                                            </button>
                                        
                                    </div>
                                    
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>
                </div>
                @endforeach

            
        </div>
    </div>
</section>
@endsection


