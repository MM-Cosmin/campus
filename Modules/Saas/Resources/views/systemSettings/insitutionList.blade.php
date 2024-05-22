@extends('backEnd.master')
@section('title')
    @lang('saas::saas.institution_list')
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
                <h1>@lang('saas::saas.institution_list')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('saas::saas.institution_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                <div class='row'>
                    <div class="offset-lg-9 col-lg-3 text-right mb-20">
                                    <a href="{{route('administrator/institution-create')}}" class="primary-btn small fix-gr-bg">
                                        <span class="ti-plus pr-2"></span>
                                        @lang('common.add_new')
                                    </a>
                                </div>
                </div>
                 
                            <div class='row'>
                                <div class='col-md-6'>
                                    <div class="main-title">
                                        <h3 class="mb-0">
                                            @lang('saas::saas.institution_list')
                                        </h3>
                                    </div>
                                </div>
                                
                            </div>




                                <div class="row">
                                    <div class="col-lg-12">

                                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                                            <thead>
                                            @if(session()->has('message-success-delete') != "" ||
                                             session()->get('message-danger-delete') != "")
                                                <tr>
                                                    <td colspan="3">
                                                        @if(session()->has('message-success-delete'))
                                                            <div class="alert alert-success">
                                                                {{ session()->get('message-success-delete') }}
                                                            </div>
                                                        @elseif(session()->has('message-danger-delete'))
                                                            <div class="alert alert-danger">
                                                                {{ session()->get('message-danger-delete') }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif


                                            <tr>
                                                <th>@lang('common.name')</th>
                                                <th>@lang('common.email')</th>
                                                <th>@lang('saas::saas.start_date')</th>
                                                <th>@lang('common.details')</th>
                                             
                                                <th>@lang('saas::saas.is_approved')</th>

                                                <th>@lang('saas::saas.login_access')</th>
                                                <th>@lang('common.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data as $row)
                                                <tr id='{{ $row->id }}'>
                                                    <td>{{$row->school_name}}</td>
                                                    <td> {{$row->email}} </td>

                                                    <td data-sort="{{ $row->starting_date }}">
                                        {{  $row->starting_date != ""? dateConvert($row->starting_date):''}}</td>
                                                   

                                                    <td>
                                                        <a href="{{route('administrator/institution-details', $row->id)}}">
                                                            <span class="ti-view-grid icongrediant"></span>
                                                        </a>
                                                    </td>
                                                     <td>
                                                        

                                                            <label class="switch_toggle">
                                                              <input type="checkbox"
                                                                     class="switch-input-institution-approve" {{$row->active_status == 1? 'checked':''}}>
                                                              <span class="slider round"></span>
                                                          </label>


                                                    </td>

                                                    <td>
                                                        

                                                              <label class="switch_toggle">
                                                                <input type="checkbox"
                                                                       class="switch-input-institution-enable" {{$row->is_enabled == 'yes'? 'checked':''}}>
                                                                <span class="slider round"></span>
                                                            </label>


                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                                @lang('common.select')
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="{{route('administrator/institution-edit', [$row->id])}}">@lang('common.edit')</a>
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