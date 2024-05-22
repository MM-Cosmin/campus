@if(in_array('un_session_id',$active_fields))
<div class="col-lg-6">
    <div class="primary_input">
        <select class="primary_select  form-control {{ $errors->has('un_session_id') ? ' is-invalid' : '' }}" name="un_session_id"
                id="select_session">
            <option data-display="@lang('university::un.select_session') *"
                    value="">@lang('university::un.select_session')</option>
            @foreach($un_session as $session)
                <option value="{{$session->id}}">{{$session->name}}
                </option>
            @endforeach
        </select>
    </div>
    @if($errors->has('un_session_id'))
       <div class="text-danger error-message invalid-select mb-10">{{ $errors->first('un_session_id') }}</div>
   @endif
</div>
@endif

@if(in_array('un_faculty_id',$active_fields))
<div class="col-lg-6">
    <div class="primary_input">
        <select class="primary_select  form-control {{ $errors->has('un_faculty_id') ? ' is-invalid' : '' }}" name="un_faculty_id"
                id="select_faculty">
            <option data-display="@lang('university::un.select_faculty') *"
                    value="">@lang('university::un.select_faculty')</option>
            @foreach($faculties as $faculty)
                <option value="{{$faculty->id}}">{{$faculty->name}}
                </option>
            @endforeach
        </select>
    </div>
    @if($errors->has('un_faculty_id'))
       <div class="text-danger error-message invalid-select mb-10">{{ $errors->first('un_faculty_id') }}</div>
   @endif
</div>
@endif


@if(in_array('un_department_id',$active_fields))
<div class="col-lg-6">
    <div class="primary_input" id="select_dept_div">
      <select class="primary_select  {{ $errors->has('un_department_id') ? ' is-invalid' : '' }}" id="select_dept" name="un_department_id">
            <option data-display="@lang('university::un.select_department') *" value="">@lang('university::un.select_department')</option>
        </select>
        
            <div class="pull-right loader loader_style" id="select_dept_loader">
                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
            </div>
            @if($errors->has('un_department_id'))
            <div class="text-danger error-message invalid-select mb-10">{{ $errors->first('un_department_id') }}</div>
        @endif
    </div>
</div>
@endif

@if(in_array('un_academic_id',$active_fields))
<div class="col-lg-6">
    <div class="primary_input" id="select_academic_div">
      <select class="primary_select  {{ $errors->has('un_academic_id') ? ' is-invalid' : '' }}" id="select_academic" name="un_academic_id">
            <option data-display="@lang('university::un.select_academic_year') *" value="">@lang('university::un.select_academic_year')</option>
            @foreach($unAcademics as $unAcademy)
                  <option value="{{$unAcademy->id}}">{{$unAcademy->name}} </option>
            @endforeach
        </select>
        
            <div class="pull-right loader loader_style" id="select_academic_loader">
                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
            </div>
            @if($errors->has('un_academic_id'))
            <div class="text-danger error-message invalid-select mb-10">{{ $errors->first('un_academic_id') }}</div>
        @endif
    </div>
</div>
@endif

@if(in_array('un_semester_id',$active_fields))
<div class="col-lg-6">
    <div class="primary_input" id="select_semester_div">
      <select class="primary_select  {{ $errors->has('un_semester_id') ? ' is-invalid' : '' }}" id="select_semester" name="un_semester_id">
            <option data-display="@lang('university::un.select_semester') *" value="">@lang('university::un.select_semester')</option>
            @foreach($semesters as $semester)
                  <option value="{{$semester->id}}">{{$semester->name}} </option>
            @endforeach
        </select>
        
            <div class="pull-right loader loader_style" id="select_academic_loader">
                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
            </div>
            @if($errors->has('un_semester_id'))
            <div class="text-danger error-message invalid-select mb-10">{{ $errors->first('un_semester_id') }}</div>
        @endif
    </div>
</div>
@endif


@if(in_array('un_semester_label_id',$active_fields))
<div class="col-lg-6">
    <div class="primary_input" id="select_semester_label_div">
      <select class="primary_select  {{ $errors->has('un_semester_label_id') ? ' is-invalid' : '' }}" id="select_semester_label" name="un_semester_label_id">
            <option data-display="@lang('university::un.select_semester_level') *" value="">@lang('university::un.select_semester_level')</option>
        </select>
        
            <div class="pull-right loader loader_style" id="select_semester_label_loader">
                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
            </div>
            @if($errors->has('un_semester_label_id'))
            <div class="text-danger error-message invalid-select mb-10">{{ $errors->first('un_semester_label_id') }}</div>
        @endif
    </div>
</div>
@endif

@if(in_array('un_section_id',$active_fields))
<div class="col-lg-6">
    <div class="primary_input" id="select_section_div">
      <select class="primary_select  {{ $errors->has('un_section_id') ? ' is-invalid' : '' }}" id="select_section" name="un_section_id">
            <option data-display="@lang('university::un.select_section') *" value="">@lang('university::un.select_section')</option>
        </select>
        
            <div class="pull-right loader loader_style" id="select_section_loader">
                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
            </div>
            @if($errors->has('un_section_id'))
            <div class="text-danger error-message invalid-select mb-10">{{ $errors->first('un_section_id') }}</div>
        @endif
    </div>
</div>
@endif