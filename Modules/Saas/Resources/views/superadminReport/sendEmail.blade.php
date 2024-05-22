@extends('backEnd.master')
@section('title')
    @lang('communicate.send_mail')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('communicate.send_mail')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('communicate.communicate')</a>
                    <a href="#"> @lang('communicate.send_mail')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('communicate.send_mail') </h3>
                    </div>
                </div>

            </div>
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/send-email', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-lg-12">
                    @if (session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </div>
                    @elseif(session()->has('message-danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('message-danger') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-7">
                            <div class="white-box">
                                <div class="">
                                    <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-30">
                                                <label>@lang('common.subject') <span>*</span> </label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('email_title') ? ' is-invalid' : '' }}"
                                                    type="text" name="email_title" autocomplete="off"
                                                    value="{{ old('email_title') }}">
                                               
                                                <span class="focus-border"></span>
                                                @if ($errors->has('email_title'))
                                                    <span class="text-danger" role="alert">
                                                        {{ $errors->first('email_title') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="primary_input">
                                                <label>@lang('common.description') <span>*</span> </label>
                                                <textarea class="primary_input_field {{ $errors->has('description') ? ' is-invalid' : '' }}" cols="0"
                                                    rows="4" name="description" id="details">{{ old('description') }}</textarea>
                                              
                                                @if ($errors->has('description'))
                                                    <span class="text-danger" role="alert">
                                                        {{ $errors->first('description') }}
                                                    </span>
                                                @endif
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">

                            <div class="row student-details">

                                <!-- Start Sms Details -->
                                <div class="col-lg-12">


                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <input type="hidden" name="selectTab" id="selectTab">




                                        <!-- Start Class Section Tab -->
                                        <div>
                                            <div class="white-box">

                                                <div class="row mb-35">
                                                    @if (session()->has('error-message'))
                                                        <div class="alert alert-danger">
                                                            {{ session()->get('error-message') }}
                                                            <button type="button" class="close" data-dismiss="alert"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    @endif

                                                    <div class="col-lg-12">
                                                        <select
                                                            class="primary_select form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                                            name="school_id" id="class_id_email_sms">
                                                            <option data-display="@lang('common.select_one')" value="">
                                                                @lang('common.select_one')</option>

                                                            @foreach ($institutions as $value)
                                                                <option value="{{ $value->id }}">
                                                                    {{ $value->school_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('school_id'))
                                                            <span class="text-danger" role="alert">
                                                                {{ $errors->first('school_id') }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="col-lg-12 mt-30" id="selectSectionsDiv">
                                                        <label for="checkbox" class="mb-2">@lang('communicate.or')</label>

                                                        <div class="">
                                                            <input type="checkbox" id="select_all" class="common-checkbox"
                                                                name="select_all">
                                                            <label for="select_all"
                                                                class="mt-3">@lang('communicate.select_all')</label>

                                                        </div>
                                                        @if ($errors->has('select_all'))
                                                            <span class="text-danger" role="alert">
                                                                {{ $errors->first('select_all') }}
                                                            </span>
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
                    <div class="alert alert-warning mt-30">
                        @lang('communicate.For_Sending_Email')
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                    </div>

                    <div class="white-box mt-30">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span> @lang('communicate.send')
                                </button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        {{ Form::close() }}
        </div>
    </section>
@endsection
