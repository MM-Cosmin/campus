@extends('backEnd.master')
@section('title')
@lang('system_settings.general_settings')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.general_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.general_settings')</a>
            </div>
        </div>
    </div>
</section>
<section class="student-details">
    <div class="container-fluid p-0">
        @include('backEnd.partials.alertMessage')
        <div class="row">
            <div class="col-lg-3 col-md-6">
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('system_settings.change_logo')</h3>
                        </div>

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-logo', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

                        <div class="white-box">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="text-center">
                            @if(isset($editData->logo) && !empty($editData->logo))                            
                                <img class="img-fluid Img-100" src="{{$editData->logo}}" alt="" >
                            @else
                                <img class="img-fluid Img-100" src="{{asset('/public/uploads/settings/logo.png')}}" alt="">
                            @endif
                            </div>

                            <div class="mt-40">
                                <div class="text-center">
                                    <label class="primary-btn small fix-gr-bg" for="upload_logo">@lang('system_settings.upload')</label>
                                    <input type="file" class="d-none form-control" name="main_school_logo" id="upload_logo">
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg  "    >
                                    <span class="ti-check"></span>
                                    @lang('system_settings.change_logo')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>


                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="main-title">

                            <h3 class="mb-30">@lang('system_settings.change_fav') </h3>
                        </div>

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-logo', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

                        <div class="white-box">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="text-center">
                            @if(isset($editData->favicon) && !empty($editData->favicon))                            
                                <img class="img-fluid Img-50" src="{{$editData->favicon}}" alt="" >
                            @else
                                <img class="img-fluid" src="{{asset('public/uploads/settings/favicon.png')}}" alt="">
                            @endif
                            </div>

                            <div class="mt-40">
                                <div class="text-center">
                                    <label class="primary-btn small fix-gr-bg" for="upload_favicon">@lang('system_settings.upload')</label>
                                    <input type="file" class="d-none form-control" name="main_school_favicon" id="upload_favicon">
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg  ">
                                    <span class="ti-check"></span>
                                    @lang('system_settings.change_fav') 
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

                
            </div>

            <div class="col-lg-9">
                <div class="row xm_3">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('system_settings.general_settings_view')</h3>
                        </div>
                    </div>
                    @if (!empty($editData))
                         <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                        <a href="{{route('update-school-settings')}}" class="primary-btn small fix-gr-bg"> <span class="ti-pencil-alt"></span> @lang('common.edit')
                        </a>
                    </div>
                    @else
                         <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                        <a href="{{route('administrator/add-general-settings')}}" class="primary-btn small fix-gr-bg"> <span class="ti-pencil-alt"></span> @lang('common.add')
                        </a>
                    </div>
                    @endif
                   
                </div>

               <div class="row">
                    <div class="col-lg-12">
                        <div class="white-box">
                            <div class="student-meta-box">
                                
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('common.school_name')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$editData->school_name}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.site_title')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$editData->site_title}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('common.address')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$editData->address}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('common.phone_no')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$editData->phone}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('common.email_address')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$editData->email}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.school_code')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$editData->school_code}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('common.academic_year')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @php
                                                    $academic_year=App\SmAcademicYear::where('id',$editData->session_id)->first();
                                                @endphp
                                                @if(isset($editData))
                                                    {{$editData->session_id != ""? @$academic_year->year:""}}

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.language')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">

                                                @if(isset($editData))

                                                {{$editData->languages != ""? $editData->languages->language_name:""}}

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.date_format')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">

                                                @if(isset($editData)) 
                                                {{$editData->dateFormats != ""? $editData->dateFormats->normal_view:""}}

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.time_zone')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->timeZone->time_zone}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.currency')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$editData->currency}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.currency_symbol')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$editData->currency_symbol}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.promossion_without_exam')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                    @if (@$editData->promotionSetting != "" && @$editData->promotionSetting == 1)
                                                        Enable
                                                    @else
                                                        Disable
                                                    @endif
                                                {{-- {{@$editData->promotionSetting != ""? @$editData->promotionSetting == 1:""}} --}}
                                                {{-- {{$editData->promotionSetting}} --}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="single-meta d-none">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('system_settings.copyright_text') 
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {!! $editData->copyright_text !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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
