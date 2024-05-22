@extends('gmeet::layouts.master')
@section('title')
    @lang('common.virtual_class')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('common.virtual_class')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('gmeet::gmeet.gmeet')</a>
                    <a href="#">@lang('common.virtual_class')</a>
                    <a href="#">@lang('common.show')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="mb-30"> @lang('common.topic') : {{ @$results['topic'] }}</h3>
                </div>
                @if (is_null($virtualClass->course_id) && moduleStatusCheck('Lms') == false)
                    @if (userPermission('g-meet.virtual-class.edit'))
                        <div class="col-md-2 pull-right  text-right">
                            <a href="{{ route('g-meet.virtual-class.edit', $virtualClass->id) }}"
                                class="primary-btn small fix-gr-bg "> <span class="ti-pencil-alt"></span> @lang('common.edit')
                            </a>
                        </div>
                    @endif
                @endif
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="" class="table school-table-style" cellspacing="0"
                                width="100%">

                                <tr>
                                    <th>#</th>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.status')</th>
                                </tr>
                                @php $sl = 1 @endphp
                                @if (!is_null($virtualClass->course_id) && moduleStatusCheck('Lms'))
                                    <tr>
                                        <td>{{ $sl++ }} </td>
                                        <td class="propertiesname">@lang('lms::lms.course') </td>
                                        <td>{{ $virtualClass->course->course_title }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.class') </td>
                                    <td>{{ $virtualClass->class->class_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.class_section')</td>
                                    <td>{{ $virtualClass->section->section_name }}
                                    </td>

                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.topic')</td>
                                    <td>{{ @$virtualClass->topic }}</td>
                                </tr>
                                @if ($virtualClass->weekly_days != null)
                                    <tr>
                                        <td>{{ $sl++ }} </td>
                                        <td class="propertiesname">@lang('gmeet::gmeet.repeat_day')</td>
                                        <td>
                                            @foreach ($assign_day as $day)
                                                {{ $day->name }},
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.teachers')</td>
                                    <td> {{ $virtualClass->teachersName }} </td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname"> @lang('common.attached_file') </td>
                                    <td>
                                        @if ($virtualClass->attached_file)
                                            <a href="{{ asset($virtualClass->attached_file) }}" download=""><i
                                                    class="fa fa-download mr-1"></i> {{ __('common.download') }}</a>
                                        @else
                                            {{ __('gmeet::gmeet.No File') }}
                                        @endif
                                    </td>

                                </tr>

                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.start_date_time')</td>
                                    <td>{{ $virtualClass->MeetingDateTime }}</td>
                                </tr>
                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('gmeet::gmeet.gmeet')</td>
                                    <td>
                                        @if($virtualClass->CurrentStatus == 'started')
                                        <a href="{{ $virtualClass->gmeet_url }}" target="_blank" rel="noopener noreferrer">{{ __('gmeet::gmeet.start_join') }}</a>
                                        @else 
                                        <a href="#Closed" class="primary-btn small bg-warning text-white border-0">{{ $virtualClass->CurrentStatus }}</button> 
                                            
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('gmeet::gmeet.virtual_class_id')</td>
                                    <td>{{ @$results['id'] }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.password')</td>
                                    <td>{{ @$results['password'] }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('gmeet::gmeet.video_link')  </td>
                                    <td>
                                        @if($virtualClass->video_link)
                                       <a class="primary-btn small fix-gr-bg" href="{{ $virtualClass->video_link }}" target="_blank" rel="noopener noreferrer">
                                        {{ __('common.click') }}
                                    </a>  
                                       @endif 
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname"> @lang('gmeet::gmeet.recorded_video')   </td>
                                    <td>
                                         @if($virtualClass->local_video) 
                                         <a class="primary-btn small fix-gr-bg" href="{{ asset($virtualClass->local_video) }}" download="" ><i class="fa fa-download mr-1"></i> {{ __('common.download') }}</a> 
                                            @if (userPermission("g-meet.upload-document"))
                                                <a class="primary-btn small fix-gr-bg" data-toggle="modal" data-target="#removeVideo"
                                                        href="#"><i class="fa fa-trash mr-1"></i>@lang('common.remove')</a>
                                            @endif
                                         @else 
                                            @if (userPermission("g-meet.upload-document"))
                                                        <a class="primary-btn small fix-gr-bg modalLink" data-modal-size="modal-md"
                                                            title="@lang('gmeet::gmeet.upload_recorded_video')"
                                                            href="{{route('g-meet.upload-document-modal', [$virtualClass->id, 'type'=>'class'])}}"><i class="fa fa-upload mr-1"></i>@lang('gmeet::gmeet.upload')</a>
                                            @endif
                                         @endif  </td>
    
                                     
                                    </td>
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
    
                    <div class="mt-20 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg"
                            data-dismiss="modal">@lang('common.cancel')</button>
                        <form class=""
                            action="{{ route('g-meet.upload-document-destroy') }}"
                            method="POST">
                            @csrf
                           <input type="hidden" name="id" value="{{ $virtualClass->id }}">
                           <input type="hidden" name="type" value="class">
                            <button class="primary-btn fix-gr-bg"
                                type="submit">@lang('common.delete')</button>
                        </form>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
@endsection
