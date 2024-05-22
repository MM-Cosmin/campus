@extends('backEnd.master')
@section('title')
    @lang('communicate.notice_board')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.notice_board')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.notice_board')</a>
            </div>
        </div>
    </div>
</section>

<section class="mb-40 sms-accordion">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('communicate.all_notices')</h3>
                </div>
            </div>
            <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                <a href="{{route('administrator/add-notice')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('communicate.add_notice')
                </a>
            </div>
        </div>
              @if(session()->has('message-success'))
             <div class="alert alert-success">
             {{ session()->get('message-success') }}
              </div>
              @elseif(session()->has('message-danger'))
              <div class="alert alert-danger">
                  {{ session()->get('message-danger') }}
              </div>
              @endif
        <div class="row">
            <div class="col-lg-12">
                <div id="accordion">
                   @php $i = 0; @endphp
                   @if(isset($allNotices))
                   @foreach($allNotices as $value)
                   <div class="card">
                     <a class="card-link" data-toggle="collapse" href="#notice{{$value->id}}">
                        <div class="card-header d-flex justify-content-between">

                            {{$value->notice_title}}

                            <div>
                             <a href="{{route('administrator/edit-notice',$value->id)}}">
                                <button type="submit" class="primary-btn small tr-bg mr-0">@lang('common.edit') </button>
                             </a>
                                <a data-toggle="modal" data-target="#deleteClassModal{{$value->id}}"  href="#">
                                <button type="button" class="primary-btn small tr-bg mr-0">@lang('common.delete') </button>    
                                </a>
                         
                            </div>
                        </div>
                    </a>
                    @php $i++; @endphp
                    <div id="notice{{$value->id}}" class="collapse {{$i ==  1 ? 'show' : ''}}" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    {!! $value->notice_message !!}
                                </div>
                                <div class="col-lg-4">
                                    <p class="mb-0">
                                        <span class="ti-calendar mr-10"></span>
                                        @lang('communicate.publish_date') :
                                        
                                         
                                         {{$value->publish_on != ""? dateConvert($value->publish_on):''}}


                                    </p>
                                    <p class="mb-0">
                                        <span class="ti-calendar mr-10"></span>
                                        @lang('communicate.notice_date') : 
                                        
                                        {{$value->notice_date != ""? dateConvert($value->notice_date):''}}

                                    </p>
                                    <h4>@lang('communicate.message_to')</h4>
                                    @if (count($value->getInstitute()) > 0)
                                        @foreach ($value->getInstitute() as $key => $institution)
                                            <p class="mb-0">
                                                <span class="ti-user mr-10"></span>{{$institution->school_name}}</p>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade admin-query" id="deleteClassModal{{$value->id}}" >
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('communicate.delete_notice')</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                <div class="text-center">
                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                </div>

                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                    <a href="{{ route('administrator/delete-notice', [$value->id])}}" class="text-light">
                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                        </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
</section>
@endsection
