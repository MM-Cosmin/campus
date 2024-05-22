@extends('backEnd.master')
@section('title')
@lang('bbb::bbb.virtual_meeting')
@endsection

@section('css')
    <style>
        .propertiesname {
            text-transform: uppercase;
        }
        .recurrence-section-hide {
            display: none ;
        }
    </style>
@endsection

@section('mainContent')

    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('bbb::bbb.virtual_meeting')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">  @lang('common.dashboard')</a>
                     <a href="#">@lang('bbb::bbb.bbb')</a>
                    <a href="#">@lang('bbb::bbb.virtual_meeting')</a>
                </div>
            </div>
        </div>
    </section>


    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                @include('bbb::meeting.includes.form')
                @include('bbb::meeting.includes.list')
            </div>
        </div>
    </section>
@endsection

@push('script')
    @if(isset($editdata))
        @if ( old('is_recurring',$editdata->is_recurring) == 1)
            <script>$(".recurrence-section-hide").show();</script>
        @else
            <script>$(".recurrence-section-hide").hide();</script>
        @endif
    @elseif( old('is_recurring') == 1)
        <script>$(".recurrence-section-hide").show();</script>
    @else
        <script>$(".recurrence-section-hide").hide();</script>
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
                $.get('{{ route('bbb.user.list.user.type.wise') }}',{ user_type: userType },function(res){
                   
                    $.each(res.users, function( index, item ) {
                        $('#selectMultiUsers').append(new Option(item.full_name, item.id));
                     
                    });
                   
                    $('#selectMultiUsers').multiselect('reset');
                })
            })

            $(document).on('click','.recurring-type',function(){

                if($("input[name='is_recurring']:checked").val() == 0){
                    $(".recurrence-section-hide").hide();
                }else{
                    $(".recurrence-section-hide").show();
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
@endpush
