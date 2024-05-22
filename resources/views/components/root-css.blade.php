
<style>
    /* for toastr dynamic start*/
    .toast-success {
        background-color: !important;
    }

    .toast-message {
        color: ;
    }

    .toast-title {
        color: ;

    }

    .toast {
        color: ;
    }

    .toast-error {
        background-color: !important;
    }

    .toast-warning {
        background-color: !important;
    }
</style>
<style>
    :root {
        --base_font : {{ in_array(session()->get('locale', Config::get('app.locale')), ['ar']) ?  'Cairo,' : ''}}Poppins, sans-serif;
        --box_shadow : {{ $color_theme->box_shadow ? 'var(--box_shadow)':'none' }};
        @foreach($color_theme->colors as $color)
        --{{ $color->name}}: {{ $color->pivot->value }};
                    @if(in_array($color->name, ['success', 'danger']))
        --{{ $color->name}}_with_opacity: {{ $color->pivot->value }}23;
                @endif
            @endforeach
        }
</style>