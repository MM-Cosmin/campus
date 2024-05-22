<section class="hero_area_slider  owl-carousel">
    @if ($homeSliders->isEmpty())
        <div class="hero_area" id='slider-1'>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 p-0">
                        <div class="hero_area_inner">
                            <img src="{{ asset($homeSlider->image) }}" alt="hero slider">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @foreach ($homeSliders as $homeSlider)
            <div class="hero_area" id='slider-1'>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <div class="hero_area_inner">
                                <a href="{{$homeSlider->link ?? '#'}}" target="__blank">
                                    <img src="{{ asset($homeSlider->image) }}" alt="hero slider">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</section>
