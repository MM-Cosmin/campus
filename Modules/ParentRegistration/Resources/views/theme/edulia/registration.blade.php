<?php
$setting = generalSetting();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- All Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(generalSetting()->favicon) }}" type="image/png" />
    <title>{{ @generalSetting()->site_title ? @generalSetting()->site_title : 'Infix Edu ERP' }}
        | @lang('student.student_registration') </title>
    <meta name="_token" content="{!! csrf_token() !!}" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Fonts -->

    <!-- DatePicker CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/datepicker/gijgo.min.css') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/fontawesome.all.min.css') }}">

    <!-- themify CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/themify/themify-icons.min.css') }}">

    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/nice-select/nice-select.css') }}">

    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/style.css') }}">
    <style>
        .section_padding {
            padding: 50px 0px !important;
        }

        .file_uploader button {
            position: absolute;
            right: 0;
            border: 0;
            bottom: 7px;
            right: 7px;
            padding: 0;
            background: transparent;
            z-index: 99;
            background: #4068FC;
            padding: 4px 10px;
            color: white;
        }

        .file_uploader button label {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- registration area start -->
    <section class="section_padding gray_bg reg">
        <div class="container">
            <div class="registration_area_logo text-center">
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
                <div class="row">
                    <div class="col-xl-6 offset-xl-3 col-md-10 offset-md-1 col-sm-12" id='page-width-clearfix'>
                        <div class="reg_wrapper">
                            @if ($reg_setting->registration_permission == 1)
                                <form method="POST" class=""
                                    action="{{ route('parentregistration-student-store') }}"
                                    id="parent-registration" enctype="multipart/form-data">
                            @endif
                            {{ csrf_field() }}
                            @if ($errors->any())
                                <div>
                                    <div class="text-danger">{{ __('common.whoops_something_went_wrong') }}</div>
                                </div>
                            @endif
                            <input type="hidden" id="url" value="{{ url('/') }}">

                            {{-- Academic Details Starts --}}
                            <div class="reg_wrapper_item">
                                <h4><span>1</span> Academic Details</h4>
                                <div class="reg_wrapper_item_flex">
                                    @if (in_array('session', $active_fields))
                                        <div class="input-control" id="academic-year-div">
                                            <label for="#" class="input-control-label">@lang('student.academic_year')
                                                <span>*</span></label>
                                            <select name="academic_year" class="input-control-input"
                                                id="select-academic-year">
                                                <option data-display="@lang('student.select_academic_year')"
                                                    value="">@lang('student.select_academic_year')</option>
                                                @foreach ($academic_years as $academic_year)
                                                    <option value="{{ $academic_year->id }}">
                                                        {{ $academic_year->year }}
                                                        [{{ $academic_year->title }}]
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('academic_year'))
                                                <span class="text-danger" >{{ @$errors->first('academic_year') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (in_array('class', $active_fields))
                                        <div class="input-control" id="class-div">
                                            <label for="#" class="input-control-label">
                                                {{ field_label($fields, 'class') ? field_label($fields, 'class') : __('student.class') }} <span>*</span>
                                            </label>
                                            <select name="class" class="input-control-input" id="select-class">
                                                <option data-display="{{ field_label($fields, 'class') }}"
                                                    value="">{{ field_label($fields, 'class') }} </option>
                                            </select>
                                            @if ($errors->has('class'))
                                                <span class="text-danger" >{{ @$errors->first('class') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (in_array('section', $active_fields))
                                        <div class="input-control" id="section-div">
                                            <label for="#"
                                                class="input-control-label">@lang('student.section') </label>
                                            <select name="section" class="input-control-input" id="select-section">
                                                <option data-display="{{ field_label($fields, 'section') }}"
                                                    value="">{{ field_label($fields, 'section') }} </option>
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- Academic Details Ends --}}

                            {{-- Student Details Starts --}}
                            <div class="reg_wrapper_item">
                                <h4><span>2</span> Student Details</h4>
                                <div class="reg_wrapper_item_flex">
                                    @if (in_array('first_name', $active_fields))
                                        <div class="input-control">
                                            <label for="first_name"
                                                class="input-control-label">{{ field_label($fields, 'first_name') }}
                                                <span>*</span></label>
                                            <input type="text" name='first_name' id="first_name"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'first_name') }}'>
                                            @if ($errors->has('first_name'))
                                                <span class="text-danger" >{{ @$errors->first('first_name') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (in_array('last_name', $active_fields))
                                        <div class="input-control">
                                            <label for="last_name"
                                                class="input-control-label">{{ field_label($fields, 'last_name') }}
                                                <span>*</span></label>
                                            <input type="text" name='last_name' id="last_name"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'last_name') }}'>
                                            @if ($errors->has('last_name'))
                                                <span class="text-danger" >{{ @$errors->first('last_name') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (in_array('date_of_birth', $active_fields))
                                        <div class="input-control">
                                            <label for="datepicker3"
                                                class="input-control-label">{{ field_label($fields, 'date_of_birth') }}
                                                <span>*</span></label>
                                            <input type="text" name="date_of_birth"
                                                class="input-control-input mydob" placeholder='{{ date('m/d/Y') }}'
                                                id='datepicker3'>
                                            @if ($errors->has('date_of_birth'))
                                                <span class="text-danger" >{{ @$errors->first('date_of_birth') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (in_array('email_address', $active_fields))
                                        <div class="input-control">
                                            <label for="student_email"
                                                class="input-control-label">{{ field_label($fields, 'email_address') }}
                                            </label>
                                            <input type="text" name='student_email' id="student_email"
                                                class="input-control-input" value="{{ old('student_email') }}"
                                                placeholder='{{ field_label($fields, 'email_address') }}'>
                                        </div>
                                    @endif
                                    @if (in_array('gender', $active_fields))
                                        <div class="input-control">
                                            <label for="gender"
                                                class="input-control-label">{{ field_label($fields, 'gender') }}
                                            </label>
                                            <select name="gender" id="gender" class="input-control-input">
                                                @if (moduleStatusCheck('Lead') == true)
                                                    <option data-display="{{ field_label($fields, 'gender') }} "
                                                        value="">{{ field_label($fields, 'gender') }}
                                                    </option>
                                                @else
                                                    <option data-display="{{ field_label($fields, 'gender') }} "
                                                        value="">{{ field_label($fields, 'gender') }}
                                                    </option>
                                                @endif
                                                @foreach ($genders as $gender)
                                                    <option value="{{ $gender->id }}"
                                                        {{ old('gender') == $gender->id ? 'selected' : '' }}>
                                                        {{ $gender->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('phone_number', $active_fields))
                                        <div class="input-control">
                                            <label for="student_mobile"
                                                class="input-control-label">{{ field_label($fields, 'phone_number') }}
                                                <span>*</span></label>
                                            <input type="text" name='student_mobile' id="student_mobile"
                                                class="input-control-input" value="{{ old('student_mobile') }}"
                                                placeholder='{{ field_label($fields, 'phone_number') }}'>
                                            @if ($errors->has('student_mobile'))
                                                <span class="text-danger" >{{ @$errors->first('student_mobile') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (in_array('age', $active_fields))
                                        <div class="input-control">
                                            <label for="age"
                                                class="input-control-label">{{ field_label($fields, 'age') }}</label>
                                            <input type="text" name='age' id="age"
                                                class="input-control-input" value="{{ old('age') }}"
                                                placeholder='{{ field_label($fields, 'age') }}'>
                                        </div>
                                    @endif
                                    @if (in_array('blood_group', $active_fields))
                                        <div class="input-control">
                                            <label for="blood_group"
                                                class="input-control-label">{{ field_label($fields, 'blood_group') }}</label>
                                            <select name="blood_group" id="blood_group" class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'blood_group') }} "
                                                    value="">{{ field_label($fields, 'blood_group') }}
                                                </option>
                                                @foreach ($blood_groups as $group)
                                                    <option value="{{ $group->id }}"
                                                        {{ old('group') == $group->id ? 'selected' : '' }}>
                                                        {{ $group->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('religion', $active_fields))
                                        <div class="input-control">
                                            <label for="religion"
                                                class="input-control-label">{{ field_label($fields, 'religion') }}</label>
                                            <select name="religion" id="religion" class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'religion') }} "
                                                    value="">{{ field_label($fields, 'religion') }}
                                                </option>
                                                @foreach ($religions as $religion)
                                                    <option value="{{ $religion->id }}"
                                                        {{ old('religion') == $religion->id ? 'selected' : '' }}>
                                                        {{ $religion->base_setup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('caste', $active_fields))
                                        <div class="input-control">
                                            <label for="caste"
                                                class="input-control-label">{{ field_label($fields, 'caste') }}</label>
                                            <input type="text" name='caste' id="caste"
                                                class="input-control-input" value="{{ old('caste') }}"
                                                placeholder='{{ field_label($fields, 'caste') }}'>
                                        </div>
                                    @endif
                                    @if (in_array('id_number', $active_fields))
                                        <div class="input-control">
                                            <label for="id_number"
                                                class="input-control-label">{{ field_label($fields, 'id_number') }}</label>
                                            <input type="text" name='id_number' id="id_number"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'id_number') }}
                                            @if (moduleStatusCheck('Lead') == true) * @endif'
                                                value="{{ old('id_number') }}">
                                        </div>
                                    @endif
                                    @if (in_array('lead_city', $active_fields) && moduleStatusCheck('Lead') == true)
                                        <div class="input-control">
                                            <label for="lead_city"
                                                class="input-control-label">{{ field_label($fields, 'lead_city') }}</label>
                                            <select name="lead_city" id="lead_city" class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'lead_city') }} "
                                                    value="">{{ field_label($fields, 'lead_city') }}
                                                </option>
                                                @foreach ($lead_city as $city)
                                                    <option value="{{ $city->id }}"
                                                        {{ old('city') == $city->id ? 'selected' : '' }}>
                                                        {{ $city->city_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('source_id', $active_fields) && moduleStatusCheck('Lead') == true)
                                        <div class="input-control">
                                            <label for="source_id"
                                                class="input-control-label">{{ field_label($fields, 'source_id') }}</label>
                                            <select name="source_id" id="source_id" class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'source_id') }} "
                                                    value="">{{ field_label($fields, 'source_id') }}
                                                </option>
                                                @foreach ($sources as $source)
                                                    <option value="{{ $source->id }}"
                                                        {{ old('source') == $source->id ? 'selected' : '' }}>
                                                        {{ $source->source_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('student_category_id', $active_fields))
                                        <div class="input-control">
                                            <label for="student_category_id"
                                                class="input-control-label">{{ field_label($fields, 'student_category_id') }}</label>
                                            <select name="student_category_id" id="student_category_id"
                                                class="input-control-input">
                                                <option
                                                    data-display="{{ field_label($fields, 'student_category_id') }} "
                                                    value="">{{ field_label($fields, 'student_category_id') }}
                                                </option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('student_group_id', $active_fields))
                                        <div class="input-control">
                                            <label for="student_group_id"
                                                class="input-control-label">{{ field_label($fields, 'student_group_id') }}</label>
                                            <select name="student_group_id" id="student_group_id"
                                                class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'student_group_id') }} "
                                                    value="">{{ field_label($fields, 'student_group_id') }}
                                                </option>
                                                @foreach ($groups as $group)
                                                    <option value="{{ $group->id }}"
                                                        {{ old('group') == $group->id ? 'selected' : '' }}>
                                                        {{ $group->group }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('height', $active_fields))
                                        <div class="input-control">
                                            <label for="height"
                                                class="input-control-label">{{ field_label($fields, 'height') }}</label>
                                            <input type="text" name='height' id="height"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'height') }}'
                                                value="{{ old('height') }}">
                                        </div>
                                    @endif
                                    @if (in_array('weight', $active_fields))
                                        <div class="input-control">
                                            <label for="weight"
                                                class="input-control-label">{{ field_label($fields, 'weight') }}</label>
                                            <input type="text" name='weight' id="weight"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'weight') }}'
                                                value="{{ old('weight') }}">
                                        </div>
                                    @endif

                                    @if (in_array('photo', $active_fields))
                                        <div class="position-relative input-control file_uploader">
                                            <label for="#"
                                                class="input-control-label">{{ field_label($fields, 'photo') }}</label>
                                            <input class="input-control-input" id="placeholderPhoto" type="text"
                                                placeholder="{{ field_label($fields, 'photo') }}" readonly>
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                    for="photo">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="photo"
                                                    value="{{ old('photo') }}" id="photo">
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- Student Details Ends --}}

                            {{-- Guardian Details Starts --}}
                            <div class="reg_wrapper_item">
                                <h4><span>3</span> Guardian Details</h4>
                                <div class="reg_wrapper_item_flex">
                                    @if (in_array('fathers_name', $active_fields))
                                        <div class="input-control">
                                            <label for="fathers_name"
                                                class="input-control-label">{{ field_label($fields, 'fathers_name') }}</label>
                                            <input type="text" name='fathers_name' id="fathers_name"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'fathers_name') }}'
                                                value="{{ old('fathers_name') }}">
                                        </div>
                                    @endif
                                    @if (in_array('fathers_occupation', $active_fields))
                                        <div class="input-control">
                                            <label for="fathers_occupation"
                                                class="input-control-label">{{ field_label($fields, 'fathers_occupation') }}</label>
                                            <input type="text" name='fathers_occupation' id="fathers_occupation"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'fathers_occupation') }}'
                                                value="{{ old('fathers_occupation') }}">
                                        </div>
                                    @endif
                                    @if (in_array('fathers_phone', $active_fields))
                                        <div class="input-control">
                                            <label for="fathers_phone"
                                                class="input-control-label">{{ field_label($fields, 'fathers_phone') }}</label>
                                            <input type="text" name='fathers_phone' id="fathers_phone"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'fathers_phone') }}'
                                                value="{{ old('fathers_phone') }}">
                                        </div>
                                    @endif

                                    @if (in_array('fathers_photo', $active_fields))
                                        <div class="position-relative input-control file_uploader">
                                            <label for="#"
                                                class="input-control-label">{{ field_label($fields, 'fathers_photo') }}</label>
                                            <input class="input-control-input" id="placeholderFathersName" type="text"
                                                placeholder="{{ field_label($fields, 'fathers_photo') }}" readonly>
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                    for="fathers_photo">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="fathers_photo"
                                                    value="{{ old('fathers_photo') }}" id="fathers_photo">
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="reg_wrapper_item_flex">
                                    @if (in_array('mothers_name', $active_fields))
                                        <div class="input-control">
                                            <label for="mothers_name"
                                                class="input-control-label">{{ field_label($fields, 'mothers_name') }}</label>
                                            <input type="text" name='mothers_name' id="mothers_name"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'mothers_name') }}'
                                                value="{{ old('mothers_name') }}">
                                        </div>
                                    @endif

                                    @if (in_array('mothers_occupation', $active_fields))
                                        <div class="input-control">
                                            <label for="mothers_occupation"
                                                class="input-control-label">{{ field_label($fields, 'mothers_occupation') }}</label>
                                            <input type="text" name='mothers_occupation' id="mothers_occupation"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'mothers_occupation') }}'
                                                value="{{ old('mothers_occupation') }}">
                                        </div>
                                    @endif

                                    @if (in_array('mothers_phone', $active_fields))
                                        <div class="input-control">
                                            <label for="mothers_phone"
                                                class="input-control-label">{{ field_label($fields, 'mothers_phone') }}</label>
                                            <input type="text" name='mothers_phone' id="mothers_phone"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'mothers_phone') }}'
                                                value="{{ old('mothers_phone') }}">
                                        </div>
                                    @endif

                                    @if (in_array('mothers_photo', $active_fields))
                                        <div class="position-relative input-control file_uploader">
                                            <label for="#"
                                                class="input-control-label">{{ field_label($fields, 'mothers_photo') }}</label>
                                            <input class="input-control-input" id="placeholderMothersName" type="text"
                                                placeholder="{{ field_label($fields, 'mothers_photo') }}" readonly>
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                    for="mothers_photo">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="mothers_photo"
                                                    value="{{ old('mothers_photo') }}" id="mothers_photo">
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                
                                @if (in_array('relation', $active_fields))
                                    <div class="input-control select-guardian">
                                        <span>{{ field_label($fields, 'relation') }}:</span>
                                        <label for="relationFather" class="page_radio">
                                            <input class="relationButton" type="radio" value="F"
                                                name='relationButton'
                                                id="relationFather"
                                                {{ old('relationButton', 'F') == 'F' ? 'checked' : '' }}>
                                            <span class="page_radio_title">@lang('student.father')</span>
                                        </label>
                                        <label for="relationMother" class="page_radio">
                                            <input class="relationButton" type="radio" value="M"
                                                name='relationButton'
                                                id="relationMother"
                                                {{ old('relationButton', 'M') == 'M' ? 'checked' : '' }}>
                                            <span class="page_radio_title">@lang('student.mother')</span>
                                        </label>
                                        <label for="relationOther" class="page_radio">
                                            <input class="relationButton" type="radio" value="O"
                                                name='relationButton'
                                                id="relationOther"
                                                {{ old('relationButton', 'O') == 'O' ? 'checked' : '' }}>
                                            <span class="page_radio_title">@lang('student.Other')</span>
                                        </label>
                                    </div>
                                @endif
                                <div class="reg_wrapper_item_flex">
                                    @if (in_array('guardians_name', $active_fields))
                                        <div class="input-control">
                                            <label for="guardians_name"
                                                class="input-control-label">{{ field_label($fields, 'guardians_name') }}
                                            </label>
                                            <input type="text" name='guardian_name' id="guardians_name"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'guardians_name') }}'
                                                value="{{ old('guardian_name') }}">
                                        </div>
                                    @endif
                                    @if (in_array('guardians_email', $active_fields))
                                        <div class="input-control">
                                            <label for="guardians_email"
                                                class="input-control-label">{{ field_label($fields, 'guardians_email') }}
                                                <span>*</span></label>
                                            <input type="text" name='guardian_email' id="guardians_email"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'guardians_email') }}'
                                                value="{{ old('guardian_email') }}">
                                            @if ($errors->has('guardian_email'))
                                                <span class="text-danger" >{{ @$errors->first('guardian_email') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (in_array('guardians_phone', $active_fields))
                                        <div class="input-control">
                                            <label for="guardians_phone"
                                                class="input-control-label">{{ field_label($fields, 'guardians_phone') }}
                                            </label>
                                            <input type="text" name='guardian_mobile' id="guardians_phone"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'guardians_phone') }}'
                                                value="{{ old('guardian_mobile') }}">
                                        </div>
                                    @endif
                                    @if (in_array('guardians_occupation', $active_fields))
                                        <div class="input-control">
                                            <label for="guardians_occupation"
                                                class="input-control-label">{{ field_label($fields, 'guardians_occupation') }}
                                            </label>
                                            <input type="text" name='guardians_occupation'
                                                id="guardians_occupation"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'guardians_occupation') }}'
                                                value="{{ old('guardians_occupation') }}">
                                        </div>
                                    @endif
                                </div>
                                @if (in_array('guardians_photo', $active_fields))
                                    <div class="position-relative input-control file_uploader">
                                        <label for="#"
                                            class="input-control-label">{{ field_label($fields, 'guardians_photo') }}</label>
                                        <input class="input-control-input" id="placeholderGuardiansName"
                                            type="text"
                                            placeholder="{{ field_label($fields, 'guardians_photo') }}" readonly>
                                        <button class="" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="guardians_photo">@lang('common.browse')</label>
                                            <input type="file" name="guardians_photo"
                                                value="{{ old('guardians_photo') }}" id="guardians_photo">
                                        </button>
                                    </div>
                                @endif
                                <div class="reg_wrapper_item_flex">
                                    @if (in_array('guardians_address', $active_fields))
                                        <div class="input-control">
                                            <label for="guardians_address"
                                                class="input-control-label">{{ field_label($fields, 'guardians_address') }}</label>
                                            <input type="text" name='guardians_address' id="guardians_address"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'guardians_address') }}'
                                                value="{{ old('guardians_address') }}">
                                        </div>
                                    @endif
                                    @if (in_array('current_address', $active_fields))
                                        <div class="input-control">
                                            <label for="current_address"
                                                class="input-control-label">{{ field_label($fields, 'current_address') }}</label>
                                            <input type="text" name='current_address' id="current_address"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'current_address') }}'
                                                value="{{ old('current_address') }}">
                                        </div>
                                    @endif
                                    
                                </div>

                                @if (in_array('permanent_address', $active_fields))
                                        <div class="input-control">
                                            <label for="permanent_address"
                                                class="input-control-label">{{ field_label($fields, 'permanent_address') }}</label>
                                            <input type="text" name='permanent_address' id="permanent_address"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'permanent_address') }}'
                                                value="{{ old('permanent_address') }}">
                                        </div>
                                @endif

                                <div class="input-control">
                                    <label for="how_do_know_us"
                                        class="input-control-label">{{ __('parentregistration::parentRegistration.how_do_you_know_us') }}?</label>
                                    <textarea class="input-control-input" name="how_do_know_us" id="how_do_know_us" rows="1"
                                        placeholder="{{ __('parentregistration::parentRegistration.how_do_you_know_us') }}?"></textarea>
                                </div>
                            </div>
                            {{-- Guardian Details Ends --}}

                            {{-- Other Details Starts --}}
                            <div class="reg_wrapper_item">
                                <h4><span>4</span> Miscellaneous Details</h4>
                                <div class="reg_wrapper_item_flex">
                                    @if (in_array('route', $active_fields))
                                        <div class="input-control">
                                            <label for="route"
                                                class="input-control-label">{{ field_label($fields, 'route') }}</label>
                                            <select name="route" id="route"
                                                class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'route') }} "
                                                    value="">{{ field_label($fields, 'route') }}
                                                </option>
                                                @foreach ($route_lists as $route_list)
                                                    <option value="{{ $route_list->id }}"
                                                        {{ old('route_list') == $route_list->id ? 'selected' : '' }}>
                                                        {{ $route_list->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('vehicle', $active_fields))
                                        <div class="input-control" id="select_vehicle_div">
                                            <label for="vehicle"
                                                class="input-control-label">{{ field_label($fields, 'vehicle') }}</label>
                                            <select name="vehicle" id="selectVehicle"
                                                class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'vehicle') }} "
                                                    value="">{{ field_label($fields, 'vehicle') }}
                                                </option>
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('dormitory_name', $active_fields))
                                        <div class="input-control">
                                            <label for="dormitory_name"
                                                class="input-control-label">{{ field_label($fields, 'dormitory_name') }}</label>
                                            <select name="dormitory_name" id="SelectDormitory"
                                                class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'dormitory_name') }} "
                                                    value="">{{ field_label($fields, 'dormitory_name') }}
                                                </option>
                                                @foreach ($dormitory_lists as $dormitory_list)
                                                    <option value="{{ $dormitory_list->id }}"
                                                        {{ old('dormitory_name') == $dormitory_list->id ? 'selected' : '' }}>
                                                        {{ $dormitory_list->dormitory_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('room_number', $active_fields))
                                        <div class="input-control" id="roomNumberDiv">
                                            <label for="room_number"
                                                class="input-control-label">{{ field_label($fields, 'room_number') }}</label>
                                            <select name="room_number" id="selectRoomNumber"
                                                class="input-control-input">
                                                <option data-display="{{ field_label($fields, 'room_number') }} "
                                                    value="">{{ field_label($fields, 'room_number') }}
                                                </option>
                                            </select>
                                        </div>
                                    @endif
                                    @if (in_array('national_id_number', $active_fields))
                                        <div class="input-control">
                                            <label for="national_id_number"
                                                class="input-control-label">{{ field_label($fields, 'national_id_number') }}</label>
                                            <input type="text" name='national_id_number' id="national_id_number"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'national_id_number') }}'
                                                value="{{ old('national_id_number') }}">
                                        </div>
                                    @endif
                                    @if (in_array('local_id_number', $active_fields))
                                        <div class="input-control">
                                            <label for="local_id_number"
                                                class="input-control-label">{{ field_label($fields, 'local_id_number') }}</label>
                                            <input type="text" name='local_id_number' id="local_id_number"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'local_id_number') }}'
                                                value="{{ old('local_id_number') }}">
                                        </div>
                                    @endif
                                    @if (in_array('bank_account_number', $active_fields))
                                        <div class="input-control">
                                            <label for="bank_account_number"
                                                class="input-control-label">{{ field_label($fields, 'bank_account_number') }}</label>
                                            <input type="text" name='bank_account_number' id="bank_account_number"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'bank_account_number') }}'
                                                value="{{ old('bank_account_number') }}">
                                        </div>
                                    @endif
                                    @if (in_array('bank_name', $active_fields))
                                        <div class="input-control">
                                            <label for="bank_name"
                                                class="input-control-label">{{ field_label($fields, 'bank_name') }}</label>
                                            <input type="text" name='bank_name' id="bank_name"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'bank_name') }}'
                                                value="{{ old('bank_name') }}">
                                        </div>
                                    @endif
                                    @if (in_array('previous_school_details', $active_fields))
                                        <div class="input-control">
                                            <label for="previous_school_details"
                                                class="input-control-label">{{ field_label($fields, 'previous_school_details') }}</label>
                                            <input type="text" name='previous_school_details'
                                                id="previous_school_details"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'previous_school_details') }}'
                                                value="{{ old('previous_school_details') }}">
                                        </div>
                                    @endif
                                    @if (in_array('additional_notes', $active_fields))
                                        <div class="input-control">
                                            <label for="additional_notes"
                                                class="input-control-label">{{ field_label($fields, 'additional_notes') }}</label>
                                            <input type="text" name='additional_notes' id="additional_notes"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'additional_notes') }}'
                                                value="{{ old('additional_notes') }}">
                                        </div>
                                    @endif
                                    
                                </div>
                                @if (in_array('ifsc_code', $active_fields))
                                        <div class="input-control">
                                            <label for="ifsc_code"
                                                class="input-control-label">{{ field_label($fields, 'ifsc_code') }}</label>
                                            <input type="text" name='ifsc_code' id="ifsc_code"
                                                class="input-control-input"
                                                placeholder='{{ field_label($fields, 'ifsc_code') }}'
                                                value="{{ old('ifsc_code') }}">
                                        </div>
                                @endif

                                @if (in_array('document_file_1', $active_fields))
                                    <div class="position-relative input-control file_uploader">
                                        <label for="#"
                                            class="input-control-label">{{ field_label($fields, 'document_file_1') }}</label>
                                        <input class="input-control-input" id="placeholderFileOneName" type="text"
                                            placeholder="{{ field_label($fields, 'document_file_1') }}" readonly>
                                        <button class="" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="document_file_1">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_1"
                                                value="{{ old('document_file_1') }}" id="document_file_1">
                                        </button>
                                    </div>
                                @endif
                                @if (in_array('document_file_2', $active_fields))
                                    <div class="position-relative input-control file_uploader">
                                        <label for="#"
                                            class="input-control-label">{{ field_label($fields, 'document_file_2') }}</label>
                                        <input class="input-control-input" id="placeholderFileTwoName" type="text"
                                            placeholder="{{ field_label($fields, 'document_file_2') }}" readonly>
                                        <button class="" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="document_file_2">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_2"
                                                value="{{ old('document_file_2') }}" id="document_file_2">
                                        </button>
                                    </div>
                                @endif
                                @if (in_array('document_file_3', $active_fields))
                                    <div class="position-relative input-control file_uploader">
                                        <label for="#"
                                            class="input-control-label">{{ field_label($fields, 'document_file_3') }}</label>
                                        <input class="input-control-input" id="placeholderFileThreeName"
                                            type="text"
                                            placeholder="{{ field_label($fields, 'document_file_3') }}" readonly>
                                        <button class="" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="document_file_3">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_3"
                                                value="{{ old('document_file_3') }}" id="document_file_3">
                                        </button>
                                    </div>
                                @endif
                                @if (in_array('document_file_4', $active_fields))
                                    <div class="position-relative input-control file_uploader">
                                        <label for="#"
                                            class="input-control-label">{{ field_label($fields, 'document_file_4') }}</label>
                                        <input class="input-control-input" id="placeholderFileFourName"
                                            type="text"
                                            placeholder="{{ field_label($fields, 'document_file_4') }}" readonly>
                                        <button class="" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="document_file_4">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_4"
                                                value="{{ old('document_file_4') }}" id="document_file_4">
                                        </button>
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
                                @php
                                    $tooltip = '';
                                    if ($reg_setting->registration_permission == 1) {
                                        $tooltip = '';
                                    } else {
                                        $tooltip = "You Can't Registration Now";
                                    }
                                @endphp
                                <div class="input-control">
                                    <button type="submit" class='input-control-input'
                                        id="onlineRegistrationSubmitButton" data-toggle="tooltip"
                                        title="{{ @$tooltip }}">@lang('edulia.register_now')</button>
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
                            </div>
                            {{-- Other Details Ends --}}
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- registration area end -->

    <!-- jQuery JS -->
    <script src="{{ asset('public/theme/edulia/js/jquery.min.js') }}"></script>

    <!-- Nice Select JS -->
    <script src="{{ asset('public/theme/edulia/packages/nice-select/jquery.nice-select.min.js') }}"></script>

    <!-- DatePicker JS -->
    <script src="{{ asset('public/theme/edulia/packages/datepicker/gijgo.min.js') }}"></script>

    <!-- Main Script JS -->
    <script src="{{ asset('/public/backEnd/js/custom.js') }}"></script>

    <script>
        $('select').niceSelect();
        $('#datepicker3').datepicker({
            uiLibrary: 'bootstrap5',
            format: 'dd-mm-yyyy'
        });
        $('#datepicker4').datepicker({
            uiLibrary: 'bootstrap5',
            format: 'dd-mm-yyyy'
        });

        let fileInput = document.getElementById('browseFile');
        if (fileInput) {
            fileInput.addEventListener('change', showFileName);

            function showFileName(event) {
                let fileInput = event.srcElement;
                let fileName = fileInput.files[0].name;
                document.getElementById('placeholderInput').placeholder = fileName;
            }
        }
        function getAge(dob) {
            return ~~((new Date() - new Date(dob)) / 31556952000);
        }
        $("input.mydob").change(function() {
            $("#age").val(getAge($(this).val()));
        });
    </script>
</body>

</html>
