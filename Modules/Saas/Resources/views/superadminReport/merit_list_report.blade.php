@extends('backEnd.master')
@section('title')
@lang('reports.merit_list_report')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('reports.merit_list_report') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('reports.reports')</a>
                    <a href="#">@lang('reports.merit_list_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('message-success') != "")
                    @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                    @endif
                @endif
                @if(session()->has('message-danger') != "")
                    @if(session()->has('message-danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('message-danger') }}
                        </div>
                    @endif
                @endif
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/merit-list-report', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'administrator-result']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="col-lg-3 mt-30-md">
                            <select class="primary_select form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}"
                                    name="institution" id="select-institution-result">
                                <option data-display="@lang('common.select_institution') *"
                                        value="">@lang('common.select_institution') *
                                </option>
                                @foreach($schools as $school)
                                    <option value="{{$school->id}}">{{$school->school_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('institution'))
                                <span class="text-danger" role="alert">
                                    {{ $errors->first('institution') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-30-md" id="select_exam_div">
                            <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                    name="exam" id="select_exam">
                                <option data-display="@lang('exam.select_exam')*" value="">@lang('exam.select_exam')*
                                </option>

                            </select>
                            @if ($errors->has('exam'))
                                <span class="text-danger" role="alert">
                                    {{ $errors->first('exam') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-30-md" id="select_class_div">
                            <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                    id="select_class" name="class">
                                <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')
                                    *
                                </option>

                            </select>
                            @if ($errors->has('class'))
                                <span class="text-danger" role="alert">
                                    {{ $errors->first('class') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-30-md" id="select_section_div">
                            <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                                    id="select_section" name="section">
                                <option data-display="@lang('common.select_section')*"
                                        value="">@lang('common.select_section') *
                                </option>
                            </select>
                            @if ($errors->has('section'))
                                <span class="text-danger" role="alert">
                                    {{ $errors->first('section') }}
                                </span>
                            @endif
                        </div>

                        <div class="col-lg-12 mt-20 text-right">
                            <button type="submit" class="primary-btn small fix-gr-bg">
                                <span class="ti-search pr-2"></span>
                                @lang('common.search')
                            </button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>

    @if(isset($allresult_data))
        <section class="student-details">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30 mt-30">@lang('reports.merit_list_report')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">


                    <div class="col-lg-12">
                        <div class="white-box">

                            <div class="print_button pull-right">
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/merit-list/print', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student', 'target' => '_blank']) }}
                                <input type="hidden" name="InputClassId" value="{{$InputClassId}}">
                                <input type="hidden" name="InputExamId" value="{{$InputExamId}}">
                                <input type="hidden" name="InputSectionId" value="{{$InputSectionId}}">
                                <input type="hidden" name="InputInstitutionId" value="{{$InputInstitutionId}}">
                                <button type="submit" class="primary-btn small fix-gr-bg"><i class="ti-printer"> </i>
                                    Print
                                </button>
                                </form>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-11">
                                    <div class="single-report-admit">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex">
                                                    <div>
                                                        <img class="logo-img" src="{{ generalSetting()->logo }}" alt="">
                                                    </div>
                                                    <div class="ml-30">
                                                        <h3 class="text-white"> {{isset($school_name)?$school_name:'Infix School Management ERP'}} </h3>
                                                        <p class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h3>@lang('reports.order_of_merit_list')</h3>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <p class="mb-0">
                                                                        @lang('common.academic_year') : <span
                                                                                class="primary-color fw-500">{{generalSetting()->session_year}}</span>
                                                                    </p>
                                                                    <p class="mb-0">
                                                                        @lang('exam.exam') : <span
                                                                                class="primary-color fw-500">{{$exam_name}}</span>
                                                                    </p>
                                                                    <p class="mb-0">
                                                                        @lang('common.class') : <span
                                                                                class="primary-color fw-500">{{$class_name}}</span>
                                                                    </p>
                                                                    <p class="mb-0">
                                                                        @lang('common.section') : <span
                                                                                class="primary-color fw-500">{{$section->section_name}}</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h3>@lang('common.subjects')</h3>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    @foreach($assign_subjects as $subject)
                                                                        <p class="mb-0">
                                                                            <span class="primary-color fw-500">{{$subject->subject->subject_name}}</span>
                                                                        </p>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <table class="w-100 mt-30 mb-20">
                                                    <thead>
                                                    <tr>
                                                        <th>Merit @lang('reports.position')</th>
                                                        <th>@lang('student.admission_no')</th>
                                                        <th>@lang('common.student')</th>
                                                        @foreach($subjectlist as $subject)
                                                            <th>{{$subject}}</th>
                                                        @endforeach

                                                        <th>@lang('exam.total_mark')</th>
                                                        <th>@lang('reports.average')</th>
                                                        <th>@lang('exam.gpa')</th>
                                                        <th>@lang('reports.result')</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @php $i=1; $subject_mark = []; $total_student_mark = 0; @endphp
                                                    @foreach($allresult_data as $row)
                                                        <tr>
                                                            <td>{{$row->merit_order}}</td>
                                                            <td>{{$row->admission_no}}</td>
                                                            <td>{{$row->student_name}}</td>

                                                            @php $markslist = explode(',',$row->marks_string);@endphp
                                                            @if(!empty($markslist))
                                                                @foreach($markslist as $mark)
                                                                    @php
                                                                        $subject_mark[]= $mark;
                                                                        $total_student_mark = $total_student_mark + $mark;
                                                                    @endphp
                                                                    <td>  {{!empty($mark)?$mark:0}}</td>
                                                                @endforeach

                                                            @endif


                                                            <td>{{$total_student_mark}} </td>
                                                            <td>{{!empty($row->average_mark)?$row->average_mark:0}} @php $total_student_mark=0; @endphp </td>
                                                            <td>
                                                                <?php
                                                                $total_grade_point = 0;
                                                                $number_of_subject = count($subject_mark);
                                                                foreach ($subject_mark as $mark) {
                                                                    $grade_gpa = markGpa($mark);
                                                                    $total_grade_point = $total_grade_point + $grade_gpa->gpa;
                                                                }
                                                                if ($total_grade_point == 0) {
                                                                    echo '0.00';
                                                                } else {
                                                                    if ($number_of_subject == 0) {
                                                                        echo '0.00';
                                                                    } else {
                                                                        echo number_format((float)$total_grade_point / $number_of_subject, 2, '.', '');
                                                                    }
                                                                }

                                                                ?>


                                                            </td>
                                                            <td>
                                                                <button class="primary-btn small @if($row->result=="F") bg-danger @else bg-success @endif text-white border-0">{{$row->result}}</button>
                                                            </td>
                                                        </tr>

                                                    @endforeach
                                                    </tbody>
                                                </table>
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

    @endif


@endsection
@push('script')
<script>
    $(document).on('change','#select_section',function(){
        let section = $(this).val();
        $("#select_section").val(section);
    })
</script>
<script src="{{asset('public/backEnd/saas/js/')}}/custom.js"></script>
@endpush