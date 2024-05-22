@push('css')   

<link href="{{ asset('public/backEnd/css/filepond/filepond-plugin-image-preview.min.css')}}"
    rel="stylesheet" />
<link href="{{ asset('public/backEnd/css/filepond/filepond.min.css')}}" rel="stylesheet" />
<style>
    /**
 * FilePond Custom Styles
 */
    .filepond--drop-label {
        color: #4c4e53;
    }

    .filepond--label-action {
        text-decoration-color: #babdc0;
    }

    .filepond--panel-root {
        border-radius: 2em;
        background-color: #edf0f4;
        height: 1em;
    }

    .filepond--item-panel {
        background-color: #595e68;
    }

    .filepond--drip-blob {
        background-color: #7f8a9a;
    }
</style>
@endpush
<input type="file" class="filepond" id="filepond_attachment" name="files[]" multiple data-max-file-size="{{ generalSetting()->file_size*1024 }}" data-max-files="30" />

@push('scripts')
    <script src="{{ asset('public/backEnd/js/filepond/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/js/filepond/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/js/filepond/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/js/filepond/filepond-plugin-file-encode.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/js/filepond/filepond.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            FilePond.registerPlugin(

                // encodes the file as base64 data
                FilePondPluginFileEncode,

                // validates the size of the file
                FilePondPluginFileValidateSize,

                // corrects mobile image orientation
                FilePondPluginImageExifOrientation,

                // previews dropped images
                FilePondPluginImagePreview
            );

            // Select the file input and use create() to turn it into a pond
            FilePond.create(document.getElementById('filepond_attachment'));
        });
    </script>
@endpush
