
@extends('backEnd.master')
@section('title')
    @lang('common.virtual_class')
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
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('common.virtual_class_list')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.virtual_class')</a>
                    <a href="#">@lang('zoom::zoom.list')</a>
                </div>
            </div>
        </div>
    </section>


    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                @if(auth()->user()->role_id ==2 || auth()->user()->role_id ==3)
                <div class="col-lg-12 student-details up_admin_visitor">
                    <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">

                        @foreach ($records as $key => $record)
                            <li class="nav-item">
                                <a class="nav-link @if ($key == 0) active @endif " href="#tab{{ $key }}" role="tab"
                                    data-toggle="tab">
                                    @if(moduleStatusCheck('University'))
                                    {{$record->semesterLabel->name}} ({{$record->unSection->section_name}}) - {{@$record->unAcademic->name}}
                                        @else 
                                        {{$record->class->class_name}} ({{$record->section->section_name}}) 
                                    @endif 
                                 </a>
                            </li>
                        @endforeach

                    </ul>

                    @php
                        if(moduleStatusCheck('University')){
                            $meetings = [];
                        }else{
                            $meetings = in_array(auth()->user()->role_id, [2,3]) ? $record->student_virtual_class  : $meetings;
                        }
                        @endphp


                    <!-- Tab panes -->
                    <div class="tab-content mt-40">
                        <!-- Start Fees Tab -->
                        @foreach ($records as $key => $record)
                            <div role="tabpanel" class="tab-pane fade  @if ($key == 0) active show @endif"
                                id="tab{{ $key }}">
                                @php
                                if(moduleStatusCheck('University')){
                                    $meetings = $record->UnstudentVirtualClass;
                                } else{
                                     $meetings =  $record->student_virtual_class ;
                                }
                                @endphp 
                                @include('zoom::virtualClass.includes.list', ['meetings' => $meetings])
                            </div>


                        @endforeach
                    </div>
                </div>
                @else    
                @include('zoom::virtualClass.includes.form')
               
                @include('zoom::virtualClass.includes.list')
                @endif
            </div>
        </div>
    </section>
@endsection

@include('backEnd.partials.data_table_js')

@section('script')
    @if (isset($editdata))
        @if (old('is_recurring', $editdata->is_recurring) == 1)
            <script>
                $(".recurrence-section-hide").show();
            </script>
        @else

            <script>
                $(".recurrence-section-hide").hide();
                $(".day_hide").hide();
            </script>
        @endif
    @elseif(old('is_recurring') == 1)
        <script>
            $(".recurrence-section-hide").show();
        </script>
    @else
        <script>
            $(".recurrence-section-hide").hide();
            $(".day_hide").hide();
        </script>
    @endif
    @if (isset($editdata))
        <script>
            $(".default-settings").show();
        </script>
    @else
        <script>
            $(".default-settings").hide();
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $(document).on('change', '.user_type', function() {
                let userType = $(this).val();
                $("#selectSectionss").select2().empty()
                $.get('{{ route('zoom.user.list.user.type.wise') }}', {
                    user_type: userType
                }, function(res) {
                    $("#selectSectionss").select2().empty()
                    $.each(res.users, function(index, item) {
                        $('#selectSectionss').append(new Option(item.full_name, item.id))
                    });
                })
            })

            $(document).on('click', '.recurring-type', function() {
                if ($("input[name='is_recurring']:checked").val() == 0) {
                    $(".recurrence-section-hide").hide();
                    $(".day_hide").hide();
                } else {
                    $(".recurrence-section-hide").show();
                }
            })
            $("#recurring_type").on("change", function() {
                var type = $(this).val();

                if (type == 2) {
                    $(".day_hide").show();
                } else {
                    $(".day_hide").hide();
                }

            })
            $(document).on('click', '.chnage-default-settings', function() {
                if ($(this).val() == 0) {
                    $(".default-settings").hide();
                } else {
                    $(".default-settings").show();
                }
            })
        })
    </script>

@stop
