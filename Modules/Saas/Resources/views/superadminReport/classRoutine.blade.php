@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('academics.class_routine')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('academics.class_routine')</a>
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/class-routine', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4">
                                    <select class="primary_select form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" id="select-institution">
                                        <option data-display="@lang('common.select_institution') *" value="">@lang('common.select_institution') *</option>
                                        @foreach($institutions as $institution)
                                        <option value="{{ $institution->id }}">{{ $institution->school_name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('institution'))
                                    <span class="text-danger invalid-select" role="alert">
                                        <strong>{{ $errors->first('institution') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_class_div">
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                       
                                    </select>
                                    @if ($errors->has('class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        <strong>{{ $errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_section_div">
                                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                    </select>
                                    @if ($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        <strong>{{ $errors->first('section') }}</strong>
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
    </div>
</section>

@if(isset($sm_weekends))
<section class="mt-20">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-6 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('academics.class_routine')</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <x-table>
                    <table id="default_table" class="table school-table-data" cellspacing="0" width="100%">
                        <tr>
                            @php
                                $height = 0;
                                $tr = [];
                            @endphp
                           
                            @foreach ($sm_weekends as $sm_weekend)
                           
                                @php
                                    $studentClassRoutine =  App\SmWeekend::saasClassRoutine($class_id, $section_id, $sm_weekend->id); 
                                @endphp
                                @if ($studentClassRoutine->count() > $height)
                                    @php
                                        $height = $studentClassRoutine->count();
                                    @endphp
                                @endif

                                <th>{{ @$sm_weekend->name }}</th>
                            @endforeach

                        </tr>

                        @php
                            $used = [];
                            $tr = [];
                            
                        @endphp
                        @foreach ($sm_weekends as $sm_weekend)
                            @php
                                
                                $i = 0;
                                $studentClassRoutine = $studentClassRoutine = App\SmWeekend::saasClassRoutine($class_id, $section_id, $sm_weekend->id);
                            @endphp
                            @foreach ($studentClassRoutine as $routine)
                                @php
                                    if (!in_array($routine->id, $used)) {
                                        $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->saasSubject ? $routine->saasSubject->subject_name : '';
                                        $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->saasSubject ? $routine->saasSubject->subject_code : '';
                                        $tr[$i][$sm_weekend->name][$loop->index]['class_room'] = $routine->classRoom ? $routine->classRoom->room_no : '';
                                        $tr[$i][$sm_weekend->name][$loop->index]['teacher'] = $routine->teacherDetail ? $routine->teacherDetail->full_name : '';
                                        $tr[$i][$sm_weekend->name][$loop->index]['start_time'] = $routine->start_time;
                                        $tr[$i][$sm_weekend->name][$loop->index]['end_time'] = $routine->end_time;
                                        $tr[$i][$sm_weekend->name][$loop->index]['is_break'] = $routine->is_break;
                                        $used[] = $routine->id;
                                    }
                                    
                                @endphp
                            @endforeach

                            @php
                                
                                $i++;
                            @endphp
                        @endforeach

                        @for ($i = 0; $i < $height; $i++)
                            <tr>
                                @foreach ($tr as $days)
                                    @foreach ($sm_weekends as $sm_weekend)
                                        <td>
                                            @php
                                                $classes = gv($days, $sm_weekend->name);
                                            @endphp
                                            @if ($classes && gv($classes, $i))
                                                @if ($classes[$i]['is_break'])
                                                    <strong> @lang('academics.break') </strong>

                                                    <span class="">
                                                        ({{ date('h:i A', strtotime(@$classes[$i]['start_time'])) }}
                                                        -
                                                        {{ date('h:i A', strtotime(@$classes[$i]['end_time'])) }})
                                                        <br> </span>
                                                @else
                                                    <span class="">
                                                        <strong>@lang('common.time')
                                                            :</strong>
                                                        {{ date('h:i A', strtotime(@$classes[$i]['start_time'])) }}
                                                        -
                                                        {{ date('h:i A', strtotime(@$classes[$i]['end_time'])) }}
                                                        <br> </span>
                                                    <span class=""> <strong>
                                                            {{ $classes[$i]['subject'] }}
                                                        </strong>
                                                        ({{ $classes[$i]['subject_code'] }})
                                                        <br> </span>
                                                    @if ($classes[$i]['class_room'])
                                                        <span class="">
                                                            <strong>@lang('academics.room')
                                                                :</strong>
                                                            {{ $classes[$i]['class_room'] }}
                                                            <br> </span>
                                                    @endif
                                                    @if ($classes[$i]['teacher'])
                                                        <span class="">
                                                            {{ $classes[$i]['teacher'] }}
                                                            <br> </span>
                                                    @endif
                                                @endif
                                            @endif

                                        </td>
                                    @endforeach
                                @endforeach
                            </tr>
                    @endfor
                </table>
                </x-table>

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