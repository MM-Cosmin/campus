{{ Form::open([
    'class' => 'form-horizontal',
    'files' => true,
    'route' => 'g-meet.upload-document',
    'method' => 'POST',
    'enctype' => 'multipart/form-data',
    'name' => 'document_upload',
]) }}

<div class="row">
    <div class="col-lg-12">
        <input type="hidden" name="id" value="{{ $meetingOrClass->id }}">
        <input type="hidden" name="type" value="{{ $type }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="primary_input">

                    <input class="primary_input_field read-only-input form-control" type="text" name="video_link"
                        value="{{ $meetingOrClass->video_link }}" id="video_link">
                    <label> @lang('gmeet::gmeet.video_url')</label>
                    

                    <span class=" text-danger" role="alert" id="amount_error">

                    </span>

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-30">
        <div class="row no-gutters input-right-icon">
            <div class="col">
                <div class="primary_input">
                    <input class="primary_input_field" type="text" id="placeholderInput"
                        placeholder="{{ isset($meetingOrClass->local_video) && @$meetingOrClass->local_video != '' ? getFilePath3(@$meetingOrClass->local_video) : 'Attach File' }}"
                        disabled>
                    
                </div>
            </div>
            <div class="col-auto">
                <button class="primary-btn-small-input" type="button">
                    <label class="primary-btn small fix-gr-bg" for="browseFile">
                        @lang('common.browse')</label>
                    <input type="file" class="d-none" name="local_video" id="browseFile">
                </button>
            </div>
        </div>
    </div>


    <div class="col-lg-12 text-center mt-40">
        <div class="d-flex justify-content-between">
            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')
            </button>

            <button class="primary-btn fix-gr-bg submit" type="submit">@lang('common.save')
            </button>
        </div>
    </div>
</div>

{{ Form::close() }}
<script>
      var fileInput = document.getElementById("browseFile");
      if (fileInput) {
          fileInput.addEventListener("change", showFileName);
  
          function showFileName(event) {
              var fileInput = event.srcElement;
              var fileName = fileInput.files[0].name;
              document.getElementById("placeholderInput").placeholder = fileName;
          }
      }
      var fileInp = document.getElementById("browseFil");
      if (fileInp) {
          fileInp.addEventListener("change", showFileName);
  
          function showFileName(event) {
              var fileInp = event.srcElement;
              var fileName = fileInp.files[0].name;
              document.getElementById("placeholderIn").placeholder = fileName;
          }
      }
</script>

