
<div class="container-fluid">
    {{ Form::open([
        'class' => 'form-horizontal',
        'files'=>true,
        'route' => 'zoom.upload_document',
        'method' => 'POST',
        'enctype' => 'multipart/form-data',
        
    ]) }}
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="meetingupload" value="{{ $upload_type }}">
            <input type="hidden" name="meeting_id" value="{{ $meeting->id }}">
            <input type="hidden" name="course_id" value="{{ @$course_id }}">
            <div class="row mt-25">
                <div class="col-lg-12">
                    <div class="primary_input">
                        <label class="paddinfTop"> @lang('zoom::zoom.link')</label>

                        <input class="primary_input_field form-control" type="text" name="link"
                            value="{{ $meeting->vedio_link != '' ? $meeting->vedio_link : '' }}">

                        <span class=" text-danger" role="alert" id="amount_error">

                        </span>
                    </div>
                </div>
            </div>
            <div class="row mt-25">
              
                <div class="col-lg-12">
                    <div class="primary_input">
                        <div class="primary_file_uploader">
                            <input
                                class="primary_input_field form-control {{ $errors->has('attached_file') ? ' is-invalid' : '' }}"
                                readonly="true" type="text"
                                placeholder="{{ isset($meeting->local_video) && @$meeting->local_video != '' ? getFilePath3(@$meeting->local_video) : 'Attach File ' }}"
                                id="placeholderInputModal">

                            <button class="" type="button">
                                <label class="primary-btn small fix-gr-bg"
                                    for="browseVedioFile">{{ __('common.browse') }}</label>
                                <input type="file" class="d-none" name="video" id="browseVedioFile">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="col-lg-12 text-center mt-40">
            <div class="mt-40 d-flex justify-content-between">
                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>

                <button class="primary-btn fix-gr-bg submit" type="submit">@lang('common.save')</button>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>

<script type="text/javascript">
    var fileInput = document.getElementById("browseVedioFile");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);
        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderInputModal").placeholder = fileName;
            console.log(document.getElementById("placeholderInputModal").placeholder);
        }
    }    
</script>
