@extends(config('pagebuilder.site_layout'), ['edit' => false])
<title>{{ __('edulia.blog_list') }} </title>
@section(config('pagebuilder.site_section'))
{{headerContent()}}
@php
    $gs = generalSetting();
@endphp
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>
                            {{ __('edulia.blog_list') }} 
                            <span>
                                <a href="{{ url('/') }}">{{ __('edulia.home') }}</a> /{{ __('edulia.blog_list') }}
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section_padding blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12">
                    <div class="blog_card">
                        <div class="row">
                            <div class="col-lg-8 col-md-7" id="dynamicLoadMoreData">
                                @foreach ($blogs->paginate(5) as $item)
                                    <div class="blog_card_wrapper searchBlogContent">
                                        <div class="blog_card_wrapper_img">
                                            <img src="{{ asset($item->image) }}" alt="{{ $item->news_title }}">
                                        </div>
                                        <div class="blog_card_wrapper_content">
                                            <a href="{{ route('frontend.news-details', $item->id) }}" class='blog_card_wrapper_content_title'>{{ $item->news_title }}</a>
                                            <p class="blog_card_wrapper_content_meta">{{ dateConvert($item->publish_date) }} / {{$item->category->category_name}}</p>
                                            <p>{!! mb_strimwidth($item->news_body, 0, 200, "...") !!}</p>
                                            <a href="{{ route('frontend.news-details', $item->id) }}">{{__('edulia.read_more')}}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($gs->blog_search == 1 || $gs->recent_blog == 1)
                                <div class="col-lg-4 col-md-5">
                                    <div class="blog_widget">
                                        @if ($gs->blog_search == 1)
                                            <div class="blog_widget_search">
                                                <label for="#" class='blog_widget_search_icon'><i class="far fa-search"></i></label>
                                                <input type="text" class="input-control-input" placeholder='{{__('edulia.search')}}' id="blogallcontentsearch">
                                            </div>
                                        @endif
                                        @if ($gs->recent_blog == 1)
                                            <div class="blog_widget_item">
                                                <h5>{{__('edulia.recent_blog')}}</h5>
                                                @foreach($blogs->orderBy('id', 'desc')->paginate(3) as $blog)
                                                    <div class="blog_widget_item_recentnews">
                                                        <a href="{{ route('frontend.news-details', $blog->id) }}">{{ $blog->news_title }}</a>
                                                        <p>{{ dateConvert($blog->publish_date) }} / {{$blog->category->category_name}}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <div class="load_more section_padding_top">
                                        <a href="#" class="site_btn load_more_blog_btn">{{__('edulia.load_more')}}</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{{footerContent()}}
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $("#blogallcontentsearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".searchBlogContent").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $(document).on('click', '.load_more_blog_btn', function (e) {
            e.preventDefault();
            var totalBlog = $('.searchBlogContent').length;
            $.ajax({
                url: "{{route('frontend.load-more-blog-list')}}",
                method: "POST",
                data: {
                    skip: totalBlog,
                    _token: "{{csrf_token()}}",
                },
                success: function (response) {
                    if(totalBlog == response.total_data){
                        $('.load_more_blog_btn').hide();
                    }else{
                        $('#dynamicLoadMoreData').append(response.html);
                    }
                }
            })
        })
    </script>
@endpushonce
