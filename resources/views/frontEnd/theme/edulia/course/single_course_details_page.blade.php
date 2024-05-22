@extends(config('pagebuilder.site_layout'),['edit' => false ])
@section(config('pagebuilder.site_section'))
{{headerContent()}}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{__('edulia.course_details')}}<span><a href="{{url('/')}}">{{__('edulia.home')}}</a> / {{__('edulia.courses_details')}}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_padding course course_details_page mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="course_sidebar">
                        <div class="course_sidebar_thumbnail">
                            <img src="{{$course->image != ""? asset($course->image) : '../img/client/common-banner1.jpg'}}" alt="{{$course->title}}">
                        </div>
                        @if ($course->courseCategory->category_name)
                            <div class="course_sidebar_content">
                                <h5>{{ __('edulia.category').' '.':'}}{{$course->courseCategory->category_name}}</h5>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-7 col-lg-8 col-md-12">
                    <div class="course_details">
                        <div class="course_details_mentor">
                            <div class="course_details_mentor_head">
                                <div class="course_details_mentor_title">
                                    <h5>{{$course->title}}</h5>
                                </div>
                            </div>
                            <div class="course_details_mentor_wrapper">
                                {!! $course->overview !!}
                            </div>
                        </div>
                        <div class="course_details_preview_img">
                            <img src="{{$course->image != ""? asset($course->image) : '../img/client/common-banner1.jpg'}}" alt="{{$course->title}}">
                        </div>
                        <nav class="course_details_menu">
                            <ul>
                                @if ($course->outline)
                                    <li class='course_details_menu_list'>
                                        <a href="#" class='course_details_menu_list_link active about-filter' data-name='overview'>
                                            {{ __('edulia.overview') }}
                                        </a>
                                    </li>
                                @endif
                                @if ($course->prerequisites)
                                    <li class='course_details_menu_list'>
                                        <a href="#" class='course_details_menu_list_link about-filter' data-name='curriculum'>
                                            {{ __('edulia.curriculum') }} 
                                        </a>
                                    </li>
                                @endif
                                @if ($course->resources)
                                    <li class='course_details_menu_list'>
                                        <a href="#" class='course_details_menu_list_link about-filter' data-name='instructors'>
                                            {{ __('edulia.instructors') }}
                                        </a>
                                    </li>
                                @endif
                                @if ($course->stats)
                                    <li class='course_details_menu_list'>
                                        <a href="#" class='course_details_menu_list_link about-filter' data-name='reviews'>
                                            {{ __('edulia.reviews') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                        <div class="course_details_abouts">
                            <div class="course_details_abouts_item overview">
                                {!! $course->outline !!}
                            </div>
                            <div class="course_details_abouts_item curriculum">  
                                {!! $course->prerequisites !!}
                            </div>
                            <div class="course_details_abouts_item instructors">
                                {!! $course->resources !!}
                            </div>
                            <div class="course_details_abouts_item reviews">
                                {!! $course->stats !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        // ABOUT FILTER BTN
        let filterBtn = document.querySelectorAll('.about-filter');
        let aboutInfo = document.querySelectorAll('.course_details_abouts_item');
        filterBtn.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let value = e.target.dataset.name;
                aboutInfo.forEach(function(item) {
                    if (item.classList.contains(value)) {
                        item.style.display = 'block'
                    } else {
                        item.style.display = 'none'
                    }
                })
            })
        })
    </script>
@endpushonce