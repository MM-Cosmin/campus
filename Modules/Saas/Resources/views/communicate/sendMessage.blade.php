@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.add_notice')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.add_notice')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            @if(userPermission(288) )
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('communicate.add_notice')</h3>
                    </div>
                </div>
            @endif
            <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                <a href="{{url('administrator/notice-list')}}" class="primary-btn small fix-gr-bg">
                    @lang('communicate.notice_board')
                </a>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'administrator/save-notice-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
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
              <div class="white-box">
                <div class="">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="primary_input mb-30">
                                <input class="primary_input_field{{ $errors->has('notice_title') ? ' is-invalid' : '' }}"
                                type="text" name="notice_title" autocomplete="off" value="{{isset($leave_type)? $leave_type->type:''}}">
                                <label>@lang('common.title') <span>*</span> </label>
                                <span class="focus-border"></span>
                                @if ($errors->has('notice_title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('notice_title') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="primary_input mt-40">
                    
                                <textarea class="primary_input_field article-ckeditor mt-30" cols="0" rows="4" name="notice_message" ></textarea>
                            
                                
                                <label class="textarea-label">@lang('communicate.notice') <span></span> </label>
                            </div>

                            <div class="primary_input mt-40"> 
                                <input type="checkbox" id="is_published" class="common-checkbox" value="1" name="is_published">
                                <label for="is_published">Is Published Web Site</label> 
                            </div>
                        </div>


                        <div class="col-lg-5">
                         <div class="no-gutters input-right-icon mb-30">
                            <div class="col">
                                <div class="primary_input">
                                    <input class="primary_input_field date form-control{{ $errors->has('notice_date') ? ' is-invalid' : '' }}" id="notice_date" type="text" autocomplete="off" 
                                    name="notice_date" value="{{date('m/d/Y')}}">
                                    <label>@lang('communicate.notice_date') <span>*</span> </label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('notice_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('notice_date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <i class="ti-calendar" id="notice_date_icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="no-gutters input-right-icon">
                            <div class="col">
                                <div class="primary_input">
                                    <input class="primary_input_field date form-control{{ $errors->has('publish_on') ? ' is-invalid' : '' }}" id="publish_on" type="text" autocomplete="off" 
                                    name="publish_on" value="{{date('m/d ')}}">
                                    <label>@lang('communicate.publish_on') <span>*</span> </label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('publish_on'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('publish_on') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <i class="ti-calendar" id="publish_on_icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-50">
                            <label>@lang('communicate.message_to')</label><br>
                            @foreach($roles as $role)
                            <div class=""> 
                                <input type="checkbox" id="role_{{$role->id}}" class="common-checkbox" value="{{$role->id}}" name="role[]">
                                <label for="role_{{$role->id}}">{{$role->name}}</label>
                                           
                            </div>  
                                        @endforeach
                                        @if($errors->has('section'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('section') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit">
                                    <span class="ti-check"></span>
                                    @lang('communicate.save_content')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>


</section>
@endsection 

@push('scripts')
<script>
    CKEDITOR.replace( 'notice_message' );
</script>
@endpush

