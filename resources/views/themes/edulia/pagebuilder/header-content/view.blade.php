@pushonce(config('pagebuilder.site_style_var'))
<link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/packages/zeynep/zeynep.min.css')}}">
<link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/themify/themify-icons.min.css')}}">
@endpushonce
@php
    $generalSetting = generalSetting();
    $is_registration_permission = false;
    if (moduleStatusCheck('ParentRegistration')) {
        $reg_setting = Modules\ParentRegistration\Entities\SmRegistrationSetting::where('school_id', $generalSetting->school_id)->first();
        $is_registration_position = $reg_setting ? $reg_setting->position : null;
        $is_registration_permission = $reg_setting ? $reg_setting->registration_permission == 1 : false;
    }
@endphp
<header class="heading">
    <div class="heading_sub">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <nav class="heading_sub_left">
                        <ul>
                            @if (!empty(pagesetting('header-left-menus')))
                                @foreach(pagesetting('header-left-menus') as $rightMenu)
                                    <li>
                                        <a href="{{ gv($rightMenu, 'header-left-menu-icon-url') }}">
                                            <i class="{{gv($rightMenu, 'header-left-menu-icon-class')}}"></i>
                                            {{ gv($rightMenu, 'header-left-menu-label') }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </nav>
                </div>
                <div class="col-md-7 text-end">
                    <nav class="heading_sub_right">
                        <ul class='social-links'>
                            @if (!empty(pagesetting('header-right-menus')))
                                @foreach (pagesetting('header-right-menus') as $icon)
                                    <li class='social-links-list'>
                                        <a href="{{ gv($icon, 'header-right-icon-url') }}" target='_blank' class='social-links-list-link'>
                                            <i class="{{ gv($icon, 'header-right-icon-class') }}"></i>
                                            {{ gv($icon, 'header-right-menu-label') }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <ul>
                            <li>
                                @if (!auth()->check())
                                    <a href="{{url('/login')}}">
                                        <i class="far fa-user"></i>
                                        {{ __('edulia.login')}}
                                    </a>
                                @else
                                    <a href="{{url('/admin-dashboard')}}">
                                        <i class="far fa-user"></i>
                                        {{ __('edulia.dashboard')}}
                                    </a>
                                @endif
                            </li>
                            @if (moduleStatusCheck('ParentRegistration') && $is_registration_permission && $is_registration_permission == 1)
                                <li>
                                    <a href="{{ route('parentregistration/registration', $reg_setting->url) }}">
                                        <i class="far">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16.5"
                                                viewBox="0 0 15 16.5">
                                                <g id="archive-book" transform="translate(-2.25 -1.25)">
                                                    <path id="Path_1912" data-name="Path 1912"
                                                        d="M16.5,5.75v7.5c0,2.25-1.125,3.75-3.75,3.75h-6C4.125,17,3,15.5,3,13.25V5.75C3,3.5,4.125,2,6.75,2h6C15.375,2,16.5,3.5,16.5,5.75Z"
                                                        transform="translate(0)" fill="none" stroke="#fff"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-width="1.5" />
                                                    <path id="Path_1913" data-name="Path 1913"
                                                        d="M13.758,2V7.9a.377.377,0,0,1-.631.278L11.385,6.575a.372.372,0,0,0-.511,0L9.131,8.182A.377.377,0,0,1,8.5,7.9V2Z"
                                                        transform="translate(-1.379)" fill="none" stroke="#fff"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-width="1.5" />
                                                    <path id="Path_1914" data-name="Path 1914" d="M13.25,14h3.192"
                                                        transform="translate(-2.566 -3)" fill="none" stroke="#fff"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-width="1.5" />
                                                    <path id="Path_1915" data-name="Path 1915" d="M9,18h6.385"
                                                        transform="translate(-1.506 -4.005)" fill="none" stroke="#fff"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-width="1.5" />
                                                </g>
                                            </svg>
                                        </i>
                                        {{ __('edulia.student_registration')}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <div class="heading_mobile">
        <div>
            <div class="heading_mobile_thum"><i class="far fa-bars"></i></div>
        </div>
        <div class='text-center'>
            <a href='{{url('/')}}' class="heading_logo">
                <img src="{{pagesetting('header_menu_image') ? pagesetting('header_menu_image')[0]['thumbnail'] : defaultLogo($generalSetting->logo) }}" alt="">
            </a>
        </div>
        <form action="#" class='heading_main_search m_s'>
            <div class="input-control">
                <label for="search" class="input-control-icon"><i class="far fa-search"></i></label>
                <input type="search" name='search' id='search' class="input-control-input"
                    placeholder='Search for course, skills and Videos' required>
            </div>
        </form>
    </div>

    <div class="heading_main">
        <div class="container">
            <div class="row">
                <div class="col-md-2 my-auto">
                    <a href="{{url('/')}}" class="heading_main_logo mobile-menu-left">
                        <img src="{{pagesetting('header_menu_image') ? pagesetting('header_menu_image')[0]['thumbnail'] : defaultLogo($generalSetting->logo) }}" alt="">
                    </a>
                </div>
                <div class="col-md-7">
                    <x-header-content-menu></x-header-content-menu>
                </div>
                @if (!empty(pagesetting('header_menu_search')) && pagesetting('header_menu_search') == 1)
                    <div class="col-md-3 text-end mobile-none">
                        <form action='#' methods='GET' class="heading_main_search">
                            <div class="input-control">
                                <input type="search" class="input-control-input" placeholder='{{pagesetting('header_menu_search_placeholder')}}' required>
                                <button type='submit' class="input-control-icon"><i class="far fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>
<div class="clear_head"></div>


<!-- mobile menu -->
<div class="heading_mobile_menu zeynep">
    <x-header-content-mobile-menu></x-header-content-mobile-menu>
</div>
<!-- mobile menu -->


@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/'.activeTheme().'/packages/zeynep/zeynep.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            // MOBILE MENU ACTIVE JS
            var zeynep = $('.zeynep').zeynep({})
            $('.heading_mobile_thum').on('click', function() {
                zeynep.open()
                $('.bg-shade').fadeIn();
            })
            $('.bg-shade').on('click', function() {
                zeynep.close()
                $('.bg-shade').fadeOut();
            })

            $('[data-mobile-search]').on('click', function(e) {
                e.stopPropagation();
                $('.m_s').fadeToggle('fast')
            });
            $(document).on('click', function(e) {
                if (!$(e.target).is('.m_s *')) {
                    $('.m_s').fadeOut('fast')
                }
            })
        });
    </script>
@endpushonce