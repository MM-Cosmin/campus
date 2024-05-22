@extends('backEnd.master')
@section('title')
    @lang('zoom::zoom.virtual_meeting')
@endsection

@section('css')
<style>
    .propertiesname{
        text-transform: uppercase;
    }.
    .recurrence-section-hide {
       display: none!important
    }
    </style>
@endsection

@section('mainContent')

<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('zoom::zoom.meetings_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('zoom::zoom.meetings')</a>
                <a href="#">@lang('zoom::zoom.list')</a>
            </div>
        </div>
    </div>
</section>


<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            @if(userPermission('zoom.meetings.store') || userPermission('zoom.meetings.edit'))
                @include('zoom::meeting.includes.form')
            @endif
                @include('zoom::meeting.includes.list')
        </div>
    </div>
</section>
@endsection

@section('script')
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
        
        $(document).ready(function(){
            $(document).on('change','.user_type',function(){
                let userType = $(this).val();
                $("#selectMultiUsers").empty();
                $.get('{{ route('zoom.user.list.user.type.wise') }}',{ user_type: userType },function(res){                 
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
            $("#recurring_type").on("change", function() {
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
        })
    </script>
@stop
