@extends('backEnd.master')
@section('title')
    @lang('saas::saas.custom_domain_settings')
@endsection
@section('mainContent')

    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('saas::saas.custom_domain_settings')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('saas::saas.custom_domain_settings')</a>

                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">

                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'administrator/custom-domain-settings', 'method' => 'POST']) }}

                    <div class="white-box">
                        <div class="row p-0">
                            <div class="col-lg-12">
                                <h3 class="text-center">@lang('saas::saas.custom_domain_settings')</h3>
                                <hr>


                                <div class="row mb-40 mt-40">

                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-5 d-flex">
                                                <p class="text-uppercase fw-500 mb-10">@lang('saas::saas.allow_custom_domain') </p>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="radio-btn-flex ml-20">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="">
                                                                <input type="radio" name="allow_custom_domain"
                                                                    id="relationFather" value="true"
                                                                    class="common-radio relationButton"
                                                                    {{ config('app.allow_custom_domain') ? 'checked' : '' }}>
                                                                <label for="relationFather">@lang('common.enable')</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="">
                                                                <input type="radio" name="allow_custom_domain"
                                                                    id="relationMother" value="false"
                                                                    class="common-radio relationButton"
                                                                    {{ !config('app.allow_custom_domain') ? 'checked' : '' }}>
                                                                <label for="relationMother">@lang('common.disable')</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('allow_custom_domain'))
                                            <div class="col-12">
                                                <span class="text-danger text-left" role="alert">
                                                    
                                                    {{ $errors->first('allow_custom_domain') }}
                                                
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    

                                </div>
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" id="_submit_btn_admission">
                                                <span class="ti-check"></span>
                                                @lang('common.save')
                                            </button>
                                        </div>
                                    </div>


                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
