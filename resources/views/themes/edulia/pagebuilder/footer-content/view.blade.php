@php
    $generalSetting = generalSetting();
@endphp
<div class="footer-item">
    @if(pagesetting('footer_menu_image'))
    <a href='{{ url('/') }}' class="footer-item-logo">
        <img src="{{ file_exists(pagesetting('footer_menu_image')[0]['thumbnail']) ? pagesetting('footer_menu_image')[0]['thumbnail'] : defaultLogo($generalSetting->logo) }}"
            alt="">
    </a>
    @endif 
    <p style="color: {{ pagesetting('footer-content-bg-color') }}">
        {!! pagesetting('footer-right-content-text') !!}
    </p>
</div>
