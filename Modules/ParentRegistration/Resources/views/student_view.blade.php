@extends('backEnd.master')
@section('title')
    @lang('student.student_view')
@endsection
@section('mainContent')
    @php  $setting = App\SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();  if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; }   @endphp
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('student.student_details')</h1>
                <div class="bc-pages">
                    <a href="{{ url('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="{{ url('parentregistration/student-list') }}">@lang('student.new_registration')</a>
                    <a href="#">@lang('student.student_details')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Start Student Meta Information -->
                    <div class="main-title">
                        <h3 class="mb-20">@lang('student.student_details')</h3>
                    </div>
                    <div class="student-meta-box">
                        <div class="student-meta-top"></div>
                        <img class="student-meta-img img-100"
                            src="{{ file_exists(@$student_detail->student_photo) ? asset($student_detail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                            alt="">

                        <div class="white-box radius-t-y-0">
                            <div class="single-meta mt-50">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('student.student_name')
                                    </div>

                                    <div class="value">
                                        {{ $student_detail->first_name . ' ' . $student_detail->last_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('common.class')
                                    </div>
                                    <div class="value">

                                        {{ @$student_detail->class->class_name }}

                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('common.section')
                                    </div>
                                    <div class="value">
                                        {{ @$student_detail->section->section_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('common.gender')
                                    </div>
                                    <div class="value">
                                        {{ $student_detail->gender != '' ? $student_detail->gender->base_setup_name : '' }}
                                    </div>
                                </div>
                            </div>
                            @if (moduleStatusCheck('Saas') == true)
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('common.school')
                                        </div>
                                        <div class="value">
                                            {{ @$student_detail->school->school_name }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('common.academic_year')
                                    </div>
                                    <div class="value">
                                        {{ @$student_detail->academicYear->year }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End Student Meta Information -->

                </div>

                <!-- Start Student Details -->
                <div class="col-lg-9 student-details up_admin_visitor">


                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Start Profile Tab -->
                        <div role="tabpanel" class="tab-pane fade  show active" id="studentProfile">
                            <div class="white-box">
                                <h4 class="stu-sub-head">@lang('student.personal_info')</h4>


                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('common.date_of_birth')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ !empty($student_detail->date_of_birth) ? dateConvert($student_detail->date_of_birth) : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('student.age')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ \Carbon\Carbon::parse($student_detail->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('student.category')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ $student_detail->category != '' ? $student_detail->category->category_name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('student.group')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ $student_detail->group ? $student_detail->group->group : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('student.religion')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ $student_detail->religion != '' ? $student_detail->religion->base_setup_name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('common.phone_number')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ @$student_detail->student_mobile }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('common.email_address')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ @$student_detail->student_email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (moduleStatusCheck('Lead') == true)
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-6">
                                                <div class="">
                                                    @lang('lead::lead.city')
                                                </div>
                                            </div>

                                            <div class="col-lg-7 col-md-7">
                                                <div class="">
                                                    {{ @$student_detail->leadCity->city_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-6">
                                                <div class="">
                                                    @lang('lead::lead.source')
                                                </div>
                                            </div>

                                            <div class="col-lg-7 col-md-7">
                                                <div class="">
                                                    {{ @$student_detail->source->source_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('student.present_address')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ @$student_detail->current_address }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('student.permanent_address')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                {{ @$student_detail->permanent_address }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Start Parent Part -->
                                <h4 class="stu-sub-head mt-40">@lang('student.Parent_Guardian_Details')</h4>
                                <div class="d-flex">
                                    <div class="mr-20 mt-20">
                                        <img class="student-meta-img img-100"
                                            src="{{ file_exists(@$student_detail->fathers_photo) ? asset($student_detail->fathers_photo) : asset('public/uploads/staff/demo/father.png') }}"
                                            alt="">

                                    </div>
                                    <div class="w-100">
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('student.father_name')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->fathers_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('student.occupation')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->fathers_occupation != '' ? @$student_detail->fathers_occupation : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('common.phone_number')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->fathers_mobile != '' ? @$student_detail->fathers_mobile : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="mr-20 mt-20">
                                        <img class="student-meta-img img-100"
                                            src="{{ file_exists(@$student_detail->mothers_photo) ? asset($student_detail->mothers_photo) : asset('public/uploads/staff/demo/mother.jpg') }}"
                                            alt="">
                                    </div>
                                    <div class="w-100">
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('student.mother_name')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->mothers_name != '' ? @$student_detail->mothers_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('student.occupation')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->mothers_occupation != '' ? @$student_detail->mothers_occupation : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('common.phone_number')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->mothers_mobile != '' ? @$student_detail->mothers_mobile : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="mr-20 mt-20">
                                        <img class="student-meta-img img-100"
                                            src="{{ file_exists(@$student_detail->guardians_photo) ? asset($student_detail->guardians_photo) : asset('public/uploads/staff/demo/guardian.jpg') }}"
                                            alt="">

                                    </div>
                                    <div class="w-100">
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('student.guardian_name')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->guardian_name != '' ? @$student_detail->guardian_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('common.email_address')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->guardian_email != '' ? @$student_detail->guardian_email : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('common.phone_number')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->guardian_mobile != '' ? @$student_detail->guardian_mobile : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('student.relation_with_guardian')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->relation != '' ? @$student_detail->relation : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('student.occupation')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->guardians_occupation != '' ? @$student_detail->guardians_occupation : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="">
                                                        @lang('student.guardian_address')
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-7">
                                                    <div class="">
                                                        {{ $student_detail->guardians_address != '' ? @$student_detail->guardians_address : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Parent Part -->

                                <!-- Start Transport Part -->
                                @if (isMenuAllowToShow('transport') || isMenuAllowToShow('dormitory'))
                                    <h4 class="stu-sub-head mt-40">@lang('student.' . (isMenuAllowToShow('transport') ? 'transport' : '') . (isMenuAllowToShow('transport') && isMenuAllowToShow('dormitory') ? '_and_' : '') . (isMenuAllowToShow('dormitory') ? 'dormitory' : '') . '_details')</h4>
                                    @if (isMenuAllowToShow('transport'))

                                        @if (!empty($student_detail->route_list_id))
                                            <div class="single-info">
                                                <div class="row">
                                                    <div class="col-lg-5 col-md-5">
                                                        <div class="">
                                                            @lang('student.route')
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-7 col-md-6">
                                                        <div class="">
                                                            {{ isset($student_detail->route_list_id) ? @$student_detail->route->title : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($student_detail->vechile_id))
                                            @if (!empty($vehicle_no))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5">
                                                            <div class="">
                                                                @lang('student.vehicle_number')
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-7 col-md-6">
                                                            <div class="">
                                                                {{ $student_detail->vehicle != '' ? @$student_detail->vehicle->vehicle_no : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif


                                        @if (isset($driver_info))
                                            @if (!empty($driver_info->full_name))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5">
                                                            <div class="">
                                                                @lang('student.driver_name')
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-7 col-md-6">
                                                            <div class="">
                                                                {{ $student_detail->vechile_id != '' ? @$driver_info->full_name : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        @if (isset($driver_info))
                                            @if (!empty($driver_info->mobile))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5">
                                                            <div class="">
                                                                @lang('student.driver_phone_number')
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-7 col-md-6">
                                                            <div class="">
                                                                {{ $student_detail->vechile_id != '' ? @$driver_info->mobile : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif

                                    @if (isMenuAllowToShow('dormitory'))
                                        @if (isset($student_detail->dormitory))
                                            @if (!empty($student_detail->dormitory->dormitory_name))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5">
                                                            <div class="">
                                                                @lang('student.dormitory_name')
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-7 col-md-6">
                                                            <div class="">
                                                                {{ isset($student_detail->dormitory_id) ? @$student_detail->dormitory->dormitory_name : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    <!-- End Transport Part -->
                                @endif

                                <!-- Start Other Information Part -->
                                <h4 class="stu-sub-head mt-40">@lang('student.other_information')</h4>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.blood_group')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->bloodgroup_id) ? @$student_detail->bloodGroup->base_setup_name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.student_group')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->student_group_id) ? @$student_detail->group->group : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.height')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->height) ? @$student_detail->height : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.weight')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->weight) ? @$student_detail->weight : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.previous_school_details')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->previous_school_details) ? @$student_detail->previous_school_details : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.national_id_number')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->national_id_no) ? @$student_detail->national_id_no : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.local_id_number')
                                            </div>
                                        </div>


                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->local_id_no) ? @$student_detail->local_id_no : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.bank_account_number')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->bank_account_no) ? @$student_detail->bank_account_no : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.bank_name')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->bank_name) ? @$student_detail->bank_name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('student.ifsc_code')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                {{ isset($student_detail->ifsc_code) ? @$student_detail->ifsc_code : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Other Information Part -->
                                <!-- Start Documentation Part -->
                                <h4 class="stu-sub-head mt-40">@lang('student.document')</h4>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">

                                                {{ $student_detail->document_title_1 != '' ? $student_detail->document_title_1 : __('student.document_file_1') }}
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">

                                                @if ($student_detail->document_file_1)
                                                    <a href="{{ asset($student_detail->document_file_1) }}"
                                                        download="">
                                                        <i class="fa fa-download mr-1"></i> Download</a>
                                                @else
                                                    No File
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">

                                                {{ $student_detail->document_title_2 != '' ? $student_detail->document_title_2 : __('student.document_file_2') }}
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">

                                                @if ($student_detail->document_file_2)
                                                    <a href="{{ asset($student_detail->document_file_2) }}"
                                                        download="">
                                                        <i class="fa fa-download mr-1"></i> Download</a>
                                                @else
                                                    No File
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">

                                                {{ $student_detail->document_title_3 != '' ? $student_detail->document_title_3 : __('student.document_file_3') }}
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">

                                                @if ($student_detail->document_file_3)
                                                    <a href="{{ asset($student_detail->document_file_3) }}"
                                                        download="">
                                                        <i class="fa fa-download mr-1"></i> Download</a>
                                                @else
                                                    No File
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                {{ $student_detail->document_title_4 != '' ? $student_detail->document_title_4 : __('student.document_file_4') }}
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">

                                                @if ($student_detail->document_file_4)
                                                    <a href="{{ asset($student_detail->document_file_4) }}"
                                                        download="">
                                                        <i class="fa fa-download mr-1"></i> Download</a>
                                                @else
                                                    No File
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- end documentation--added by abuNayem --}}
                                {{-- Custom field start --}}
                                @if (isMenuAllowToShow('custom_field'))
                                    @include('backEnd.customField._coutom_field_show')
                                @endif
                                {{-- Custom field end --}}

                            </div>
                        </div>
                        <!-- End Profile Tab -->
                        <!-- delete document modal -->

                        <!-- delete document modal -->
                        <div class="row mt-40">
                            <div class="col-lg-12 pull-right">


                                {{-- <a class="primary-btn fix-gr-bg"
                                   href="{{url('parentregistration/student-approve', [$student_detail->id])}}"
                                   target="_blank" data-id="{{$student_detail->id}}">@lang('common.approve')</a> --}}
                                <a class="primary-btn fix-gr-bg" href="#" data-toggle="modal"
                                    data-target="#aprroveStudentModal"
                                    data-id="{{ $student_detail->id }}">@lang('common.approve')</a>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- End Student Details -->
            </div>


            <div class="modal fade admin-query" id="aprroveStudentModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">@lang('parentregistration::parentRegistration.student_approve')</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">

                            <div class="text-center">
                                <h4>@lang('common.are_you_sure_to_approve')</h4>
                            </div>

                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg"
                                    data-dismiss="modal">@lang('common.cancel')</button>
                                {{ Form::open(['url' => 'parentregistration/student-approve', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{ @$student_detail->id }}"
                                    id="student_delete_i">
                                <button class="primary-btn fix-gr-bg" type="submit">@lang('common.approve')</button>
                                {{ Form::close() }}
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
