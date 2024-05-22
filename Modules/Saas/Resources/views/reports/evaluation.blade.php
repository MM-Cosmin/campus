@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('homework.evaluation_report')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('homework.home_work')</a>
                    <a href="#">@lang('homework.evaluation_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'search-evaluation', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-4">
                                <div class="primary_input">
                                    <select class="primary_select form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                                            name="class_id" id="classSelectStudent">
                                        <option data-display="@lang('common.select_class') *"
                                                value="">@lang('common.select')</option>
                                        @foreach($classes as $key=>$value)
                                            <option value="{{$value->id}}">{{$value->class_name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('class_id'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                <strong>{{ $errors->first('class_id') }}</strong>
                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="primary_input" id="sectionStudentDiv">
                                    <select class="primary_select form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }}"
                                            name="section_id" id="sectionSelectStudent">
                                        <option data-display="@lang('common.select_section') *"
                                                value="">@lang('common.select') *
                                        </option>
                                    </select>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('section_id'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                        <strong>{{ $errors->first('section_id') }}</strong>
                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="primary_input" id="subjectSelecttDiv">
                                    <select class="primary_select form-control{{ $errors->has('subject_id') ? ' is-invalid' : '' }}"
                                            name="subject_id" id="subjectSelect">
                                        <option data-display="@lang('common.select_subjects') *"
                                                value="">@lang('common.subject') *
                                        </option>
                                    </select>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('subject_id'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                <strong>{{ $errors->first('subject_id') }}</strong>
            </span>
                                    @endif
                                </div>
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
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('homework.evaluation_report')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                <tr>

                                    <th>@lang('common.subject')</th>
                                    <th>@lang('homework.home_work_date')</th>
                                    <th>@lang('homework.submission_date')</th>
                                    <th>@lang('homework.complete')/@lang('homework.incomplete')</th>
                                    <th>@lang('homework.complete')(%)</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($homeworkLists as $value)
                                    <tr>
                                        <td>{{$value->subjects!=""?$value->subjects->subject_name:""}}</td>
                                        <td data-sort="{{strtotime($value->homework_date)}}">{{ !empty($value->homework_date)? dateConvert($value->homework_date):''}} </td>

                                        <td data-sort="{{strtotime($value->submission_date)}}">{{ !empty($value->submission_date)? dateConvert($value->submission_date):''}} </td>

                                        @php
                                            $homeworkPercentage = App\SmHomework::getHomeworkPercentage($value->class_id, $value->section_id, $value->id)
                                        @endphp

                                        <td>
                                            <?php
                                            if (isset($homeworkPercentage)) {
                                                $incomplete = $homeworkPercentage['totalStudents'] - $homeworkPercentage['totalHomeworkCompleted'];
                                                echo $homeworkPercentage['totalHomeworkCompleted'] . '/' . $incomplete;
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            if (isset($homeworkPercentage)) {
                                                $x = $homeworkPercentage['totalHomeworkCompleted'] * 100;
                                                if (empty($homeworkPercentage['totalStudents']) || $homeworkPercentage['totalStudents'] < 1) {
                                                    $y = 0;
                                                } else {
                                                    $y = $x / $homeworkPercentage['totalStudents'];
                                                }
                                                echo $y;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    @lang('common.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if(userPermission(185))
                                                        <a class="dropdown-item modalLink"
                                                           title="View Evaluation Report"
                                                           data-modal-size="full-width-modal"
                                                           href="{{route('view-evaluation-report',$value->id)}}">@lang('common.view')</a>
                                                    @endif
                                                </div>
                                            </div>
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
    </section>
@endsection
@include('backEnd.partials.data_table_js')