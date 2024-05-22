@extends('backEnd.master')
@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('saas::saas.Custom Domain') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('saas::saas.settings')</a>
                    <a href="#">@lang('saas::saas.Custom Domain')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    @if (session()->has('message-success') != '')
                        @if (session()->has('message-success'))
                            <div class="alert alert-success">
                                {{ session()->get('message-success') }}
                            </div>
                        @endif
                    @endif
                    @if (session()->has('message-danger') != '')
                        @if (session()->has('message-danger'))
                            <div class="alert alert-danger">
                                {{ session()->get('message-danger') }}
                            </div>
                        @endif
                    @endif
                    <div class="white-box">
                        <div class="row">
                            <div class="col-md-6 offset-md-3 text-center ">
                                <h2>@lang('saas::saas.Custom Domain')</h2>
                                @if (!$school->custom_domain)
                                    <p>@lang('saas::saas.You have not set up any custom domains. You can set up one, such as school.yourdomain.com').</p>
                                    <p>
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="modal"
                                            data-target="#domain_configure_modal">
                                            <i class="ti-check mr-1"></i>@lang('saas::saas.configure')</button>
                                    </p>
                                    @else

                                    <p>{{  __('saas::saas.You have set up your custom domains to') }} <strong> <a href="{{ 'https://'.$school->custom_domain }}" target="_blank">{{ 'https://'.$school->custom_domain }}</a> </strong></p>
                                    <p>
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="modal"
                                            data-target="#domain_remove_modal">
                                            <i class="ti-check mr-1"></i>@lang('saas::saas.remove')</button>

                                            <button class="primary-btn fix-gr-bg submit" data-toggle="modal"
                                            data-target="#domain_configure_modal">
                                            <i class="ti-check mr-1"></i>@lang('saas::saas.configure')</button>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade admin-query" id="domain_configure_modal">
        <div class="modal-dialog modal_800px modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title custome_domain_heading domain_heading">{{ __('saas::saas.Connect a domain') }}</h4>
                    <h4 class="modal-title custome_domain_heading cname_heading verification_heading"
                        style="display: none;">{{ __('saas::saas.Configure your DNS') }}</h4>
                    <h4 class="modal-title custome_domain_heading activation_heading" style="display: none;">
                        {{ __('saas::saas.Activating your domain') }}</h4>
                    <h4 class="modal-title custome_domain_heading ready_to_go_heading" style="display: none;">
                        {{ __('saas::saas.You are ready to go!') }}</h4>



                    <button type="button" class="close " data-dismiss="modal">
                        <i class="ti-close "></i>
                    </button>
                </div>

                <div class="modal-body">
                        <div class="row custome_domain_row" id="domain_row">
                            <div class="col-lg-12 mb-25">
                                <div class="primary_input">
                                    <input class="primary_input_field" type="text" name="custom_domain"
                                        id="custom_domain" autocomplete="off" value="{{ 'https://'.$school->custom_domain }}">
                                    <label for="custom_domain">{{ __('Domain') }} <span>*</span> </label>
                                    <span class="focus-border"></span>
                                </div>
                                <span class="text-danger text-left" role="alert" id="custom_domain_error">
                                    
                                    @if ($errors->has('domain'))
                                        {{ $errors->first('domain') }}
                                    @endif
                                </span>
                            </div>

                            <div class="col-xl-12">
                                <p>{{  __('saas::saas.Enter the exact domain name you want people to see when they visit this space. It should be a subdomain') }} (https://your-company.com)</p>
                            </div>

                            <div class="col-lg-12 text-center">
                                <div class="d-flex justify-content-center pt_20">
                                    <button type="button" class="primary-btn semi_large2 fix-gr-bg goto" id="domain_goto"
                                        data-goto="cname" {{ $school->custom_domain ? '' : 'disabled' }}><i class="ti-check"></i>{{ __('saas::saas.next') }}
                                    </button> 
                                </div>
                            </div>

                        </div>

                        <div class="row custome_domain_row" id="cname_row" style="display: none;">
                            <div class="col-xl-12">
                                <p>{{ __('saas::saas.Add this CNAME record to your domain by visiting your DNS provider or registrar') }}.
                                </p>
                                <p class="alert alert-success"><strong>{{ __('saas::saas.CNAME') }}</strong>
                                    {{ preg_replace('#^https?://#', '', rtrim(url('/'), '/')) }}
                                </p>
                            </div>

                            <div class="col-lg-12 text-center">
                                <div class="d-flex justify-content-center pt_20">
                                    <button type="button" class="primary-btn semi_large2 goto fix-gr-bg mr-1"
                                        data-goto="domain">{{ __('saas::saas.previous') }} </button>
                                    <button type="button" class="primary-btn semi_large2 goto fix-gr-bg check_dns"
                                        id="cname_goto" data-goto="verification">{{ __('saas::saas.next') }} </button>
                                </div>
                            </div>
                        </div>

                        <div class="row custome_domain_row" id="verification_row" style="display: none;">
                            <div class="col-xl-12">

                                <p class="alert alert-success verification_checking"> <i class="fa fa-spinner fa-spin"></i>
                                    {{ __('saas::saas.Checking the DNS configuration') }}...</p>
                                <p class="verification_error alert alert-warning" style="display: none;"> 
                                    {{ __('saas::saas.The domain is missing a CNAME record pointing to') }}
                                   {{ preg_replace('#^https?://#', '', rtrim(url('/'), '/')) }} {{  __('saas::saas.or the update has not yet propagated') }}. </p>

                                <p class="verification_error">{{  __('saas::saas.We check the DNS configuration every 10 seconds') }}. <span
                                        class="btn btn-sm btn-link check_dns">{{  __('saas::saas.Refresh') }}</span></p>

                            </div>

                            <div class="col-lg-12 text-center">
                                <div class="d-flex justify-content-center pt_20">
                                    <button type="button" class="primary-btn semi_large2 goto fix-gr-bg mr-1"
                                        data-goto="cname">{{ __('saas::saas.previous') }} </button>
                                    <button type="button" class="primary-btn semi_large2 goto fix-gr-bg"
                                        id="verification_goto" data-goto="activation">
                                        <i class="ti-check"></i>{{ __('saas::saas.next') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Activating your domain --}}

                        <div class="row custome_domain_row" id="activation_row" style="display: none;">
                            <div class="col-xl-12">
                                <p class="alert alert-primary"> <i class="fa fa-spinner fa-spin"></i> {{ __('saas::saas.Activating your domain. It may take a few minutes') }}...</p>
                            </div>

                            <div class="col-lg-12 text-center">
                                <div class="d-flex justify-content-center pt_20">
                                    <button type="button" class="primary-btn semi_large2 goto fix-gr-bg mr-1 check_dns"
                                        data-goto="verification">{{ __('saas::saas.previous') }} </button>
                                    <button type="button" class="primary-btn semi_large2 goto fix-gr-bg"
                                        id="activation_goto" data-goto="ready_to_go"><i
                                            class="ti-check"></i>{{ __('saas::saas.next') }} </button>
                                </div>
                            </div>
                        </div>

                        {{-- You're ready to go! --}}
                        <div class="row custome_domain_row" id="ready_to_go_row" style="display: none;">
                            <div class="col-xl-12">
                                <p class="alert alert-primary"> {{  __('saas::saas.Your domain is now properly configured') }}.</p>
                            </div>

                            <div class="col-lg-12 text-center">
                                <div class="d-flex justify-content-center pt_20">
                                    <button type="button" class="primary-btn semi_large2 goto fix-gr-bg mr-1 check_dns"
                                        data-goto="verification">{{ __('saas::saas.previous') }} </button>
                                    <button type="button" class="primary-btn semi_large2 fix-gr-bg" id="done"><i
                                            class="ti-check"></i>{{ __('saas::saas.done') }} </button>
                                </div>
                            </div>
                        </div>


                </div>

            </div>
        </div>
    </div>
    <div class="modal fade admin-query" id="domain_remove_modal">
        <div class="modal-dialog modal_800px modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title custome_domain_heading domain_heading">{{ __('Remove domain') }}</h4>
                
                    <button type="button" class="close " data-dismiss="modal">
                        <i class="ti-close "></i>
                    </button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="{{ route('saas.custom-domain.remove') }}">
                        @csrf()
                        <div class="row custome_domain_row" id="domain_row">
                            
                            <div class="col-xl-12">
                                <p>{{  __('saas::saas.Are you sure about remove domain from school') }}?</p>
                            </div>

                            <div class="col-lg-12 text-center">
                                <div class="d-flex justify-content-center pt_20">
                                    <button type="submit" class="primary-btn semi_large2 fix-gr-bg "><i class="ti-check"></i>{{ __('saas::saas.remove') }} </button> 
                                </div>
                            </div>

                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('click', '.goto', function() {
            let target = $(this).data('goto');
            goto(target)

        });

        function goto(target) {
            $('.custome_domain_heading').hide();
            $('.custome_domain_row').hide();
            $('.' + target + '_heading').show();
            $('#' + target + '_row').show();
        }

        $(document).on('keyup', '#custom_domain', function() {

            let regEx =
                /^https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$/gm;
            let custom_domain = $(this).val();
            if (regEx.test(custom_domain)) {
                $('#custom_domain_error').html(
                            '<img src="{{ asset('public/backEnd/img/demo_wait.gif') }}" class="loader_img_style"/>')
                $.ajax({
                    url: "{{ route('saas.custom-domain.validate') }}",
                    data: {
                        custom_domain: custom_domain
                    },
                    method: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response) {
                            $('#custom_domain').removeClass('is-invalid');
                            $('#custom_domain_error').removeClass('text-danger').addClass('text-success').html('Domain valid');
                            $('#domain_goto').removeAttr('disabled');
                        }
                    },
                    error: function(error) {
                        $('#custom_domain').addClass('is-invalid');
                        $('#custom_domain_error').removeClass('text-success').addClass('text-danger')
                                    .html(error.responseJSON.errors.custom_domain[0]);
                        $('#domain_goto').attr('disabled', true);
                    }
                })
            } else {
                $('#custom_domain').addClass('is-invalid');
                $('#custom_domain_error').removeClass('text-success').addClass('text-danger')
                                    .html('');
                $('#domain_goto').attr('disabled', true);
            }

        });

        $(document).on('click', '.check_dns', function() {
            
            checkDns();
        });

        function activateDomain(){
            let custom_domain = $('#custom_domain').val();
            $.ajax({
                url: "{{ route('saas.custom-domain') }}",
                data: {
                    custom_domain: custom_domain
                },
                method: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        goto('ready_to_go');
                        setTimeout(function(){
                            window.location.href = '';
                        }, 2000)
                    }
                },
                error: function(error) {
                   
                }
            })
        }

        

        function checkDns(){
            let custom_domain = $('#custom_domain').val();
            $('.verification_error').hide();
            $('.verification_checking').show();
            $.ajax({
                url: "{{ route('saas.custom-domain.dns_check') }}",
                data: {
                    custom_domain: custom_domain
                },
                method: 'post',
                dataType: 'json',
                success: function(response) {
                    console.log(typeof response);
                    if (response) {
                        goto('activation');
                        activateDomain();
                    } else {
                        $('#verification_goto').attr('disabled', true);
                        $('.verification_error').show();
                        $('.verification_checking').hide();
                        setTimeout(function(){
                            checkDns();
                        }, 10000)
                    }
                },
                error: function(error) {
                    $('#verification_goto').attr('disabled', true);
                    $('.verification_error').show();
                    $('.verification_checking').hide();
                    setTimeout(function(){
                        checkDns();
                    }, 10000)
                }
            })
        }

        $(document).on('click', '#done', function(){
            $('#domain_configure_modal').modal('hide');
            window.location.href = '';
        });

    </script>
@endpush
