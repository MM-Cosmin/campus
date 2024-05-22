@extends('backEnd.master')
@section('title') 
@lang('appslider::appSlider.sliders')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('appslider::appSlider.sliders') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('appslider::appSlider.sliders') </a>
                <a href="#">@lang('appslider::appSlider.slider') </a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if(isset($editData))
                                        @lang('appslider::appSlider.edit_slider')
                                    @else
                                    @lang('appslider::appSlider.add_slider')
                                    @endif
                                   
                                </h3>
                            </div>
                            @if(isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'appslider.update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{@$editData->id}}">
                                @else
                             @if(userPermission('appslider.store'))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'appslider.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input type="hidden" name="saas_admin" value="{{$saas}}">
                                                <label> @lang('common.title') <span class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field "
                                                    type="text" name="title" autocomplete="off"
                                                    value="{{isset($editData) ?@$editData->title : old('title')}}">
                                               
                                                
                                                @if ($errors->has('title'))
                                                    <span class="text-danger">
                                                {{ $errors->first('title') }}
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label> @lang('appslider::appSlider.url') <span class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field"
                                                    type="text" name="url" autocomplete="off"
                                                    value="{{isset($editData)? @$editData->url: old('url')}}">
                                             
                                                
                                                @if ($errors->has('url'))
                                                    <span class="text-danger" >
                                                {{ $errors->first('url') }}
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                               
                                    <div class="row mt-15">
                                      
                                        <div class="col-lg-12 mt-15">
                                            <div class="primary_input">
                                                <div class="primary_file_uploader">
                                                    <input class="primary_input_field" id="placeholderInput" type="text"
                                                placeholder="{{ isset($editData) ? ($editData->slider_image != '' ? getFilePath3($postal_receive->slider_image) : trans('common.file')) : trans('common.file') }}"
                                                readonly>
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="browseFile">{{ __('common.browse') }}</label>
                                                        <input type="file" class="d-none" name="slider_image" id="browseFile">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="row  mt-40">
                                        <div class="col-lg-12">
                                            <div class="d-flex radio-btn-flex">
                                                @if(isset($editData))
                                                <div class="mr-30">
                                                    <input type="radio" name="status" id="relationFather" value="1" class="common-radio relationButton" {{ $editData->active_status==1 ? 'checked' :'' }}>
                                                    <label for="relationFather">@lang('appslider::appSlider.active')</label>
                                                </div>
                                                <div class="mr-30">
                                                    <input type="radio" name="status" id="relationMother" value="0" class="common-radio relationButton" {{ $editData->active_status==0 ? 'checked' :'' }} >
                                                    <label for="relationMother">@lang('appslider::appSlider.inactive')</label>
                                                </div>
                                                @else
                                                <div class="mr-30">
                                                    <input type="radio" name="status" id="relationFather" value="1" class="common-radio relationButton" checked>
                                                    <label for="relationFather">@lang('appslider::appSlider.active')</label>
                                                </div>
                                                <div class="mr-30">
                                                    <input type="radio" name="status" id="relationMother" value="0" class="common-radio relationButton" >
                                                    <label for="relationMother">@lang('appslider::appSlider.inactive')</label>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip">
                                                <span class="ti-check"></span>
                                                @lang('common.save')
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
                                <h3 class="mb-0">@lang('appslider::appSlider.sliders_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th> @lang('common.sl')</th>
                                            <th> @lang('common.title')</th>
                                            <th> @lang('common.image')</th>
                                            <th> @lang('appslider::appSlider.link')</th>
                                            <th> @lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($appSliders as $key=>$item) 
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>
                                                    <img src="{{ asset($item->slider_image) }}" alt="" class="img-fluid">
                                                </td>
                                                <td> {{ $item->url }} </td>                                          
                                                <td>
                                                    <x-drop-down>
                                                            @if(userPermission('appslider.saas.edit'))                                        
                                                                @if($item->school_id==null)
                                                                <a class="dropdown-item" href="{{ route('appslider.saas.edit',[$item->id]) }}">@lang('common.edit')</a>
                                                                @else  
                                                                <a class="dropdown-item" href="{{ route('appslider.edit',[$item->id]) }}">@lang('common.edit')</a>
                                                                @endif
                                                            @endif
                                                            @if(userPermission('appslider.delete'))
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteAppSlider{{ @$item->id }}" href="#">
                                                                    @lang('common.delete')
                                                                </a>
                                                            @endif
                                                    </x-drop-down>
                                                </td>
                                            </tr>  
                                            <div class="modal fade admin-query" id="deleteAppSlider{{@$item->id}}" >
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('appslider::appSlider.delete_slider')</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
            
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>
            
                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                                <a href="{{route('appslider.delete', [@$item->id])}}" class="text-light">
                                                                <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                                </a>
                                                            </div>
                                                        </div>
            
                                                    </div>
                                                </div>
                                            </div>                                 
                                        @endforeach
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
