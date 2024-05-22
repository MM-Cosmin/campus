@extends('backEnd.master')
@section('title')
@lang('exam.exam')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="#">@lang('exam.exam')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($exam))
            @if(userPermission(215))
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('exam')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                </div>
            </div>
            @endif
        @endif

    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('exam-update',$exam->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
 

        <div class="row">
           
            <div class="col-lg-12">
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                    @lang('exam.edit_exam')
                                
                           
                            </h3>
                        </div>
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                       
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                        
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                    <input type="hidden" name="id" id="id" value="{{$exam->id}}">
                                    <input type="hidden" name="class_id" id="section_id" value="{{$exam->class_id}}">
                                    <input type="hidden" name="section_id" id="section_id" value="{{$exam->section_id}}">
                                    <input type="hidden" name="subject_id" id="subject_id" value="{{$exam->subject_id}}">
                                    <input type="hidden" name="exam_type_id" id="exam_type_id" value="{{$exam->exam_type_id}}">
                                    
                                    <div class="col-lg-6 mt-30-md">
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class" disabled="">
                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                            @foreach($classes as $class)
                                            <option value="{{$class->id}}"  {{ $class->id == $exam->class_id?'selected':''}}>{{$class->class_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('class') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 mt-30-md">
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="section"  disabled="">
                                            <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                            @foreach($sections as $section)
                                            <option value="{{$section->section_id}}" {{ $section->section_id == $exam->section_id?'selected':''}}>{{$section->sectionName->section_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('class') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-6 mt-30-md">
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="section" disabled="">
                                            <option data-display="@lang('common.select_subjects') *" value="">@lang('common.select_subjects') *</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{$subject->subject_id}}" {{ $subject->subject_id == $exam->subject_id?'selected':''}}>{{$subject->subject->subject_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('class') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 mt-30-md">
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="section" disabled="">
                                            <option data-display="@lang('common.select_exam_type') *" value="">@lang('common.select_exam_type') *</option>
                                            @foreach($exams_types as $exams_type)
                                            <option value="{{$exams_type->id}}" {{ $exams_type->id == $exam->exam_type_id?'selected':''}}>{{$exams_type->title}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('class') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="row mt-25">
                                    <div class="col-lg-6">

                                        <div class="primary_input">
                                            <input oninput="numberMinZeroCheck(this)" class="primary_input_field{{ $errors->has('exam_marks') ? ' is-invalid' : '' }}"
                                            type="text" name="exam_marks" id="exam_mark_main" autocomplete="off" value="{{isset($exam)? $exam->exam_mark: 0}}" required="">
                                            <label>@lang('exam.exam_mark') *</label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('exam_marks'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('exam_marks') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

  
                            </div>
                            <div class="row mt-40">
                                 <div class="col-lg-10">
                                    <div class="main-title">
                                        <h5>@lang('exam.add_mark_distributions') </h5>
                                    </div>
                                </div>
                                <div class="col-lg-2 text-right">
                                    <button type="button" class="primary-btn icon-only fix-gr-bg" onclick="addRowMark();" id="addRowBtn">
                                    <span class="ti-plus pr-2"></span></button>
                                </div>
                            </div>


                            <table class="table" id="productTable">
                                <thead>
                                    <tr>
                                      <th>@lang('exam.exam_title')</th>
                                      <th>@lang('exam.exam_mark')</th>
                                      <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; $totalMark = 0; @endphp
                                @foreach($exam->GetExamSetup as $row)
                                @php $i++; $totalMark += $row->exam_mark; @endphp
                                  <tr id="row1" class="mt-40">
                                    <td class="border-top-0">
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">  
                                        <div class="primary_input">
                                            <input class="primary_input_field{{ $errors->has('exam_title') ? ' is-invalid' : '' }}"
                                                type="text" id="exam_title" name="exam_title[]" autocomplete="off" value="{{$row->exam_title}}">
                                                <label>@lang('common.title')</label>
                                        </div>
                                    </td>
                                    <td class="border-top-0">
                                        <div class="primary_input">
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field{{ $errors->has('exam_mark') ? ' is-invalid' : '' }} exam_mark"
                                            type="text" id="exam_mark" name="exam_mark[]" autocomplete="off"   value="{{$row->exam_mark}}">
                                        </div>
                                    </td> 
                                    <td  class="border-top">
                                         <button class="primary-btn icon-only fix-gr-bg" type="button" id="{{$i != 1? 'removeMark':''}}">
                                             <span class="ti-trash"></span>
                                        </button>
                                       
                                    </td>
                                    </tr>
                                    @endforeach

                                    <tfoot>
                                        <tr>
                                           <td class="border-top-0">@lang('exam.result')</td>

                                           <td class="border-top-0" id="totalMark">
                                             <input type="text" class="primary_input_field" name="totalMark" readonly="true" value="{{$totalMark}}">
                                           </td>
                                           <td class="border-top-0"></td>
                                       </tr>
                                   </tfoot>
                               </tbody>
                            </table>                              
                            <div class="row mt-40">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg">
                                        <span class="ti-check"></span>
                                        
                                            @lang('exam.update_mark_distribution')
                                        
                                     

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}

</section>
@endsection
