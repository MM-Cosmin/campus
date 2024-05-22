@extends('backEnd.master')
@section('title')
    @lang('saas::saas.ticket_view')
@endsection()
@push('css')
    <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/editor/summernote-bs4.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins&display=swap');
        body{
            font-family: 'Poppins', sans-serif;
        }
        img{
        	width: 60px;
        }
        p{
            margin-bottom: 0;
            font-size: 14px;
            line-height: 26px;
        }
        button{
            cursor: pointer;
        }
            .infix_form_area{
               padding-top: 40px;
               padding-bottom: 40px;
               
            }
            .infix_form_area form textarea{
                width:  100%;
                border: 1px solid #8f8f8f;
                height: 200px;
                margin-bottom: 20px;
                padding: 20px;
            }

            .textarea-reply{
                width:  100%;
                border: 1px solid #8f8f8f;
                height: 100px !important;
                margin-bottom: 20px;
                padding: 20px;
            }
            .infix_form_area form textarea::placeholder{
                font-size: 14px;
                font-weight: 400;
                color: #8f8f8f;
            }
            .infix_form_area form button{
                text-align: right;
            }
            .file_upload svg{
                font-size: 14px;
                width: 14px;
            }
            .file_upload span{
                text-transform: uppercase;
            }
            .infix_form_area .custom-file-label::after {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                z-index: 3;
                display: block;
                height: calc(1.5em + .75rem);
                padding: .375rem .75rem;
                line-height: 1.5;
                color: #495057;
                content: "Browse";
                background-color: #e9ecef;
                border-left: inherit;
                border-radius: 0 .25rem .25rem 0;
                display: none;
                text-align: left;
            }
            
            .infix_form_area .custom-file-label {
                cursor: pointer;
                position: absolute;
                top: 0;
                right: 0;
                left: 0;
                z-index: 1;
                height: calc(1.5em + .75rem + 2px);
                padding: 7px;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                background-color: transparent;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                border: none;
                text-transform: capitalize;
                font-size: 14px;
                text-transform: capitalize;
                font-weight: 400;
                display: inline-block !important;
                margin-bottom: 0;
                width: 100%;
                text-align: left;
                
            }
            .infix_form_area .custom-file-label:focus{
                outline: none;
            }
            .infix_form_area .input-group-text {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-align: center;
                align-items: center;
                padding: .375rem .75rem;
                margin-bottom: 0;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                text-align: center;
                white-space: nowrap;
                background-color: #e9ecef;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                background: transparent;
                border: none;
            }
            
            .comments_public .comments-thumb{
               margin-bottom: 15px;
            }
            .comments_public .comments-thumb img{
                margin-right: 20px;
            }
            .comments_public .comments-thumb p{
                font-size: 12px;
                font-weight: 300;
            }
            .comments_public > p{
                font-size: 14px;
                font-weight: 300;
                color: #000;
                margin-bottom: 0;
                padding-left: 10px;
            }
            .comments_public p span{
                font-size: 14px;
                text-transform: uppercase;
                font-weight: 400;
                position: relative;
            }
            .comments_public p span a{
                font-size: 12px;
                text-transform: uppercase;
                font-weight: 400;
                color: #000;
                text-decoration: none;

            }
            .comments_public-info{
                text-align: right;
            }
            .comments_public-info p{
                text-align: left;
            }
            .comments_public .single_comment_replay{
                /* padding-right: 30px; */
                margin-bottom: 15px;
                margin-top: 15px;
            }
            .comments_public .single_comment_replay .comments-thumb{
                justify-content: flex-end;
                margin-bottom: 15px;
            }
            .comments_public .single_comment_replay .comments-thumb img{
                margin-right: 0;
                margin-left: 15px;
            }
            .comments_public .single_comment_replay .comments-thumb .comment-meta{
                text-align: right;
                font-size: 12px;
            }
            .common_text_area{
                width:  100%;
                width: 100%;
                border: 1px solid #8f8f8f;
                height: 100px;
                margin-bottom: 20px;
                padding: 10px;
                margin:10px 0;
            }
            #reply_comment p {
                text-align: right;
            }
    </style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('saas::saas.ticket_system')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                @if (Auth::user()->role_id == 7)
                    <a href="{{ route('user.ticket') }}">@lang('saas::saas.ticket_system')</a>
                @endif
                @if (Auth::user()->role_id != 7)
                    <a href="{{ route('admin.ticket_list') }}">@lang('saas::saas.ticket_system')</a>
                @endif
                <a href="#">@lang('saas::saas.ticket_system_view')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-12 mt-20 text-right">
                @if ((Auth::user()->is_administrator == "yes") || (Auth::user()->id == $data->created_by))
                    <span class="pull-right">
                        <a href="{{ route('admin.ticket_edit',$data->id)}}" class="primary-btn small fix-gr-bg">
                            <font style="vertical-align: inherit;">
                                @lang('common.edit')
                            </font>
                        </a>
                    </span>
                @endif
                {{-- <span class="pull-right">
                    <a href="{{ route('user.reopen_ticket',$data->id) }}" class=" {{$data->active_status == 3?'primary-btn small fix-gr-bg':'btn btn-secondary'}}">
                        <font style="vertical-align: inherit;">
                            {{$data->active_status == 3?'Reopen':trans('saas::saas.active')}}
                        </font>
                    </a>
                </span> --}} 
            </div>
        </div>
        <div class="row mt-0 p-3">
            <div class="col-lg-12 white-box">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>{{ $data->subject }}</h2>
                            </div>
                            <div class="col-lg-12">
                                <p>{!! $data->description !!}</p>
                            </div>
                            <div class="col-lg-12">
                                @if($data->attachments)
                                    <ul>
                                        @foreach ($data->attachments as $item)
                                            <li>
                                                <a data-modal-size="modal-lg" title="View Details" class="dropdown-item modalLink" href="{{route('admin.ticket-view-attachment', $item->id)}}">
                                                    {{ $loop->iteration .'. '.getFilePath3($item->file) }}</a>
                                            </li>
                                        @endforeach                                     
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <p> 
                            <strong>@lang('saas::saas.created_by')</strong>: {{ @$data->user->full_name }}</p>
                        <p>
                            <strong>@lang('common.status')</strong>: 
                            @if ($data->active_status == 0)
                                <span style="color:red">@lang('common.pending')</span>
                            @endif
                            @if ($data->active_status == 1)
                                <span style="color:red">@lang('saas::saas.ongoing')</span>
                            @endif
                            @if ($data->active_status == 2)
                                <span style="color:#15a000">@lang('saas::saas.complete')</span>
                            @endif
                            @if ($data->active_status == 3)
                                <span style="color:rgb(148, 118, 118)">@lang('common.close')</span>
                            @endif
                        </p>
                        <p>
                            <strong>@lang('saas::saas.ticket_priority')</strong>:
                            <span style="color: #e1d200">{{ $data->priority->name}}</span>
                        </p>
                        <p>
                            <strong>@lang('saas::saas.responsible')</strong>: {{@$data->agent_user?$data->agent_user->full_name:'Not assign yet !'}}
                        </p>
                        <p>
                            <strong>@lang('student.category')</strong>: 
                            <span style="color: #7e0099">
                                {{ $data->category->name }}
                            </span>
                        </p>
                        <p> 
                            <strong>@lang('common.created_at')</strong>: {{ $data->created_at ->diffForHumans()}}
                        </p>
                        <p> 
                            <strong>@lang('saas::saas.last_update')</</strong>: {{ $data->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div class="row mt-30">
                    <div class="col-lg-12">
                        <h2>@lang('saas::saas.message')</h2>
                        <hr>
                    </div>
                    
                </div>
                    <div class="row">
                        <input type="hidden" name="id" id="url" value="{{ $data->id }}">
                        <div class="col-lg-12">
                            <div class="infix_form_area">
                                <div class="">
                                    <div class="comments_public pt-4">
                                        
                                        @if (count($comment)>0)
                                            @foreach ($comment as $item)
                                                @if (!($item->created_by == Auth::user()->id))
                                                    @php 
                                                        $path = @$item->user->staff->staff_photo;
                                                        if(empty($path))
                                                        {
                                                            $path = 'public/uploads/staff/demo/staff.jpg';
                                                        }
                                                    @endphp
                                                    <div class="single_comment mb-3">
                                                        <div class="comments-thumb d-flex">
                                                            <img style="max-width: 100px; max-height:100px" class="img-fluid" src="{{ file_exists(@$path) ? asset($path) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="">
                                                            <p class="comment-meta">
                                                                <span>
                                                                    <a href="#"> {{ @$item->full_name}}</a>
                                                                </span> 
                                                                <br> 
                                                                <small>{{ @$item->created_at->diffForHumans() }}</small>
                                                            </p>
                                                        </div>
                                                        <div class="comments_public-info d-flex flex-column justify-content-start align-items-start">
                                                            <p class="pl-4 ml-5 mb-4"><span class="text-justify text-capitalize">{!! $item->comment !!}</span>
                                                                @if ($item->attachments)
                                                                @foreach ($item->attachments as $attach)
                                                                    
                                                                
                                                                    @if (file_exists($attach->file))
                                                                        
                                                                        <a data-modal-size="modal-lg" title="View Details" class="dropdown-item modalLink" href="{{route('admin.ticket-view-attachment', $attach->id)}}">{{ $loop->iteration .'. '.getFilePath3($attach->file) }}</a>
                                                                    @endif
                                                                <br> 
                                                                <small>
                                                                    <a target="_blank" href="{{url($attach->file)}}" download> 
                                                                        @lang('saas::saas.download_file')
                                                                    </a>
                                                                </small>
                                                                @endforeach
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                @elseif ($item->created_by == Auth::user()->id)
                                                    @php
                                                        $path = @$item->staff_photo;
                                                        if(empty($path))
                                                        {
                                                            $path = 'public/backEnd/img/client/user.png';
                                                        }
                                                    @endphp
                                                    <div class="single_comment_replay mb-3">
                                                        <div class="comments-thumb d-flex">
                                                            <img style="max-width: 100px; max-height:100px" class="img-fluid" src="{{ file_exists(@$path) ? asset($path) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="">
                                                            <p class="comment-meta ml-20">
                                                                <span>
                                                                    <a href="#">{{ @$item->full_name}}</a>
                                                                </span> 
                                                                <br> 
                                                                <small>{{ @$item->created_at->diffForHumans() }} </small>
                                                            </p>
                                                        </div>
                                                        <div class="comments_public-info" id="reply_comment">
                                                            <p class="pr-4 mr-5 mb-3" style="text-align: right">
                                                                <span class="text-left text-capitalize">
                                                                    {!! $item->comment !!}
                                                                </span>
                                                                @if ($item->attachments)
                                                                @foreach ($item->attachments as $attach)
                                                                    
                                                                
                                                                    @if (file_exists($attach->file))
                                                                        {{-- <br><img src="{{ asset($item->file)}}" class="pt-2" width="200" style="width:30% !important"> --}}
                                                                        <a data-modal-size="modal-lg" title="View Details" class="dropdown-item modalLink" href="{{route('admin.comment-view-attachment', $attach->id)}}">{{ $loop->iteration .'. '.getFilePath3($attach->file) }}</a>
                                                                    @endif
                                                                    <br> 
                                                                    <small>
                                                                        <a target="_blank" href="{{url($attach->file)}}" download>
                                                                            @lang('saas::saas.download_file')
                                                                        </a>
                                                                    </small>
                                                                    @endforeach
                                                                @endif
                                                                
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach 
                                            <hr>
                                        @endif
                                    </div>
                                    @if(Auth::user()->role_id == 7)
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['user.comment_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                                    @else
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['admin.comment_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                                    @endif
                                    <input type="text" hidden value="{{ $data->id}}" name="id">
                                    <div class="row">
                                        <div class="col-lg-12 mb-5">
                                            <select class="primary_select"
                                                    name="active_status">
                                                <option data-display="@lang('common.status')"
                                                        value="">@lang('common.select_status')</option>
                                                <option value="0" {{ isset($data)? $data->active_status == 0 ? 'selected':'':'' }}>@lang('common.pending')</option>
                                                <option value="1" {{ isset($data)? $data->active_status == 1 ? 'selected':'':'' }}>@lang('saas::saas.ongoing')</option>
                                                <option value="2" {{ isset($data)? $data->active_status == 2 ? 'selected':'':'' }}>@lang('saas::saas.complete')</option>
                                                @if(Auth::user()->role_id == 1)
                                                    <option value="3" {{ isset($data)? $data->active_status == 3 ? 'selected':'':'' }}>@lang('common.close')</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-30">
                                        <label>@lang('saas::saas.support_ticket_message') <span>*</span> </label>
                                        <div class="primary_input">
                                            <textarea class="form-control summernote-editor {{ $errors->has('message') ? ' is-invalid' : '' }}" rows="5" name="message" cols="50" id="summernote" style="display: none;"></textarea>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('message'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('message') }}</strong>
                                                    </span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="row mb-30">
                                        <div class="col-lg-12">
                                            @include('saas::user._file_pond')
                                        </div>
                                    </div>
                                    <div class="form_btn d-flex justify-content-between">
                                        <div class="row  input-right-icon">
                                            
                                        </div>
                                        <button class="primary-btn small fix-gr-bg" type="submit">@lang('saas::saas.reply')</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>  
                        </div>  
                    </div>
            </div> 
        </div>
    </div>
</section>

@endsection
@section('script')
<script src="{{asset('public/backEnd/')}}/vendors/editor/summernote-bs4.js"></script>

    <script>
       $('#summernote').summernote({
            placeholder: 'Write here',
            tabsize: 2,
            height: 400,
      });
      $('.popover').css("display","none")
</script>
<script>
    function submit_comment(id) {
        $('.comment').css("display","none")
        $('#t').css("display","inline")
        $('.displaynone').css("display","inline")
        $('.submit_comment'+id).css("display","none")
        $('.submit_comment'+id).addClass("displaynone")
        $('.comment_id').val(id)
        $('.submit_com').css("display","none")
        var data=$('<textarea class="form-control textarea-reply comment{{ $errors->has('comment') ? ' is-invalid' : '' }}" name="comment" id="" cols="20" rows="3" placeholder="@lang('saas::saas.reply_here')..."></textarea>'+
                    '<div class="form_btn d-flex justify-content-between"> <div class="file_upload"> <div class="input-group"> <div class="custom-file"> <input type="file" class="custom-file-input {{ $errors->has('file') ? ' is-invalid' : '' }}" id="inputGroupFile04"'+
                    'aria-describedby="inputGroupFileAddon04" name="file"> <label class="custom-file-label" for="inputGroupFile04"> <svg class="upload_file svg-inline--fa fa-paperclip fa-w-14 icon mr-10" aria-hidden="true" data-prefix="fas" data-icon="paperclip" role="img"'+
                    'xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""> <path fill="currentColor"'+
                    'd="M43.246 466.142c-58.43-60.289-57.341-157.511 1.386-217.581L254.392 34c44.316-45.332 116.351-45.336 160.671 0 43.89 44.894 43.943 117.329 0 162.276L232.214 383.128c-29.855 30.537-78.633 30.111-107.982-.998-28.275-29.97-27.368-77.473 1.452-106.953l143.743-146.835c6.182-6.314 16.312-6.422 22.626-.241l22.861 22.379c6.315 6.182 6.422 16.312.241 22.626L171.427 319.927c-4.932 5.045-5.236 13.428-.648 18.292 4.372 4.634 11.245 4.711 15.688.165l182.849-186.851c19.613-20.062 19.613-52.725-.011-72.798-19.189-19.627-49.957-19.637-69.154 0L90.39 293.295c-34.763 35.56-35.299 93.12-1.191 128.313 34.01 35.093 88.985 35.137 123.058.286l172.06-175.999c6.177-6.319 16.307-6.433 22.626-.256l22.877 22.364c6.319 6.177 6.434 16.307.256 22.626l-172.06 175.998c-59.576 60.938-155.943 60.216-214.77-.485z">'+
                    '</path> </svg> @lang('common.add_attachment')</label> </div> </div> </div> <button class="primary-btn small fix-gr-bg submit_com current" type="submit">@lang('saas::saas.comment_reply')</button> </div>')
        $(this).css("display","block")
        for (let index = 0; index < 1; index++) {
        $('#'+id).append(data)
        }
    }
</script>
<script> 
    $( document ).ready(function() {
        var a = $('.linkk').data("id"); 
        if (a) {
        $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            type: 'post',
            url: "{{ url('admin/ticket-view/'.$data->id)}}",
            data: {
                id:a
            },
            dataType : 'json',
            success: function(data) {
            }
        });
        }
    });
</script>
@endsection
