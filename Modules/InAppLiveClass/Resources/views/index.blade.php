@extends('backend.master')
@section('mainContent')
    {{generateBreadcrumb()}}

    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('inappliveclass.setting') }}" method="POST">
                        @csrf
                        <div class="white-box">
                            <div class="col-md-12 ">
                                <div class="row mb-30">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-lg-6 mb-30">
                                                <div class="input-effect">
                                                    <input class="primary-input form-control "
                                                           type="text" name="agora_app_id"
                                                           autocomplete="off"
                                                           value="{{Settings('agora_app_id')}}">
                                                    <label>{{__('setting.App ID')}}
                                                        <span></span> </label>
                                                    <span class="focus-border"></span>
                                                    <span
                                                        class="text-danger">{{$errors->first('agora_app_id')}}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-30">
                                                <div class="input-effect">
                                                    <input class="primary-input form-control "
                                                           type="text" name="agora_app_certificate"
                                                           autocomplete="off"
                                                           value="{{Settings('agora_app_certificate')}}">
                                                    <label>{{__('setting.App Certificate')}}
                                                        <span></span> </label>
                                                    <span class="focus-border"></span>
                                                    <span
                                                        class="text-danger">{{$errors->first('agora_app_certificate')}}</span>
                                                </div>
                                            </div>


                                            <div class="col-lg-12">
                                                <code><a target="_blank" title="Google map api key"
                                                         href="https://console.agora.io/">{{__('setting.Click Here to Get APP ID & Certificate')}}</a></code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="primary-btn fix-gr-bg">
                                            <i class="ti-check"></i>
                                            {{__('common.Update')}}
                                        </button>
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
