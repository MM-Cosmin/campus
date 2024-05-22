@extends('gmeet::layouts.master')
@section('title')
    @lang('common.virtual_meeting')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('common.virtual_meeting')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('gmeet::gmeet.gmeet')</a>
                    <a href="#">@lang('common.virtual_meeting')</a>
                    <a href="#">@lang('common.show')</a>
                </div>
            </div>
        </div>
    </section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-md-10">
                <h3 class="mb-30"> @lang('common.topic') : {{@$virtualMeeting['topic']}}</h3>
            </div>
            <div class="col-md-2 pull-right  text-right">
            @if(userPermission('g-meet.virtual-meeting.edit'))
                    <a href="{{ route('g-meet.virtual-meeting.edit', $virtualMeeting->id) }}" class="primary-btn small fix-gr-bg "> <span class="ti-pencil-alt"></span> @lang('common.edit') </a>
                @endif
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="" class="table school-table-style" cellspacing="0" width="100%">

                            <tr>
                                <th>#</th>
                                <th>@lang('common.name')</th>
                                <th>@lang('common.status')</th>
                            </tr>
                            @php $sl = 1 @endphp
                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.topic')</td> <td>{{@$virtualMeeting['topic']}}</td>
                            </tr>
                               
                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.participants')</td> <td> {{ $virtualMeeting->participatesName }}  </td>
                            </tr>
                            @if($virtualMeeting->attached_file)
                                <tr>
                                    <td>{{ $sl++ }} </td> <td class="propertiesname"> @lang('common.attached_file') </td> <td> <a href="{{ asset($virtualMeeting->attached_file) }}" download="" ><i class="fa fa-download mr-1"></i> {{ __('common.download') }}</a>  </td>
                                </tr>
                            @endif
                            <tr>
                                <td> {{ $sl++ }} </td> <td class="propertiesname">@lang('common.start_date_time')</td> <td>{{ $virtualMeeting->MeetingDateTime }}</td>
                            </tr>
                            <tr>
                                <td>{{ $sl++ }} </td>
                                <td class="propertiesname">@lang('gmeet::gmeet.video_link')  </td>
                                <td>
                                    @if($virtualMeeting->video_link)
                                   <a class="primary-btn small fix-gr-bg" href="{{ $virtualMeeting->video_link }}" target="_blank" rel="noopener noreferrer">
                                    {{ __('common.click') }}
                                </a>  
                                   @endif 
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $sl++ }} </td>
                                <td class="propertiesname"> @lang('gmeet::gmeet.recorded_video')   </td>
                                <td>
                                    @if($virtualMeeting->local_video) 
                                    <a class="primary-btn small fix-gr-bg" href="{{ asset($virtualMeeting->local_video) }}" download="" ><i class="fa fa-download mr-1"></i> {{ __('common.download') }}</a> 
                                       @if (userPermission("g-meet.virtual-meeting.upload"))
                                           <a class="primary-btn small fix-gr-bg" data-toggle="modal" data-target="#removeVideo"
                                                   href="#"><i class="fa fa-trash mr-1"></i>@lang('common.remove')</a>
                                       @endif
                                    @else 
                                       @if (Auth::user()->id == $virtualMeeting->created_by && userPermission("g-meet.virtual-meeting.upload"))
                                                   <a class="primary-btn small fix-gr-bg modalLink" data-modal-size="modal-md"
                                                       title="@lang('gmeet::gmeet.upload_recorded_video')"
                                                       href="{{route('g-meet.upload-document-modal', [$virtualMeeting->id, 'type'=>'meeting'])}}"><i class="fa fa-upload mr-1"></i>@lang('gmeet::gmeet.upload')</a>
                                       @endif
                                    @endif    </td>


                            </tr>                               
                            <tr>
                                <td> {{ $sl++ }} </td>
                                <td class="propertiesname">@lang('gmeet::gmeet.gmeet')</td>
                                <td>
                                    @if($virtualMeeting->CurrentStatus == 'started')
                                    <a class="primary-btn small bg-success text-white border-0" href="{{ $virtualMeeting->gmeet_url }}" target="_blank"
                                    rel="noopener noreferrer">{{ auth()->user()->id == $virtualMeeting->created_by ? __('common.start') : __('common.join') }}</a>
                                @elseif($virtualMeeting->CurrentStatus == 'waiting')
                                    <a class="primary-btn small bg-info text-white border-0" href="{{ $virtualMeeting->gmeet_url }}" target="_blank"
                                    rel="noopener noreferrer">{{ __('common.waiting') }}</a>
                                @elseif($virtualMeeting->CurrentStatus == 'closed')
                                    <a class="primary-btn small bg-warning text-white border-0"
                                    rel="noopener noreferrer">{{ __('common.closed') }}</a>
                                @else

                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.description')</td> <td> {{ $virtualMeeting->description }}  </td>
                            </tr>                            

                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.timezone')</td> <td>{{@$virtualMeeting['timezone']}}</td>
                            </tr>

                            <tr>
                                <td>{{ $sl++ }} </td> <td class="propertiesname">@lang('common.created_at') </td> <td>{{Carbon\Carbon::parse(@$virtualMeeting['created_at'])->format('m-d-Y')}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade admin-query" id="removeVideo">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('gmeet::gmeet.Remove Recorded Video')</h4>
                <button type="button" class="close"
                    data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('gmeet::gmeet.are_you_sure_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg"
                        data-dismiss="modal">@lang('common.cancel')</button>
                    <form class=""
                        action="{{ route('g-meet.upload-document-destroy') }}"
                        method="POST">
                        @csrf
                       <input type="hidden" name="id" value="{{ $virtualMeeting->id }}">
                       <input type="hidden" name="type" value="meeting">
                        <button class="primary-btn fix-gr-bg"
                            type="submit">@lang('common.delete')</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

