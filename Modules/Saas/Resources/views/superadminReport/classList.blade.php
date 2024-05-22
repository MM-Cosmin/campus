@extends('backEnd.master')
@section('title')
@lang('common.class_list')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.class_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('common.class_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
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
              @if(session()->has('message-success'))
                  <div class="alert alert-success">
                  {{ session()->get('message-success') }}
                  </div>
                  @elseif(session()->has('message-danger'))
                  <div class="alert alert-danger">
                      {{ session()->get('message-danger') }}
                  </div>
              @endif
              </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'administrator/class-list', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">

                            <div class="col-lg-12">
                                    <select class="primary_select form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" id="institution">
                                        <option data-display="@lang('common.select_institution')" value="">@lang('common.select_institution') *</option>
                                        @foreach($institutions as $institution)
                                        <option value="{{ $institution->id }}">{{ $institution->school_name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('institution'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('institution') }}</strong>
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

    @if(@$classes)
 <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('common.class_list')</h3>
                    </div>
                </div>
            </div>

         <div class="row">
                <div class="col-lg-12">
                    <x-table>
                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-left">@lang('common.sl')</th>
                                    <th class="text-left">@lang('common.class')</th>
                                    <th class="text-left">@lang('common.section')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $count =1; @endphp 
                                @foreach($classes as $value)
                                <tr>
                                    <td class="text-left pl-10"><span  style="padding-left: 20px">{{@$count++}} </span> </td>
                                    <td class="text-left pl-10"><span  style="padding-left: 20px">{{@$value->class_name}} </span> </td>
                                    <td>
    
                                            <table>
                                                
                                                @foreach($value->classSection as $section)
                                                <tr>
                                                    <td>
                                                        {{@$section->globalSectionName->section_name}}
                                                    </td>
                                                </tr>
                                            @endforeach
    
                                            </table>
                                    </td>
    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</section>
@endsection
@include('backEnd.partials.data_table_js')