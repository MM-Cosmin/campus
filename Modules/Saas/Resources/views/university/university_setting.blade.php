@extends('backEnd.master')
@section('title')
    @lang('lms::lms.settings')
@endsection
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 3px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background: linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

</style>
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lms::lms.lms_settings') </h1>

                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('university::un.university')</a>
                    <a href="#">@lang('lms::lms.settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor" id="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12">                                
                               {{-- module status enable or disable --}}
                               <div class="white_box_30px mt-45">                                   
                                    <div class="">
                                        <div class="row">
                                            <div class="col-lg-12">
                    
                                                <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                    
                                                    <thead>
                    
                                                    <tr>
                                                        <th>@lang('lead::lead.school_name')</th>
                                                        <th>@lang('lead::lead.module')</th>
                                                        <th>@lang('lead::lead.status')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($module_settings as $module)
                                                        <tr>
                                                            <td>{{ $module->school->school_name }}</td>
                                                            <td>{{ strtoupper($module->module_name) }}</td>
                                                            <td>
                                                                  <label class="switch_toggle">
                                                                    <input type="checkbox" data-school_id="{{$module->school_id}}" data-id="{{$module->id}}"
                                                                        class="lms_settings_switch_btn" {{$module->school_id==1 ? 'disabled':''}} {{@$module->active_status == 0? '':'checked'}}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </td>
                    
                                                        </tr>
                    
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               {{-- end module Status --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('backEnd.partials.data_table_js')
@push('script')
<script>
    
    $(".lms_settings_switch_btn").on("change", function () {
                var school_id = $(this).data("school_id");
                var id = $(this).data("id");

                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }


                var url = $("#url").val();


                $.ajax({
                    type: "POST",
                    data: {'id': id, 'status': status, 'school_id': school_id},
                    dataType: "json",
                    url: url + "/" + "lms/switch",
                    success: function (data) {
                        //  location.reload();
                        setTimeout(function () {
                            toastr.success(data.message, "Success", {
                                timeOut: 5000,
                            });
                        }, 500);
                        // console.log(data);
                    },
                    error: function (data) {
                        // console.log('no');
                        setTimeout(function () {
                            toastr.error(data.error, "Failed", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });

    $("#admin_commission").on("input", function() {
        var admin_com = $(this).val();
        if(admin_com <= 100 ){
            $('[name=teacher_commission]').val(100 - admin_com);
        }else{
            $('[name=admin_commission]').val(100);
            toastr.warning('Maximum Value 100', 'Warnning', {
                       timeOut: 5000
                       })
        }
        
    });

</script>
@endpush     
