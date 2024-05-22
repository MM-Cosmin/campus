<?php
$setting = generalSetting();
App::setLocale(getUserLanguage());

if (isset($setting->copyright_text)) {
    $copyright_text = $setting->copyright_text;
} else {
    $copyright_text = 'Copyright © 2020 All rights reserved | This template is made with by Codethemes';
}
if (isset($setting->logo)) {
    $logo = $setting->logo;
} else {
    $logo = 'public/uploads/settings/logo.png';
}
$ttl_rtl = userRtlLtl();

if (isset($setting->favicon)) {
    $favicon = $setting->favicon;
} else {
    $favicon = 'public/backEnd/img/favicon.png';
}

$login_background = App\SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();

if (empty($login_background)) {
    $css = 'background: url(' . url('public/backEnd/img/in_registration.png') . ')  no-repeat center; background-size: cover; ';
} else {
    if (!empty($login_background->image)) {
        $css = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";
    } else {
        $css = 'background:' . $login_background->color;
    }
}
?>


<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if (isset($ttl_rtl) && $ttl_rtl == 1) dir="rtl" class="rtl" @endif
    style="{{ \Session::has('success') ? 'height: 100vh' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset($favicon) }}" type="image/png" />
    <title>{{ @generalSetting()->site_title ? @generalSetting()->site_title : 'Infix Edu ERP' }}
        | @lang('student.student_registration') </title>
    <meta name="_token" content="{!! csrf_token() !!}" />
    <x-root-css />
    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/css/themify-icons.css" />
    <link rel="stylesheet" href="{{ url('/public/') }}/landing/css/toastr.css">
    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/css/nice-select.css" />
    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/js/select2/select2.css" />
    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/css/fastselect.min.css" />
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/vendors/css/toastr.min.css" />
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/vendors/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/vendors/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/assets/vendors/vendors_static_style.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/rtl/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/loade.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/nice-select.css') }}" />
    @if (userRtlLtl() == 1)
        <style>
            html[dir="rtl"] .loader_style_parent_reg {
                padding-left: 25px;
                position: absolute;
                left: 10px;
                top: 5px;
            }

            html[dir="rtl"] .input-right-icon button {
                margin-left: 0;
                left: 0;
                margin-right: auto;
            }

            html[dir="rtl"] .input-right-icon button i {
                left: 22px;
                display: inline-block !important;
            }

            html[dir="rtl"] .input-right-icon button {
                margin-left: 0;
                left: 0;
                margin-right: auto;
                position: absolute;
                left: 0;
            }

            html[dir="rtl"] .mr-20 {
                margin-right: 0px;
                margin-left: 20px;
            }

            html[dir="rtl"] .ml-30 {
                margin-left: 0;
                margin-right: 30px;
            }

            html[dir="rtl"] .primary_input_field:focus~label,
            .primary_input_field.read-only-input~label,
            html[dir="rtl"] .has-content.primary_input_field~label {
                text-align: right !important;
            }

            html[dir="rtl"] .primary_input_field~label {
                left: auto;
                right: 0 !important;
                text-align: right;
            }

            .loader {
                display: none;
            }
        </style>
        <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/css/rtl/style.css" />
    @else
        <link rel="stylesheet" href="{{ url('public/backEnd/') }}/css/style.css" />
    @endif
    <link rel="stylesheet" href="{{ url('Modules/ParentRegistration/Resources/assets/css/style.css') }}">

</head>

<body class="reg_bg" style="{{ @$css }}">
    <!--================ Start Login Area =================-->
    <div class="reg_bg">

    </div>
    <section class="login-area  registration_area ">
        <div class="container">
            <div class="registration_area_logo">
                @if (!empty($setting->logo))
                    <img src="{{ asset($setting->logo) }}" alt="Login Panel">
                @endif
            </div>
            @if (\Session::has('success'))
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-12">
                        <div class="text-center white-box single_registration_area">
                            <h1>{{ __('Thank You') }}</h1>
                            <h3>{!! \Session::get('success') !!}</h3>
                            <a href="{{ url('/') }}" class="primary-btn small fix-gr-bg">
                                @lang('common.home')
                            </a>
                        </div>

                    </div>
                </div>
            @else
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-12">
                        <div class="text-center white-box single_registration_area">
                            <div class="reg_tittle mt-20" style="margin-botton: 100px;">
                                <h2>@lang('student.student_registration')</h2>
                            </div>

                            @if ($reg_setting->registration_permission == 1)
                                <form method="POST" class=""
                                    action="{{ route('parentregistration-student-store') }}"
                                    id="parent-registration" enctype="multipart/form-data">
                            @endif
                            {{ csrf_field() }}
                            @if ($errors->any())
                                <div>
                                    <div class="text-danger">{{ __('common.whoops_something_went_wrong') }}</div>
                                    <ul class="mt-1 text-danger">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <input type="hidden" id="url" value="{{ url('/') }}">
                            <div class="row">

                                @if (in_array('session', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input" id="academic-year-div">
                                            <select class="primary_select  form-control" name="academic_year"
                                                id="select-academic-year">
                                                <option data-display="@lang('student.select_academic_year') *"
                                                    value="">@lang('student.select_academic_year')</option>
                                                @foreach ($academic_years as $academic_year)
                                                    <option value="{{ $academic_year->id }}">
                                                        {{ $academic_year->year }}
                                                        [{{ $academic_year->title }}]
                                                    </option>
                                                @endforeach

                                            </select>

                                        </div>
                                        @if ($errors->has('academic_year'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('academic_year') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('class', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input" id="class-div">
                                            <select class="primary_select  form-control" name="class"
                                                id="select-class">
                                                <option data-display="{{ field_label($fields, 'class') }} *"
                                                    value="">{{ field_label($fields, 'class') }} *
                                                </option>
                                            </select>
                                            <div class="loader loader_style_parent_reg loader"
                                                id="select_class_loader">
                                                <img class="loader_img_style"
                                                    src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                                    alt="loader">
                                            </div>
                                        </div>
                                        @if ($errors->has('class'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('class') }}</div>
                                        @endif
                                    </div>

                                @endif
                                @if (in_array('section', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input" id="section-div">
                                            <select class="primary_select  form-control" name="section"
                                                id="select-section">
                                                <option data-display=" {{ field_label($fields, 'section') }} "
                                                    value="">{{ field_label($fields, 'section') }}</option>
                                            </select>
                                            <div class="loader_style_parent_reg loader" id="select_class_loader">
                                                <img class="loader_img_style"
                                                    src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                                    alt="loader">
                                            </div>
                                        </div>
                                        @if ($errors->has('section'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('section') }}</div>
                                        @endif
                                    </div>

                                @endif
                                @if (in_array('first_name', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='first_name'
                                                id="school_name"
                                                placeholder="{{ field_label($fields, 'first_name') }} *"
                                                value="{{ old('first_name') }}" />
                                        </div>
                                        @if ($errors->has('first_name'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('first_name') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('last_name', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='last_name'
                                                id="school_name"
                                                placeholder="{{ field_label($fields, 'last_name') }} *"
                                                value="{{ old('last_name') }}" />
                                        </div>
                                        @if ($errors->has('last_name'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('last_name') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('gender', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select class="primary_select  form-control" name="gender">

                                                @if (moduleStatusCheck('Lead') == true)
                                                    <option data-display="{{ field_label($fields, 'gender') }} "
                                                        value="">{{ field_label($fields, 'gender') }}
                                                    </option>
                                                @else
                                                    <option data-display="{{ field_label($fields, 'gender') }} *"
                                                        value="">{{ field_label($fields, 'first_name') }} *
                                                    </option>
                                                @endif

                                                @foreach ($genders as $gender)
                                                    <option value="{{ $gender->id }}"
                                                        {{ old('gender') == $gender->id ? 'selected' : '' }}>
                                                        {{ $gender->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('gender'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('gender') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('date_of_birth', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="no-gutters input-right-icon">
                                            <div class="primary_input">
                                                <input
                                                    class="primary_input_field mydob date form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}"
                                                    id="startDate" type="text"
                                                    name="date_of_birth"
                                                    placeholder="{{ field_label($fields, 'date_of_birth') }}"
                                                    value=""
                                                    autocomplete="off" id="date_of_birth">

                                                @if ($errors->has('date_of_birth'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('date_of_birth') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <label for="startDate" class="primary_input-icon">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('age', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='age' id="age"
                                                placeholder="{{ field_label($fields, 'age') }} *"
                                                readonly="" value="{{ old('age') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('blood_group', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select class="primary_select  form-control" name="blood_group">
                                                <option data-display="{{ field_label($fields, 'blood_group') }}"
                                                    value=""> {{ field_label($fields, 'blood_group') }}
                                                </option>
                                                @foreach ($blood_groups as $group)
                                                    <option value="{{ $group->id }}"
                                                        {{ old('blood_group') == $group->id ? 'selected' : '' }}>
                                                        {{ $group->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('blood_group'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('blood_group') }}</div>
                                        @endif
                                    </div>
                                @endif

                                @if (in_array('religion', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select class="primary_select  form-control" name="religion">
                                                <option data-display="{{ field_label($fields, 'religion') }}"
                                                    value="">{{ field_label($fields, 'religion') }}</option>
                                                @foreach ($religions as $religion)
                                                    <option value="{{ $religion->id }}"
                                                        {{ old('religion') == $religion->id ? 'selected' : '' }}>
                                                        {{ $religion->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('religion'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('religion') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('caste', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='caste' id="caste"
                                                placeholder="{{ field_label($fields, 'caste') }}"
                                                value="{{ old('caste') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('email_address', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='student_email'
                                                id="student_email"
                                                placeholder="{{ field_label($fields, 'email_address') }}"
                                                value="{{ old('student_email') }}" />
                                        </div>
                                        @if ($errors->has('student_email'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('student_email') }}</div>
                                        @endif
                                    </div>
                                @endif

                                @if (in_array('id_number', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='id_number'
                                                id="id_number"
                                                placeholder="{{ field_label($fields, 'id_number') }}
                                                       @if (moduleStatusCheck('Lead') == true) * @endif"
                                                value="{{ old('id_number') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('phone_number', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='student_mobile'
                                                id="student_mobile"
                                                placeholder="{{ field_label($fields, 'student_mobile') }}"
                                                value="{{ old('student_mobile') }}" />
                                        </div>
                                        <span class="text-danger error-message" id="student_mobile_error"></span>
                                    </div>
                                @endif
                                @if (in_array('lead_city', $active_fields) && moduleStatusCheck('Lead') == true)
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select class="primary_select  form-control"
                                                name="lead_city">
                                                <option data-display="{{ field_label($fields, 'lead_city') }}"
                                                    value="">{{ field_label($fields, 'lead_city') }}</option>
                                                @foreach ($lead_city as $city)
                                                    <option value="{{ $city->id }}"
                                                        {{ old('city') == $city->id ? 'selected' : '' }}>
                                                        {{ $city->city_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('lead_city'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('lead_city') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('source_id', $active_fields) && moduleStatusCheck('Lead') == true)
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select class="primary_select  form-control"
                                                name="source_id">
                                                <option data-display="{{ field_label($fields, 'source_id') }}"
                                                    value="">{{ field_label($fields, 'source_id') }}</option>
                                                @foreach ($sources as $source)
                                                    <option value="{{ $source->id }}"
                                                        {{ old('source_id') == $source->id ? 'selected' : '' }}>
                                                        {{ $source->source_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('source_id'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('source_id') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('student_category_id', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select class="primary_select  form-control"
                                                name="student_category_id">
                                                <option
                                                    data-display="{{ field_label($fields, 'student_category_id') }}"
                                                    value="">{{ field_label($fields, 'student_category_id') }}
                                                </option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('student_category_id'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('student_category_id') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('student_group_id', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select class="primary_select  form-control"
                                                name="student_group_id">
                                                <option data-display="{{ field_label($fields, 'student_group_id') }}"
                                                    value="">{{ field_label($fields, 'student_group_id') }}
                                                </option>
                                                @foreach ($groups as $group)
                                                    <option value="{{ $group->id }}"
                                                        {{ old('group') == $group->id ? 'selected' : '' }}>
                                                        {{ $group->group }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('group'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('group') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('height', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='height' id="height"
                                                placeholder="{{ field_label($fields, 'height') }}"
                                                value="{{ old('height') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('weight', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='weight' id="weight"
                                                placeholder="{{ field_label($fields, 'weight') }}"
                                                value="{{ old('weight') }}" />
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('photo', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field" type="text"
                                                        id="placeholderPhoto"
                                                        placeholder="{{ field_label($fields, 'photo') }}"
                                                        readonly="">


                                                    @if ($errors->has('file'))
                                                        <span class="text-danger d-block">
                                                            <strong>{{ @$errors->first('file') }}
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="photo">@lang('common.browse')</label>
                                                    <input type="file" class="d-none"
                                                        value="{{ old('photo') }}"
                                                        name="photo" id="photo">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- fathers details --}}
                                @if (in_array('fathers_name', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='fathers_name'
                                                id="fathers_name"
                                                placeholder="{{ field_label($fields, 'fathers_name') }}"
                                                value="{{ old('fathers_name') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('fathers_occupation', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='fathers_occupation'
                                                id="fathers_occupation"
                                                placeholder="{{ field_label($fields, 'fathers_occupation') }}"
                                                value="{{ old('fathers_occupation') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('fathers_phone', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="number" name='fathers_phone'
                                                id="fathers_phone"
                                                placeholder="{{ field_label($fields, 'fathers_phone') }}"
                                                value="{{ old('fathers_phone') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('fathers_photo', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field" type="text"
                                                        id="placeholderFathersName"
                                                        placeholder="{{ field_label($fields, 'fathers_photo') }}"
                                                        readonly="">

                                                    @if ($errors->has('file'))
                                                        <span class="text-danger d-block">
                                                            <strong>{{ @$errors->first('file') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="fathers_photo">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="fathers_photo"
                                                        id="fathers_photo">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- end fathers details --}}

                                {{-- mothers details --}}
                                @if (in_array('mothers_name', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='mothers_name'
                                                id="mothers_name"
                                                placeholder="{{ field_label($fields, 'mothers_name') }}"
                                                value="{{ old('mothers_name') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('mothers_occupation', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='mothers_occupation'
                                                id="mothers_occupation"
                                                placeholder="{{ field_label($fields, 'mothers_occupation') }}"
                                                value="{{ old('mothers_occupation') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('mothers_phone', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="number" name='mothers_phone'
                                                id="mothers_phone"
                                                placeholder="{{ field_label($fields, 'mothers_phone') }}"
                                                value="{{ old('mothers_phone') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('mothers_photo', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field" type="text"
                                                        id="placeholderMothersName"
                                                        placeholder="{{ field_label($fields, 'mothers_photo') }}"
                                                        readonly="">

                                                    @if ($errors->has('file'))
                                                        <span class="text-danger d-block">
                                                            <strong>{{ @$errors->first('file') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="mothers_photo">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="mothers_photo"
                                                        id="mothers_photo">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- end mohters details --}}

                                {{-- @if (in_array('class', $active_fields))
                                        <div class="col-lg-6">
                                            <div class="form-group input-group">
                                                <input class="form-control" type="text" name='student_mobile'
                                                       id="student_mobile"
                                                       placeholder="@lang('parentregistration::parentRegistration.student_mobile')"
                                                       value="{{old('student_mobile')}}"/>
                                            </div>
                                            <span class="text-danger error-message" id="student_mobile_error"></span>
                                        </div>
                                    @endif --}}
                                {{-- end Guardians Details --}}
                                @if (in_array('guardians_name', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='guardian_name'
                                                id="guardians_name"
                                                placeholder="{{ field_label($fields, 'guardians_name') }} *"
                                                value="{{ old('guardian_name') }}" />
                                        </div>
                                        @if ($errors->has('guardian_name'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('guardian_name') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('relation', $active_fields))
                                    <div class="col-lg-6 d-flex relation-button">
                                        <p class="text-uppercase mb-0">
                                            {{ field_label($fields, 'relation') }}
                                        </p>
                                        <div class="d-flex radio-btn-flex ml-30 mt-1">
                                            <div class="mr-20">
                                                <input type="radio" name="relationButton" id="relationFather"
                                                    value="F"
                                                    class="common-radio relationButton"
                                                    {{ old('relationButton', 'F') == 'F' ? 'checked' : '' }}>
                                                <label for="relationFather">@lang('student.father')</label>
                                            </div>
                                            <div class="mr-20">
                                                <input type="radio" name="relationButton" id="relationMother"
                                                    value="M"
                                                    class="common-radio relationButton"
                                                    {{ old('relationButton') == 'M' ? 'checked' : '' }}>
                                                <label for="relationMother">@lang('student.mother')</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationButton" id="relationOther"
                                                    value="O"
                                                    class="common-radio relationButton"
                                                    {{ old('relationButton') == 'O' ? 'checked' : '' }}>
                                                <label for="relationOther">@lang('student.Other')</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('guardians_email', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='guardian_email'
                                                id="guardians_email"
                                                placeholder="{{ field_label($fields, 'guardians_email') }} *"
                                                value="{{ old('guardian_email') }}" />
                                        </div>
                                        @if ($errors->has('guardian_email'))
                                            <div class="text-danger error-message invalid-select mb-10"
                                                id="guardian_email_error">{{ $errors->first('guardian_email') }}
                                            </div>
                                        @endif

                                    </div>
                                @endif
                                @if (in_array('guardians_phone', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='guardian_mobile'
                                                id="guardians_phone"
                                                placeholder="{{ field_label($fields, 'guardians_phone') }} "
                                                value="{{ old('guardian_mobile') }}" />
                                        </div>
                                        @if ($errors->has('guardian_mobile'))
                                            <div class="text-danger error-message invalid-select mb-10"
                                                id="guardian_mobile_error">{{ $errors->first('guardian_mobile') }}
                                            </div>
                                        @else
                                            <div class="text-danger error-message invalid-select mb-10"
                                                id="guardian_mobile_error"></div>
                                        @endif
                                        </span>
                                    </div>
                                @endif

                                @if (in_array('guardians_photo', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field" type="text"
                                                        id="placeholderGuardiansName"
                                                        placeholder="{{ field_label($fields, 'guardians_photo') }}"
                                                        readonly="">

                                                    @if ($errors->has('file'))
                                                        <span class="text-danger d-block">
                                                            <strong>{{ @$errors->first('file') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="guardians_photo">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="guardians_photo"
                                                        id="guardians_photo">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('guardians_occupation', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='guardians_occupation'
                                                id="guardians_occupation"
                                                placeholder="{{ field_label($fields, 'guardians_occupation') }}"
                                                value="{{ old('guardians_occupation') }}" />
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('guardians_address', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='guardians_address'
                                                id="guardians_address"
                                                placeholder="{{ field_label($fields, 'guardians_address') }}"
                                                value="{{ old('guardians_address') }}" />
                                        </div>
                                    </div>
                                @endif
                                {{-- end Guardians Details --}}
                                {{-- address --}}
                                @if (in_array('current_address', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='current_address'
                                                id="current_address"
                                                placeholder="{{ field_label($fields, 'current_address') }}"
                                                value="{{ old('current_address') }}" />
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('permanent_address', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name='permanent_address'
                                                id="permanent_address"
                                                placeholder="{{ field_label($fields, 'permanent_address') }}"
                                                value="{{ old('permanent_address') }}" />
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12">
                                    <div class="form-group input-group">
                                        <textarea class="form-control" name='how_do_know_us' id="school_name"
                                            placeholder="{{ __('parentregistration::parentRegistration.how_do_you_know_us') }}?">{{ old('how_do_know_us') }}</textarea>
                                    </div>
                                </div>

                                {{-- end address --}}

                                @if (in_array('route', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select class="primary_select  form-control" name="route"
                                                id="route">
                                                <option data-display="{{ field_label($fields, 'route') }}"
                                                    value="">{{ field_label($fields, 'route') }}</option>
                                                @foreach ($route_lists as $route_list)
                                                    <option value="{{ $route_list->id }}"
                                                        {{ old('route') == $route_list->id ? 'selected' : '' }}>
                                                        {{ $route_list->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('group'))
                                            <div class="text-danger error-message invalid-select mb-10">
                                                {{ $errors->first('group') }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if (in_array('vehicle', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input " id="select_vehicle_div">
                                            <select
                                                class="primary_select  form-control{{ $errors->has('vehicle') ? ' is-invalid' : '' }}"
                                                name="vehicle" id="selectVehicle">
                                                <option data-display="{{ field_label($fields, 'vehicle') }} "
                                                    value="">{{ field_label($fields, 'vehicle') }}</option>
                                            </select>
                                            <div class="loader_style_parent_reg loader"
                                                id="select_transport_loader">
                                                <img class="loader_img_style"
                                                    src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                                    alt="loader">
                                            </div>

                                            @if ($errors->has('vehicle'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('vehicle') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('dormitory_name', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input ">
                                            <select
                                                class="primary_select  form-control{{ $errors->has('dormitory_name') ? ' is-invalid' : '' }}"
                                                name="dormitory_name" id="SelectDormitory">
                                                <option data-display=" {{ field_label($fields, 'dormitory_name') }} "
                                                    value="">{{ field_label($fields, 'dormitory_name') }}
                                                </option>
                                                @foreach ($dormitory_lists as $dormitory_list)
                                                    <option value="{{ $dormitory_list->id }}"
                                                        {{ old('dormitory_name') == $dormitory_list->id ? 'selected' : '' }}>
                                                        {{ $dormitory_list->dormitory_name }}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('dormitory_name'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('dormitory_name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('room_number', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="primary_input " id="roomNumberDiv">
                                            <select
                                                class="primary_select  form-control{{ $errors->has('room_number') ? ' is-invalid' : '' }}"
                                                name="room_number" id="selectRoomNumber">
                                                <option data-display="{{ field_label($fields, 'room_number') }}"
                                                    value="">{{ field_label($fields, 'room_number') }}</option>
                                            </select>
                                            <div class="loader_style_parent_reg loader"
                                                id="select_dormitory_loader">
                                                <img class="loader_img_style"
                                                    src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                                    alt="loader">
                                            </div>

                                            @if ($errors->has('room_number'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('room_number') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('national_id_number', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text"
                                                placeholder="{{ field_label($fields, 'national_id_number') }}"
                                                name="national_id_number" value="{{ old('national_id_number') }}">

                                            @if ($errors->has('national_id_number'))
                                                <span class="text-danger">
                                                    {{ $errors->first('national_id_number') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('local_id_number', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" name="local_id_number"
                                                placeholder="{{ field_label($fields, 'local_id_number') }}"
                                                value="{{ old('local_id_number') }}">


                                            @if ($errors->has('local_id_number'))
                                                <span class="text-danger">
                                                    {{ $errors->first('local_id_number') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('bank_account_number', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text"
                                                placeholder="{{ field_label($fields, 'bank_account_number') }}"
                                                name="bank_account_number"
                                                value="{{ old('bank_account_number') }}">


                                            @if ($errors->has('bank_account_number'))
                                                <span class="text-danger">
                                                    {{ $errors->first('bank_account_number') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('bank_name', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text"
                                                placeholder="{{ field_label($fields, 'bank_name') }}"
                                                name="bank_name" value="{{ old('bank_name') }}">


                                            @if ($errors->has('bank_name'))
                                                <span class="text-danger">
                                                    {{ $errors->first('bank_name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('previous_school_details', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text"
                                                placeholder="{{ field_label($fields, 'previous_school_details') }} "
                                                name="previous_school_details"
                                                value="{{ old('previous_school_details') }}">


                                            @if ($errors->has('previous_school_details'))
                                                <span class="text-danger">
                                                    {{ $errors->first('previous_school_details') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('additional_notes', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text"
                                                placeholder="{{ field_label($fields, 'additional_notes') }}"
                                                name="additional_notes" value="{{ old('additional_notes') }}">


                                            @if ($errors->has('additional_notes'))
                                                <span class="text-danger">
                                                    {{ $errors->first('additional_notes') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('ifsc_code', $active_fields))
                                    <div class="col-lg-6">
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text"
                                                placeholder="{{ field_label($fields, 'ifsc_code') }}"
                                                name="ifsc_code" value="{{ old('ifsc_code') }}">


                                            @if ($errors->has('ifsc_code'))
                                                <span class="text-danger">
                                                    {{ $errors->first('ifsc_code') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('document_file_1', $active_fields))
                                    <div class="col-lg-6 pb-20">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input ">
                                                    <input type="hidden" name="document_title_1"
                                                        value="{{ field_label($fields, 'document_file_1') }}">
                                                    <input class="primary_input_field" type="text"
                                                        id="placeholderFileOneName"
                                                        placeholder="{{ field_label($fields, 'document_file_1') }}"
                                                        readonly="">

                                                    @if ($errors->has('file'))
                                                        <span class="text-danger d-block">
                                                            <strong>{{ @$errors->first('file') }}
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_1">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="document_file_1"
                                                        id="document_file_1">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('document_file_2', $active_fields))
                                    <div class="col-lg-6 pb-20">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input ">
                                                    <input type="hidden" name="document_title_2"
                                                        value="{{ field_label($fields, 'document_file_2') }}">
                                                    <input class="primary_input_field" type="text"
                                                        id="placeholderFileTwoName"
                                                        placeholder="{{ field_label($fields, 'document_file_2') }}"
                                                        readonly="">

                                                    @if ($errors->has('file'))
                                                        <span class="text-danger d-block">
                                                            <strong>{{ @$errors->first('file') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_2">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="document_file_2"
                                                        id="document_file_2">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (in_array('document_file_3', $active_fields))
                                    <div class="col-lg-6 pb-20">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input ">
                                                    <input type="hidden" name="document_title_3"
                                                        value="{{ field_label($fields, 'document_file_3') }}">
                                                    <input class="primary_input_field" type="text"
                                                        id="placeholderFileThreeName"
                                                        placeholder="{{ field_label($fields, 'document_file_3') }}"
                                                        readonly="">

                                                    @if ($errors->has('file'))
                                                        <span class="text-danger d-block">
                                                            <strong>{{ @$errors->first('file') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_3">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="document_file_3"
                                                        id="document_file_3">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('document_file_4', $active_fields))
                                    <div class="col-lg-6 pb-20">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field" type="text"
                                                        id="placeholderFileFourName"
                                                        placeholder="{{ field_label($fields, 'document_file_4') }}"
                                                        readonly="">
                                                    <input type="hidden" name="document_title_4"
                                                        value="{{ field_label($fields, 'document_file_4') }}">

                                                    @if ($errors->has('file'))
                                                        <span class="text-danger d-block">
                                                            <strong>{{ @$errors->first('file') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_4">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="document_file_4"
                                                        id="document_file_4">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($custom_fields)
                                    @include('parentregistration::_custom_field')
                                @endif



                                @if ($captcha)
                                    <div class="col-lg-12 text-center">
                                        {!! $captcha->renderJs() !!}
                                        {!! $captcha->display() !!}
                                        <span class="text-danger"
                                            id="g-recaptcha-error">{{ $errors->first('g-recaptcha-response') }}</span>
                                    </div>
                                @endif

                            </div>

                            @php
                                $tooltip = '';
                                if ($reg_setting->registration_permission == 1) {
                                    $tooltip = '';
                                } else {
                                    $tooltip = "You Can't Registration Now";
                                }
                            @endphp
                            <div class="row mt-40">
                                <div class="col-lg-12">
                                    <div class="login_button text-center">
                                        <button type="submit" class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                            title="{{ @$tooltip }}">
                                            <span class="ti-check"></span>
                                            @lang('common.submit')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if ($reg_setting->footer_note_status == 1)
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mt-30">
                                            {{ $reg_setting->footer_note_text }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!--================ Start End Login Area =================-->
    <!--================ Footer Area =================-->
    <footer class="footer_area registration_footer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <p>{!! $copyright_text !!}</p>
                </div>
            </div>
        </div>
    </footer>
    <!--================ End Footer Area =================-->
    <script src="{{ url('/') }}/public/backEnd/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="{{ url('/') }}/public/backEnd/vendors/js/popper.js"></script>
    <script src="{{ url('/') }}/public/backEnd/vendors/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/public/backEnd/vendors/js/nice-select.min.js"></script>
    <script type="text/javascript" src="{{ asset('public/backEnd/') }}/vendors/js/toastr.min.js"></script>
    <script src="{{ url('/') }}/public/backEnd/js/login.js"></script>
    <script src="{{ url('public/backEnd/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('/') }}/public/backEnd/js/main.js"></script>
    <script src="{{ url('/') }}/public/backEnd/js/custom.js"></script>
    <script src="{{ url('/public/js/registration_custom.js') }}"></script>
    <script>
        $('#startDate').datepicker({
            Default: {
                leftArrow: '<i class="fa fa-long-arrow-left"></i>',
                rightArrow: '<i class="fa fa-long-arrow-right"></i>'
            }
        });
    </script>
    {!! Toastr::message() !!}
    @yield('script')
</body>

</html>
