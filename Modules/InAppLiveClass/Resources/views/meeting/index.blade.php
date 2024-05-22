@extends(theme('layouts.master'))
@section('title')
    {{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} | {{__('common.In-App Live Class')}}
@endsection
@section('mainContent')
    @php
        $user =\Illuminate\Support\Facades\Auth::user();
            $setting =json_decode($session->class->host_setting);
            if ($setting){
                $chat =$setting->chat??0;
                $video =$setting->video??0;
                $audio =$setting->audio??0;
                $share_screen =$setting->share_screen??0;
            }else{
                $chat=0;
                $video=0;
                $audio=0;
                $share_screen=0;
            }
    @endphp
    {{generateBreadcrumb()}}
    <link rel="stylesheet" type="text/css" href="{{asset('Modules/InAppLiveClass/Resources/assets/custom.css')}}">

    <div class="agora-page">


        <div class="d-flex flex-column flex-lg-row">
            <div class="agora-stream flex-grow-1 bg-info-light p-15">
                <div class="agora-navbar d-flex align-items-center justify-content-between shoa px-35 py-10">
                    <div class="session-title d-flex align-items-center justify-content-center ">
                        <div class="site_logo">
                            <a href="{{url('/')}}">
                                <img src="{{asset(Settings('logo'))}}" alt="{{Settings('site_title')}}">
                            </a>
                        </div>
                        <h4
                            class="d-flex align-items-center mb-0 ">{{ $session->topic }} |


                            <span id="streamTimer"
                                  class="font-14 text-gray d-flex align-items-center justify-content-center">
                        <span
                            class="d-flex align-items-center justify-content-center text-dark time-item hours">00</span>:
                        <span
                            class="d-flex align-items-center justify-content-center text-dark time-item minutes">00</span>:
                        <span
                            class="d-flex align-items-center justify-content-center text-dark time-item seconds">00</span>
                    </span>
                        </h4>
                    </div>


                </div>
                @include('inappliveclass::meeting.stream')
            </div>

            <div class="agora-tabs ">
                <div id="collapseBtn" type="button" class="btn-transparent d-flex">
                    <span class=""><i class="ti-angle-right"></i></span>
                    <span>{{__('chat.chat')}}</span>
                </div>
                <div class="host-user d-flex align-items-center">
                    <div class="host-img">
                        <img src="{{getProfileImage($session->instructor->image,$session->instructor->name)}}" alt="">
                    </div>
                    <div class="host-content">
                        <h4>{{$session->instructor->name}}</h4>
                        <p>{{__('frontend.Start At')}} | {{$session->time}}</p>
                    </div>
                </div>
                <div class="tab-content h-100" id="nav-tabContent">
                    <div class="pb-20 tab-pane fade show active h-100" id="chat" role="tabpanel"
                         aria-labelledby="chat-tab">
                        @include('inappliveclass::meeting.chat')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    var userDefaultAvatar = '{{ getProfileImage($user->image,$user->name) }}';
    var defaultAvatar = '{{ asset('public/demo/user/admin.jpg') }}';
    var joinedToChannel = '{{__('frontend.Joined the live class')}}';
    var appId = '{{ $appId }}';
    var accountName = '{{ $accountName }}';
    var channelName = '{{ $channelName }}';
    var streamRole = '{{ $streamRole }}';
    var redirectAfterLeave = '{{ url('/') }}';
    var liveEndedLang = '{{__('frontend.This live has been ended')}}.';
    var redirectToPanelInAFewMomentLang = '{{__('frontend.You will be redirected to the homepage in a few moments')}}';
    var streamStartAt = Number({{ $streamStartAt }})
</script>
@section('js')
    <script>
        $('header').hide();
        $('footer').hide();
        $('.notification_wrapper').hide();
    </script>

@endsection
