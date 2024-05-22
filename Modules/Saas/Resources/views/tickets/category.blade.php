@extends('backEnd.master')
@section('title')
@lang('saas::saas.ticket_category')
@endsection 
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saas::saas.ticket_category_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('saas::saas.ticket_system')</a>
                <a href="#">@lang('saas::saas.ticket_category')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
       @if(isset($editData))
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('ticket.category')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($editData))
                                    @lang('saas::saas.edit_ticket_category')
                                @else
                                    @lang('saas::saas.add_ticket_category')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($editData))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['ticket.category_update',$editData->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @else
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['ticket.category_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12 mb-20">
                                        <div class="primary_input">
                                            <label>@lang('student.category_name') <span>*</span> </label>
                                            <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            type="text" name="name" autocomplete="off" value="{{isset($editData)? $editData->name : '' }}">
                                            
                                           
                                            @if ($errors->has('name'))
                                            <span class="text-danger" role="alert">
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @if(isset($editData))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('saas::saas.ticket_category_list')</h3>
                        </div>
                    </div>
                </div>
            <div class="row">
                <div class="col-lg-12">
                    <x-table>
                        <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th> @lang('student.category_title')</th>
                                <th> @lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($itemCategories))
                                @foreach($itemCategories as $value)
                                    <tr>
                                        <td>{{$value->name}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <x-drop-down>
                                                            <a class="dropdown-item" href="{{ route('ticket.category_edit',$value->id)}}"> @lang('common.edit')</a>
                                                            <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="@lang('saas::saas.delete_ticket_category')" href="{{url('ticket-category-delete-view/'.$value->id)}}">
                                                                @lang('common.delete')
                                                            </a>
                                                    </x-drop-down>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    </x-table>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')