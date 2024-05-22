@php 

$setting  = generalSetting()
@endphp
@extends('backEnd.master')

@section('title')
@lang('saas::saas.saas_settings')
@endsection

@section('mainContent')
    <style type="text/css">
        #selectStaffsDiv, .forStudentWrapper {
            display: none;
        }

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
            left: 4px;
            bottom: 1px;
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
        /* .buttons_div_one{
        border: 4px solid #FFFFFF;
        border-radius:12px;

        padding-top: 0px;
        padding-right: 5px;
        padding-bottom: 0px;
        margin-bottom: 4px;
        padding-left: 0px;
         } */
        .buttons_div{
        border: 4px solid #19A0FB;
        border-radius:12px
        }
    </style>
    @php
    $settings = $setting;
    @endphp
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('saas::saas.saas_settings')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('saas::saas.saas_settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                
                <div class="col-lg-12">
                    <div class="buttons_div">
                        <div class="white-box">
                          
                                <div class="row">   
                                    @foreach ($saas_settings as  $data)
                                    <div class="col-lg-4">
                                        <div class="d-flex align-items-center justify-content-left">
                                            
                                            <span style="font-size: 17px; padding-right: 15px;"> {{ __('system_settings.' . $data->lang_name) }}</span>

                                           
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch saas_switch_btn" {{@$data->saas_status == 0? '':'checked'}} data-infix_module_id="{{ $data->infix_module_id }}" data-id="{{ $data->id }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>  
                                    @endforeach                                 
                                                                   
                                </div>
                           
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script>
    $(document).ready(function() {
            $(".saas_switch_btn").on("change", function() {
                let field_id = $(this).data("id");
                let infix_module_id= $(this).data("infix_module_id");
                if ($(this).is(":checked")) {
                    var field_status = "1";
                } else {
                    var field_status = "0";
                }
              
                
                let url = $("#url").val();
                

                $.ajax({
                    type: "POST",
                    data: {'field_status': field_status, 'field_id': field_id,'infix_module_id':infix_module_id},
                    dataType: "json",
                    url: url + "/" + "saas/field/switch",
                    success: function(data) {
                        //  location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                        // console.log(data);
                    },
                    error: function(data) {
                        console.log(data.responseJSON);
                        setTimeout(function() {
                            toastr.error(data.responseJSON.message, "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
</script>
@endpush