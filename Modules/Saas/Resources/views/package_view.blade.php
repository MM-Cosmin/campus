@extends('backEnd.master')
@section('title')
@lang('saas::saas.package_details')
@endsection
@push('css')
<style>
    .single-meta .name {
    flex: 0 0 100%;
    max-width: calc(100% / 12 * 2);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.single-meta .value {
    flex: 0 0 100%;
    max-width: calc(100% / 12 * 10);
    text-align: left;
    padding-left: 12px;
}
</style>
@endpush
@section('mainContent')
@php  $setting = App\SmGeneralSettings::where('school_id', Auth::user()->school_id)->first(); if(!empty(@$setting->currency_symbol)){ @$currency = @$setting->currency_symbol; }else{ @$currency = '$'; } @endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saas::saas.package') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('saas::saas.subscription')</a>
                <a href="{{ route('subscription/packages') }}">@lang('saas::saas.package')</a>
                <a href="#">@lang('common.view')</a>
            </div>
        </div>
    </div>
    
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($package))         
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('saas::saas.package_details')</h3>
                </div>
            </div>
            <div class="col-lg-4 text-right col-md-6">
                <a href="{{route('subscription/package-edit', [$package->id])}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.edit')
                </a>
            </div>
        </div>
            
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="student-meta-box">
                    <div class="white-box radius-t-y-0 student-details">
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('common.name') <span>:</span>
                                </div>
                                <div class="value">
                                    {{@$package->name}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('common.duration') <span>:</span>
                                </div>
                                <div class="value">
                                    {{@$package->duration_days}}
                                    
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('saas::saas.trial_duration')<span>:</span>
                                </div>
                                <div class="value">
                                    {{@$package->trial_days}}
                                    </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('saas::saas.student_quantity')<span>:</span>
                                </div>
                                <div class="value">
                                    {{@$package->student_quantity}}
                                    </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('saas::saas.staff_quantity')<span>:</span>
                                </div>
                                <div class="value">
                                    {{@$package->staff_quantity}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('saas::saas.modules')<span>:</span>
                                </div>
                                <div class="value">
                                    {{collect($package->modules)->implode(', ')}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('saas::saas.menus')<span>:</span>
                                </div>
                                <div class="value">
                                   
                                    {{collect($package->menus)->map(function ($v) use($permissions){

                                        return __(gv($permissions['menus'], $v));
                                    })->implode(', ')}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('saas::saas.features')<span>:</span>
                                </div>
                                <div class="value">
                                    @php $i = 1; @endphp
                                        @foreach($package->packageFeatures as $packageFeature)

                                            {{$i++.'. '.@$packageFeature->feature}}<br>
                                        @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('common.status')<span>:</span>
                                </div>
                                <div class="value">
                                    {{@$package->active_status == 1? 'active':'inactive'}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('saas::saas.price') ({{@generalSetting()->currency_symbol}}):
                                </div>
                                <div class="value">
                                    {{number_format(@$package->price, 2)}}
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

