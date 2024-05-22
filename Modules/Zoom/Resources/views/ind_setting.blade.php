@extends('backEnd.master')
@section('title') 
    @lang('zoom::zoom.manage_zoom_settings')
@endsection
@section('mainContent')
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
            left: 3px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background: var(--primary-color);
        }

        input:focus + .slider {
            box-shadow: 0 0 1px linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
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
        .buttons_div_one{
        /* border: 4px solid #FFFFFF; */
        border-radius:12px;

        padding-top: 0px;
        padding-right: 5px;
        padding-bottom: 0px;
        margin-bottom: 4px;
        padding-left: 0px;
         }
        .buttons_div{
        border: 4px solid #19A0FB;
        border-radius:12px
        }
        .slider_zoom{
         margin-top: -8%;
         margin-bottom: 0;
         margin-left: 6%;
        }
    </style>
<section class="sms-breadcrumb mb-20 up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('zoom::zoom.manage_zoom_setting')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('common.virtual_class')</a>
                <a href="#">@lang('zoom::zoom.settings')</a>
            </div>
        </div>
    </div>
</section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('zoom.ind.settings.update') }}" method="POST">
                        @csrf
                        <div class="white-box">
                                <div class="row p-0">
                                    <div class="col-lg-12">
                                        <h3 class="text-center">@lang('zoom::zoom.zoom_setting')</h3>
                                        <hr>



                                        <div class="row mb-40 mt-40">
                                            <div class="col-lg-6">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field form-control{{ $errors->has('api_key') ? ' is-invalid' : '' }}" type="text" name="api_key" value="">
                                                    <label class="primary_input_label" for="">@lang('zoom::zoom.api_key')<span class="text-danger"> *</span> </label>
                                                    
                                                    @if ($errors->has('api_key'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('api_key') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field form-control{{ $errors->has('secret_key') ? ' is-invalid' : '' }}" type="text" name="secret_key" value="">
                                                    <label class="primary_input_label" for="">@lang('zoom::zoom.serect_key')<span class="text-danger"> *</span></label>
                                                    
                                                    @if ($errors->has('secret_key'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('secret_key') }}</span>
                                                    @endif
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row mb-40 mt-40">
                                            {{-- <div class="col-lg-6">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field form-control{{ $errors->has('secret_key') ? ' is-invalid' : '' }}" type="text" name="secret_key" value="">
                                                    <label class="primary_input_label" for="">@lang('zoom::zoom.serect_key')<span class="text-danger"> *</span></label>
                                                    
                                                    @if ($errors->has('secret_key'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('secret_key') }}</span>
                                                    @endif
                                                </div>
                                            </div> --}}
                                         
                                         </div>

                                     
                                            <div class="row mt-40">
                                                <div class="col-lg-12 text-center">
                                                <button class="primary-btn fix-gr-bg" id="_submit_btn_admission">
                                                        <span class="ti-check"></span>
                                                        @lang('common.update')
                                                    </button>
                                                </div>
                                            </div>
                                      

                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
