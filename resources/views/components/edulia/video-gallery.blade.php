<div class="video-gallery">
    <div class="row">
        @if ($videoGalleries->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
            <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
                    href="{{ URL::to('/video-gallery') }}">@lang('edulia.video_gallery')</a></p>
        @else
            @foreach ($videoGalleries as $videoGallery)
                @php
                    $variable = substr($videoGallery->video_link, 32, 11);
                @endphp
                <div class="col-lg-{{ $column }}
                
                @if ($column == '12' )
                    col-md-12
                    @elseif ($column == '6')
                    col-md-12
                    @elseif ($column == '4')
                    col-md-6 col-sm-12
                    @elseif ($column == '3')
                    col-md-4 col-sm-6
                    @elseif ($column == '2')
                    col-md-3 col-sm-4 col-6
                    @elseif ($column == '1')
                    col-md-2 col-sm-3 col-6
                @endif
            ">
                    <div class="single-video-item">
                        <a href='https://www.youtube.com/watch?v={{ $variable }}' class="gallery_item video">
                            <div class="gallery_item_img">
                                <img src="https://img.youtube.com/vi/{{ $variable }}/maxresdefault.jpg"
                                    alt="video thumbnail">
                            </div>
                            <div class="gallery_item_inner">
                                <h4>{{ mb_strimwidth($videoGallery->name, 0, 50, '...') }}</h4>
                                <p>{{ mb_strimwidth($videoGallery->description, 0, 150, '...') }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
