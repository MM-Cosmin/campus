@extends('backEnd.master')
@section('title')
@lang('common.virtual_class')
@endsection

@section('css')
    <style>
        .propertiesname {
            text-transform: uppercase;
        }
        .recurrence-section-hide {
            display: none !important
        }
    </style>
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.virtual_class')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('bbb::bbb.bbb')</a>
                    <a href="#">@lang('common.virtual_class')</a>
                </div>
            </div>
        </div>
    </section>


    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                @if(auth()->user()->role_id ==2 || auth()->user()->role_id ==3)
                <div class="col-lg-12 student-details up_admin_visitor">
                    <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">

                        @foreach ($records as $key => $record)
                            <li class="nav-item">
                                <a class="nav-link @if ($key == 0) active @endif " href="#tab{{ $key }}" role="tab"
                                    data-toggle="tab">{{ $record->class->class_name }}
                                    ({{ $record->section->section_name }}) </a>
                            </li>
                        @endforeach

                    </ul>


                    <!-- Tab panes -->
                    <div class="tab-content mt-40">
                        <!-- Start Fees Tab -->
                        @foreach ($records as $key => $record)
                            <div role="tabpanel" class="tab-pane fade  @if ($key == 0) active show @endif"
                                id="tab{{ $key }}">
                               
                                @include('bbb::virtualClass.includes.list')
                            </div>


                        @endforeach
                    </div>
                </div>
                @else
                @include('bbb::virtualClass.includes.form')
                @include('bbb::virtualClass.includes.list')
                @endif
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

        $(document).ready(function () {
            $(document).on('change', '.user_type', function () {
                let userType = $(this).val();
                $("#selectSectionss").select2().empty()
                $.post('{{ url('get-user-by-role') }}', {user_type: userType}, function (res) {
                    $("#selectSectionss").select2().empty()
                    $.each(res, function (index, item) {
                        $('#selectSectionss').append(new Option(item.name, item.id))
                    });
                })
            })

            $(document).on('click', '.recurring-type', function () {
                if ($("input[name='is_recurring']:checked").val() == 0) {
                    $(".recurrence-section-hide").hide();
                } else {
                    $(".recurrence-section-hide").show();
                }
            })

            $(document).on('click', '.chnage-default-settings', function () {
               
                if ($(this).val() == 0) {
                    $(".default-settings").hide();
                } else {
                    $(".default-settings").show();
                }
            })
        })
    </script>
@endpush
