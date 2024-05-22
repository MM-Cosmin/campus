<?php

$generalSetting = generalSetting();

    if (Auth::user() == "") { header('location:' . url('/login')); exit(); }

    Session::put('permission', App\GlobalVariable::GlobarModuleLinks());

    if(Module::find('FeesCollection')){
        $module = Module::find('FeesCollection');
        $module_name = @$module->getName();
        $module_status = @$module->isDisabled();
    }else{
        $module_name =NULL;
        $module_status =TRUE;
    }
?>
        <li>

            @if(Auth::user()->role_id == 1)
                <a href="{{route('superadmin-dashboard')}}" id="admin-dashboard">
            @else
                <a href="{{route('admin-dashboard')}}" id="admin-dashboard">
            @endif                
                <div class="nav_icon_small">
                    <span class="flaticon-speedometer"></span>
                </div>
                <div class="nav_title">
                    @lang('common.dashboard')
                </div>
            </a>
        </li>
@if(moduleStatusCheck('ParentRegistration')== TRUE )
     <li>
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">          
            <div class="nav_icon_small">
                <span class="flaticon-reading"></span>
            </div>
            <div class="nav_title">
                @lang('student.registration')
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuStudentRegistration">
            <li>
                <a href="{{route('parentregistration/saas-student-list')}}"> @lang('common.student_list')</a>
            </li>
            <li>
                <a href="{{route('parentregistration/settings')}}"> @lang('saas::saas.settings')</a>
            </li>
        </ul>
    </li>
@endif
    <li>
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">           
            <div class="nav_icon_small">
                <span class="flaticon-analytics"></span>
            </div>
            <div class="nav_title">
                @lang('saas::saas.institution')
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuAdministrator">
            <li>
                <a href="{{route('administrator/institution-list')}}">@lang('saas::saas.institution_list')</a>
            </li>
        </ul>
    </li>
    <!-- Start Subscription module  -->
@include('saas::menu.SaasSubscription')

    <!-- End Subscription module  -->




    {{-- <li>
    <a href="#subMenuPackages" data-toggle="collapse" aria-expanded="false"
        class="dropdown-toggle">
        <span class="flaticon-analytics"></span>
        @lang('lang.packages')
    </a>
    <ul class="list-unstyled" id="subMenuPackages">
        <li>
            <a href="{{url('administrator/package-list')}}"> @lang('lang.package_list')</a>
        </li>
    </ul>
    </li>

    <li>
    <a href="#subMenuInfixInvoice" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <span class="flaticon-accounting"></span> Invoice </a>
    <ul class="list-unstyled" id="subMenuInfixInvoice">
        <li><a href="{{url('infix/invoice-create')}}">Invoice Create</a></li>
        <li><a href="{{url('infix/invoice-list')}}">Invoice list</a></li>
        <li><a href="{{url('infix/invoice-category')}}">Invoice Category</a></li>
        <li><a href="{{url('infix/invoice-setting')}}">Invoice Setting</a></li>

    </ul>
    </li> --}}

    <li>
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">       
        <div class="nav_icon_small">
            <span class="flaticon-email"></span>
        </div>
        <div class="nav_title">
            @lang('communicate.communicate')
        </div>
    </a>
    <ul class="list-unstyled" id="subMenuCommunicate">
        <li>
            <a href="{{route('administrator/send-mail')}}">@lang('communicate.send_mail')</a>
            <a href="{{route('administrator/send-sms')}}">@lang('communicate.send_sms')</a>
            <a href="{{route('administrator/send-notice')}}">@lang('communicate.send_notice')</a>
            <li><a href="{{route('templatesettings.email-template')}}">@lang('communicate.sms_template')</a></li>
            {{-- <li><a href="{{route('templatesettings.email-template')}}"> @lang('common.email_template')</a></li> --}}
        </li>
    </ul>
    </li>

    <li>
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            
            <div class="nav_icon_small">
                <span class="flaticon-analysis"></span>
            </div>
            <div class="nav_title">
                @lang('reports.report') 
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuInfixInvoice">
            <li><a href="{{route('administrator/student-list')}}">@lang('common.student_list')</a></li>
            <li><a href="{{route('administrator/income-expense')}}">@lang('accounts.income')/@lang('accounts.expense')</a></li>
            <li><a href="{{route('administrator/teacher-list')}}">@lang('student.teacher_list')</a></li>
            <li><a href="{{route('administrator/class-list')}}">@lang('saas::saas.class_list')</a></li>
            <li><a href="{{route('administrator/class-routine')}}">@lang('academics.class_routine')</a></li>
            <li><a href="{{route('administrator/student-attendance')}}">@lang('student.student_attendance')</a></li>
            <li><a href="{{route('administrator/staff-attendance')}}">@lang('hr.staff_attendance')</a></li>
            {{-- <li><a href="{{route('administrator/merit-list-report')}}">@lang('exam.merit_list_report')</a></li> --}}
            {{-- <li><a href="{{route('saas_mark_sheet_report_student')}}">@lang('exam.mark_sheet_report')</a></li> --}}
            {{-- <li><a href="{{route('administrator/progress-card-report')}}">@lang('reports.progress_card_report')</a></li> --}}
        </ul>
    </li>
    
    <li>
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">        
        <div class="nav_icon_small">
            <span class="flaticon-settings"></span>
        </div>
        <div class="nav_title">
            @lang('system_settings.system_settings')
        </div>
    </a>
    <ul class="list-unstyled" id="subMenusystemSettings">
        @if(moduleStatusCheck('SassRolePermission'))
            <li>
                <a href="{{url('saasrolepermission/role')}}">@lang('saas::saas.saas_role')</a>
            </li>
            @endif

            <li>
                <a href="{{route('manage-adons')}}">@lang('saas::saas.module_manager')</a>
            </li>
            <li>
                <a href="{{route('administrator/general-settings')}}"> @lang('system_settings.general_settings')</a>
            </li>
            <li>
                <a href="{{route('saas-settings')}}"> @lang('saas::saas.saas_settings')</a>
            </li>
            <li>
                <a href="{{route('administrator/custom-domain-settings')}}"> @lang('saas::saas.custom_domain_settings')</a>
            </li>

            <li>
                <a href="{{route('administrator/email-settings')}}">@lang('system_settings.email_settings')</a>
            </li>
            <li>
                <a href="{{route('sms-settings')}}">@lang('system_settings.sms_settings')</a>
            </li>

            <li>
                <a href="{{route('administrator/manage-currency')}}">@lang('system_settings.manage-currency')</a>
            </li>
            @if(moduleStatusCheck('AppSlider')==true)
            <li>
                <a href="{{route('appslider.saas.index')}}">@lang('saas::saas.app_slider')</a>
            </li>
            @endif
            @if(moduleStatusCheck('University')==true)
            <li>
                <a href="{{route('administrator/university-settings')}}">@lang('university::un.university_settings')</a>
            </li>
            @endif

            <li>
                <a href="{{route('subscription/assign-module')}}">@lang('saas::saas.assign_module_and_menu')</a>
            </li>

            <li data-position="{{ menuPosition(4000) }}">
                <a href="{{ route('utility') }}">@lang('system_settings.utilities')</a>
            </li>

            {{-- <li>
                <a href="{{url('payment-method-settings')}}">@lang('lang.payment_method_settings')</a>
            </li> --}}


            {{-- <li>
                <a href="{{url('role')}}">@lang('lang.role')</a>
            </li> --}}

            {{-- <li>
                <a href="{{ url('administrator/module-permission')}}">@lang('lang.module_permission')</a>
            </li> --}}

            {{-- <li>
                <a href="{{url('login-access-control')}}">@lang('system_settings.login_permission')</a>
            </li> --}}

            {{-- <li>
                <a href="{{url('administrator/base-group')}}">@lang('lang.base_group')</a>
            </li> --}}

            <li>
                <a href="{{route('base_setup')}}">@lang('admin.base_setup')</a>
            </li>

            {{-- <li>
                <a href="{{url('academic-year')}}">@lang('common.academic_year')</a>
            </li> --}}

            {{-- <li>
                <a href="{{url('session')}}">@lang('lang.session')</a>
            </li> --}}
            {{-- <li>
                <a href="{{url('sms-settings')}}">@lang('lang.sms_settings')</a>
            </li> --}}

            {{-- @if(@in_array(152, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1)
                <li>
                    <a href="{{route('payment_method')}}"> @lang('lang.payment_method')</a>
                </li>
            @endif

            <li>
                <a href="{{url('payment-method-settings')}}">@lang('lang.payment_method_settings')</a>
            </li> --}}
            {{-- <li>
                <a href="{{url('email-settings')}}">@lang('lang.email_settings')</a>
            </li> --}}
            <li>
                <a href="{{route('language-list')}}">@lang('common.language')</a>
            </li>
            <li>
                <a href="{{route('administrator/language-settings')}}">@lang('system_settings.language_settings')</a>
            </li>
            @if(db_engine() != "pgsql")
            <li>
                <a href="{{route('administrator/backup-settings')}}">@lang('system_settings.backup_settings')</a>
            </li>
            @endif
            <li>
                <a href="{{route('button-disable-enable')}}">@lang('system_settings.button_manage') </a>
            </li>
            <li>
                <a href="{{route('templatesettings.email-template')}}">@lang('communicate.email_template')</a>
            </li>
            
            <li>
                <a href="{{route('update-system')}}">@lang('saas::saas.about_&_update')</a>
            </li>
    </ul>
    </li>


                    <li>
                        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">                            
                            <div class="nav_icon_small">
                                <span class="flaticon-software"></span>
                            </div>
                            <div class="nav_title">
                                @lang('style.style')
                            </div>
                        </a>
                        <ul class="list-unstyled" id="subMenusystemStyle">
                                <li>
                                    <a href="{{route('background-setting')}}">@lang('style.background_settings')</a>
                                    {{-- <a href="{{url('administrator/background-setting')}}">@lang('lang.background_settings')</a> --}}
                                </li>
                                <li>
                                    <a href="{{route('color-style')}}">@lang('style.color_theme')</a>
                                    {{-- <a href="{{url('administrator/color-style')}}">@lang('lang.color_theme')</a> --}}
                                </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#subMenuApi" data-toggle="collapse" aria-expanded="false"
                            class="has-arrow">
                            <div class="nav_icon_small">
                                <span class="flaticon-authentication"></span>
                            </div>
                            <div class="nav_title">
                                @lang('saas::saas.api_permission')
                            </div>
                        </a>
                        <ul class="list-unstyled" id="subMenuApi">
                            <li>
                                <a href="{{route('administrator/api/permission')}}">@lang('saas::saas.api_permission') </a>
                            </li>
                        </ul>
                    </li>



                    {{-- <li>
                        <a href="#subMenufrontEndSettings" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-software"></span>
                            @lang('lang.front_settings')
                        </a>
                        <ul class="list-unstyled" id="subMenufrontEndSettings">
                            <li>
                                <a href="{{url('admin-home-page')}}"> @lang('lang.home_page') </a>
                            </li>

                            <li>
                                <a href="{{url('news')}}">@lang('lang.news_list')</a>
                            </li>
                            <li>
                                <a href="{{url('news-category')}}">@lang('lang.news') @lang('student.category')</a>
                            </li>
                            <li>
                                <a href="{{url('testimonial')}}">@lang('lang.testimonial')</a>
                            </li>
                            <li>
                                <a href="{{url('course-list')}}">@lang('lang.course_list')</a>
                            </li>
                            <li>
                                <a href="{{url('contact-page')}}">@lang('lang.contact_page') </a>
                            </li>
                            <li>
                                <a href="{{url('contact-message')}}">@lang('lang.contact_message')</a>
                            </li>
                            <li>
                                <a href="{{url('about-page')}}"> @lang('lang.about_us') </a>
                            </li>
                            <li>
                                <a href="{{url('custom-links')}}"> @lang('lang.custom_links') </a>
                            </li>
                        </ul>
                    </li> --}}


    <li>
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">        
        <div class="nav_icon_small">
            <span class="flaticon-consultation"></span>
        </div>
        <div class="nav_title">
            @lang('saas::saas.ticket_system')
        </div>
    </a>
    <ul class="list-unstyled" id="Ticket">
        @if(userPermission(1911))
        <li><a href="{{ route('ticket.category') }}"> @lang('saas::saas.ticket_category')</a></li>
        @endif
        @if(userPermission(1918))
        <li><a href="{{ route('ticket.priority') }}">@lang('saas::saas.ticket_priority')</a></li>
        @endif
        @if(userPermission(1935))
        <li><a href="{{ route('admin.un_assign_ticket_list') }}">@lang('saas::saas.un_assign_ticket')</a> </li>
        @endif
        @if(userPermission(1925))
        <li><a href="{{ route('admin.ticket_list') }}">@lang('saas::saas.ticket_list')</a> </li>
        @endif
    </ul>


    @if(moduleStatusCheck('SaasRolePermission')== TRUE)

    <li>
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">   
    <div class="nav_icon_small">
        <span class="flaticon-consultation"></span>
    </div>
    <div class="nav_title">
        @lang('hr.human_resource') 
    </div>
    </a>
   
    <ul class="list-unstyled" id="subMenuHumanResource">
    <li>
     <a href="{{route('saas-staff-designation/index')}}">@lang('hr.designation') </a>
    </li>
     <li>
     <a href="{{route('saas-staff-department/index')}}">@lang('hr.department') </a>
    </li> 
    <li>
     <a href="{{route('saasaddStaff')}}">@lang('common.add_staff') </a>
    </li>
     <li>
     <a href="{{route('staff_directory')}}"> @lang('hr.staff_directory')</a>
    </li>
</ul>


</li>
@endif

</li>

