@extends('backEnd.master')
@section('title')
    @if (isset($editData))
        @lang('saas::saas.edit_ticket')
    @else
        @lang('saas::saas.add_ticket')
    @endif
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/editor/summernote-bs4.css') }}">
<style>
    .dropdown-toggle::after {
        display: none !important;
    }
</style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>
                    @if (isset($editData))
                        @lang('saas::saas.edit_ticket')
                    @else
                        @lang('saas::saas.add_ticket')
                    @endif
                </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    @if (Auth::user()->role_id == 7)
                        <a href="{{ route('user.ticket') }}">@lang('saas::saas.ticket_system')</a>
                    @endif
                    @if (Auth::user()->role_id != 7)
                        <a href="{{ route('admin.ticket_list') }}">@lang('saas::saas.ticket_system')</a>
                    @endif
                    <a href="#">@lang('saas::saas.ticket_list')</a>
                    <a href="#">
                        @if (isset($editData))
                            @lang('saas::saas.edit_ticket')
                        @else
                            @lang('saas::saas.add_ticket')
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            @if (isset($editData) && Auth::user()->role_id == 7)
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{ route('user.add_ticket') }}" target="_blank" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('common.add')
                        </a>
                    </div>
                </div>
            @endif

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="white-box">
                        @if (Auth::user()->role_id != 7)
                            @if (isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['admin.ticket_update', $editData->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['admin.ticket_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                            @endif
                        @else
                            @if (isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['user.ticket_update', $editData->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['user.ticket_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                            @endif
                        @endif
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="col-lg-8">
                                <div class="primary_input">
                                    <label>@lang('common.subject') <span class="text-danger"> *</span> </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}"
                                        type="text" name="subject" autocomplete="off"
                                        value="{{ isset($editData) ? $editData->subject : '' }}">
                                  
                                    @if ($errors->has('subject'))
                                        <span class="text-danger" role="alert">
                                            {{ $errors->first('subject') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="primary_input">
                                    <label>@lang('saas::saas.ticket_category') <span class="text-danger"> *</span> </label>
                                    <select
                                        class="primary_select form-control{{ $errors->has('category') ? ' is-invalid' : '' }}"
                                        id="select_class" name="category">
                                        <option data-display="@lang('saas::saas.ticket_category')" value="">@lang('saas::saas.ticket_category_select')
                                        </option>
                                        @foreach ($category as $item)
                                            <option value="{{ $item->id }}"
                                                {{ isset($editData->category_id) != null ? ($item->id == @$editData->category_id ? 'selected' : '') : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('category'))
                                    <span class="text-danger" role="alert">
                                        {{ $errors->first('category') }}
                                    </span>
                                @endif
                            </div>
                            @if (SaasDomain() == 'school')
                                <div class="col-lg-4 mt-5">
                                    <div class="primary_inp">
                                        <label>@lang('common.school') <span class="text-danger"> *</span> </label>
                                        <select
                                            class="primary_select form-control{{ $errors->has('school_id') ? ' is-invalid' : '' }}"
                                            id="select_school" name="school_id">
                                            <option data-display="@lang('saas::saas.select_school') " value="">@lang('saas::saas.select_school')
                                            </option>
                                            @foreach ($schools as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ isset($editData->school_id) ? ($item->id == @$editData->school_id ? 'selected' : '') : '' }}>
                                                    {{ $item->school_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('school_id'))
                                        <span class="text-danger" role="alert">
                                            {{ $errors->first('school_id') }}
                                        </span>
                                    @endif
                                </div>
                             @else  
                                <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">   
                            @endif
                            @php
                                $div = SaasDomain() == 'school' ? 'col-lg-4' : 'col-lg-6';
                            @endphp
                            <div class="{{ $div }} mt-5">
                                <div class="primary_input">
                                    <label>@lang('saas::saas.ticket_priority') <span class="text-danger"> *</span> </label>
                                    <select
                                        class="primary_select form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}"
                                        id="select_class" name="priority">
                                        <option data-display="@lang('saas::saas.ticket_priority')" value="">@lang('saas::saas.ticket_priority_select')
                                        </option>
                                        @foreach ($priority as $item)
                                            <option value="{{ $item->id }}"
                                                {{ isset($editData->priority_id) ? ($item->id == @$editData->priority_id ? 'selected' : '') : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('priority'))
                                    <span class="text-danger" role="alert">
                                        {{ $errors->first('priority') }}
                                    </span>
                                @endif
                            </div>
                            <div class="{{ $div }} mt-5">
                                    <label>@lang('common.status')</label>
                                <select
                                    class="primary_select"
                                    name="active_status">
                                    <option data-display="@lang('common.status')" value="">@lang('common.select_status')</option>
                                    <option value="0"
                                        {{ isset($editData) ? ($editData->active_status == 0 ? 'selected' : '') : '' }}>
                                        @lang('common.pending')</option>
                                    <option value="1"
                                        {{ isset($editData) ? ($editData->active_status == 1 ? 'selected' : '') : '' }}>
                                        @lang('saas::saas.ongoing')</option>
                                    <option value="2"
                                        {{ isset($editData) ? ($editData->active_status == 2 ? 'selected' : '') : '' }}>
                                        @lang('saas::saas.complete')</option>
                                    @if (Auth::user()->role_id == 1)
                                        <option value="3"
                                            {{ isset($editData) ? ($editData->active_status == 3 ? 'selected' : '') : '' }}>
                                            @lang('common.close')</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-12 mt-5">
                                <div class="primary_input">
                                    <label>@lang('common.description') <span class="text-danger"> *</span> </label>
                                    <textarea class="primary_input_field form-control" rows="5" name="description" cols="50" id="summernote"> 
                                        @if (isset($editData)){!! $editData->description !!}@endif
                                    </textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-danger" role="alert">
                                            {{ $errors->first('description') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                          @isset($editData)
                           <div class="col-lg-12 mt-5">
                                <div class="primary_input">
                                    @if($editData->attachments)
                                    <ul>
                                        @foreach ($editData->attachments as $item)
                                            <li id="list_{{ $item->id }}">   
                                                {{ $loop->iteration .'. '.getFilePath3($item->file) }}
                                                <a data-modal-size="modal-lg" title="View Details" class="primary-btn small fix-gr-bg icon-only modalLink" href="{{route('admin.ticket-view-attachment', $item->id)}}">
                                                    <i class="ti-eye"></i>
                                                </a>
                                                <span style="cursor: pointer;" data-url="" id="delete_item" 
                                                data-id = "{{ $item->id }}" class="primary-btn small fix-gr-bg icon-only delete_button_modal"><i class="ti-trash"></i></span>
                                            </li>
                                        @endforeach                                     
                                    </ul>
                                @endif
                                </div>
                           </div>
                           @endisset
                            <div class="col-lg-12 mt-5">
                                <div class="primary_input">
                                    @include('saas::user._file_pond')
                                </div>
                            </div>
                            <div class="col-lg-12 mt-10 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @if (isset($editData))
                                        @lang('saas::saas.update_ticket')
                                    @else
                                        @lang('saas::saas.create_ticket')
                                    @endif

                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="modal fade admin-query" id="delete_attachment_modal">
        <div class="modal-dialog small-modal modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('common.delete')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
    
                <div class="modal-body">
                    <div class="container-fluid">
                         
                            <input type="hidden" id="ticket_id" name="id">                  
                            <h4>{{ __('common.are_you_sure_to_detete_this_item') }} ?</h4>
                            <div class="row">
                                <div class="col-lg-12 text-center mt-40">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
    
                                        <button class="primary-btn fix-gr-bg delete_attachment"  type="submit">@lang('common.delete')</button>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                </div>
    
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('public/backEnd/')}}/vendors/editor/summernote-bs4.js"></script>
    <script>
        $('#summernote').summernote({
            placeholder: 'Write here',
            tabsize: 2,
            height: 400
        });
        $('.popover').css("display", "none");
        $(document).on('click', '.delete_button_modal', function() {
            let id = $(this).data('id');
            $('#ticket_id').val(id);
            $('#delete_attachment_modal').modal('toggle');

        })
        $(document).on('click', '.delete_attachment', function(e){
            e.preventDefault();
            let id = $('#ticket_id').val();
            let url = $('#url').val()
            $.ajax({
                type:'POST',
                data:{id:id, type:'ticket'},
                dataType:'json',
                url:url+'/admin/attachment-delete',
                success:function(data){
                    toastr.success(data.message, 'Success');
                    $('#list_'+id).remove();
                    $('#delete_attachment_modal').modal('hide');
                },
                error:function()
                {

                }
            })
        })
      
    </script>
@endpush
