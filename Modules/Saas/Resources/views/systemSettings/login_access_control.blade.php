@extends('backEnd.master')
@section('title')
@lang('system_settings.login_permission')
@endsection
@section('mainContent')
<style type="text/css">
    #selectStaffsDiv, .forStudentWrapper{
        display: none;
    }
    .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 1px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background: linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
}

input:focus + .slider {
  box-shadow: 0 0 1px linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.login_permission')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.login_permission')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'login-access-control', 'enctype' => 'multipart/form-data', 'method' => 'POST']) }}
                        
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-6 mb-30">
                                    @if(session()->has('message-success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message-success') }}
                                    </div>
                                    @elseif(session()->has('message-danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message-danger') }}
                                    </div>
                                    @endif
                
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }} <br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                    <div class="col-lg-12 mb-30">
                                        <select class="primary_select form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" id="member_type">
                                            <option data-display=" @lang('common.select_role') *" value="">@lang('common.select_role') *</option>
                                            @foreach($roles as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                           

                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="forStudentWrapper col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 mb-30">
                                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                                    <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')*</option>
                                                    @foreach($classes as $class)
                                                    <option value="{{$class->id}}"  {{( old("class") == $class->id ? "selected":"")}}>{{$class->class_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6 mb-30" id="select_section_div">
                                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                                    <option data-display="@lang('common.select_section')" value="">@lang('common.select_section') *</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>



                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>

                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>


        @if(isset($students))
            <div class="row mt-40">
                

                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('common.student_list') ({{$students->count()}})</h3>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    @if(session()->has('message-success') != "" ||
                                    session()->get('message-danger') != "")
                                    <tr>
                                        <td colspan="10">
                                            @if(session()->has('message-success'))
                                            <div class="alert alert-success">
                                                {{ session()->get('message-success') }}
                                            </div>
                                            @elseif(session()->has('message-danger'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('message-danger') }}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('student.admission_no')</th>
                                        <th>@lang('student.roll_no')</th>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('common.class')</th>
                                        <th>@lang('student.father_name')</th>
                                        <th>@lang('common.date_of_birth')</th>
                                        <th>@lang('common.gender')</th>
                                        <th>@lang('common.type')</th>
                                        <th>@lang('common.phone')</th>
                                        <th>@lang('system_settings.login_permission')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($students as $student)
                                    <tr id="{{$student->user_id}}">
                                        <input type="hidden" id="id" value="{{$student->user_id}}">
                                        <input type="hidden" id="role" value="{{$role}}">
                                        <td>{{$student->admission_no}}</td>
                                        <td>{{$student->roll_no}}</td>
                                        <td>{{$student->first_name.' '.$student->last_name}}</td> 
                                        <td>{{!empty($student->class)?$student->class->class_name:''}}</td>

                                        <td>{{!empty($student->parents->fathers_name)?$student->parents->fathers_name:''}}</td>
                                        <td>{{date('jS M, Y', strtotime($student->date_of_birth))}}</td>
                                        <td>{{$student->gender != ""? $student->gender->base_setup_name :''}}</td>
                                        <td>{{!empty($student->student_category_id)? $student->category->category_name:''}}</td>
                                        <td>{{$student->mobile}}</td>
                                        <td>
                                              <label class="switch_toggle">
                                              <input type="checkbox" class="switch-input" {{$student->user->access_status == 0? '':'checked'}}>
                                              <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($staffs))
             <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('hr.staff_list')</h3>
                    </div>
                </div>
            </div>

         <div class="row">
                <div class="col-lg-12">
                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('hr.staff_no')</th>
                                <th>@lang('common.name')</th>
                                <th>@lang('hr.role')</th>
                                <th>@lang('hr.department')</th>
                                <th>@lang('common.description')</th>
                                <th>@lang('common.mobile')</th>
                                <th>@lang('common.email')</th>
                                <th>@lang('system_settings.login_permission')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($staffs as $value)
                            <tr id="{{$value->user_id}}">
                                <input type="hidden" id="id" value="{{$value->user_id}}">
                                        <input type="hidden" id="role" value="{{$role}}">
                                <td>{{$value->staff_no}}</td>
                                <td>{{$value->first_name}}&nbsp;{{$value->last_name}}</td>
                                <td>{{!empty($value->roles->name)?$value->roles->name:''}}</td>
                                <td>{{$value->departments !=""?$value->departments->name:""}}</td>
                                <td>{{$value->designations !=""?$value->designations->title:""}}</td>
                                <td>{{$value->mobile}}</td>
                                <td>{{$value->email}}</td>
                                <td>
                                              <label class="switch_toggle">
                                              <input type="checkbox" class="switch-input" {{$value->staff_user->access_status == 0? '':'checked'}}>
                                              <span class="slider round"></span>
                                            </label>
                                        </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        @endif

        @if(isset($parents))
            <div class="row mt-40">
                

                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('student.parents_list')</h3>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    @if(session()->has('message-success') != "" ||
                                    session()->get('message-danger') != "")
                                    <tr>
                                        <td colspan="10">
                                            @if(session()->has('message-success'))
                                            <div class="alert alert-success">
                                                {{ session()->get('message-success') }}
                                            </div>
                                            @elseif(session()->has('message-danger'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('message-danger') }}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('student.guardian_phone') </th>
                                        <th>@lang('student.father_name') </th>
                                        <th>@lang('student.father_phone') </th>
                                        <th>@lang('student.mother_name') </th>
                                        <th>@lang('student.mother_phone') </th>
                                        <th>@lang('system_settings.login_permission')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($parents as $parent)
                                    <tr id="{{$parent->user_id}}">
                                        <input type="hidden" id="id" value="{{$parent->user_id}}">
                                        <input type="hidden" id="role" value="{{$role}}">
                                        <td>{{$parent->guardians_mobile}}</td>
                                        <td>{{$parent->fathers_name}}</td>
                                        <td>{{$parent->fathers_mobile}}</td>
                                        <td>{{$parent->mothers_name}}</td>
                                        <td>{{$parent->mothers_mobile}}</td>
                                        <td>
                                              <label class="switch_toggle">
                                              <input type="checkbox" class="switch-input" {{$parent->parent_user->access_status == 0? '':'checked'}}>
                                              <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </div>
</div>
</div>
</div>
</section>



@endsection
@include('backEnd.partials.data_table_js')