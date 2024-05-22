
@extends('gmeet::layouts.master')
@section('title')
    @lang('common.virtual_meeting')
@endsection

@section('css')
    <style>
        .propertiesname {
            text-transform: uppercase;
        }

        . .recurrence-section-hide {
            display: none !important
        }

    </style>
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
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(gMainSettings()->use_api == 1 && !in_array(auth()->user()->role_id, [2,3]))
            @if(userPermission('g-meet.virtual-meeting.store'))
            <div class="row align-items-center mb-25">
                <div class="col-lg-8 col-md-6 col-6">
                    <div class="main-title">
                        <h3 class="mb-30"></h3>
                    </div>
                </div>
                <div class="col-lg-4 text-md-right col-md-6 mb-30-lg col-6 text-right ">
                    <a class="primary-btn-small-input primary-btn small fix-gr-bg {{ $googleAccount ? 'text-lowercase' : '' }}" href="{{ !$googleAccount ? route('gmeet.google.store', ['type'=>'meeting']) : '#' }}"> <i class="fa fa-google mr-2"></i>{{ $googleAccount ? ($googleAccount->email) : __('gmeet::gmeet.Sign In with Gmail') }}</a>
                    @if($googleAccount)
                        <a class="primary-btn-small-input primary-btn small fix-gr-bg"
                            href="{{  route('g-meet.google.destroy', $googleAccount)  }}">  <span class="ti-unlock mr-1"></span>{{ __('common.logout') }}</a>
                    @endif   
                </div>
                
            </div>
            @endif
        @endif
        <div class="row">
                @if (!in_array(auth()->user()->role_id, [2,3]))
                    @if(userPermission('g-meet.virtual-meeting.store'))
                        @include('gmeet::meeting.inc.form')
                    @endif
                @endif
               
                @include('gmeet::meeting.inc.list')
        </div>
    </div>
</section>
@endsection
@section('script')
    <script>
        @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
        @endif
    </script>

    @if(isset($editdata))
        @if ( old('is_recurring',$editdata->is_recurring) == 1)
            <script>$(".recurrence-section-hide").show();</script>
        @else
            <script>$(".recurrence-section-hide").hide(); $(".day_hide").hide();</script>
        @endif
    @elseif( old('is_recurring') == 1)
        <script>$(".recurrence-section-hide").show();</script>
    @else
        <script>$(".recurrence-section-hide").hide();  $(".day_hide").hide();</script>
    @endif
    @if(isset($editdata))
        <script>$(".default-settings").show();</script>
    @else
    <script>$(".default-settings").hide();</script>
     @endif
    <script>
        (function ($) {
            $(document).on('change','.user_type',function(){
                let userType = $(this).val();
                $("#selectMultiUsers").empty();
                $.get('{{ route('g-meet.user.list.user.type.wise') }}',{ user_type: userType },function(res){               
                    $.each(res.users, function( index, item ) {
                        $('#selectMultiUsers').append(new Option(item.full_name, item.id))
                    });
                    $('#selectMultiUsers').multiselect('reset');
                })
            })

            $(document).on('click','.recurring-type',function(){
                if($("input[name='is_recurring']:checked").val() == 0){
                    $(".recurrence-section-hide").hide();
                    $(".day_hide").hide();
                }else{
                    $(".recurrence-section-hide").show();
                }
            })
            $(document).on("change", "#recurring_type", function() {
                 var type = $(this).val();
                 
                 if(type==2){
                    $(".day_hide").show();
                 }else{
                    $(".day_hide").hide();
                 }
              
            })

            $(document).on('click','.chnage-default-settings',function(){
                if($(this).val() == 0){
                    $(".default-settings").hide();
                }else{
                    $(".default-settings").show();
                }
            })
        })(jQuery)
    </script>
@stop
