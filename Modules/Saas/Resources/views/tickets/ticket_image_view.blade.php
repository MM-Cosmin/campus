<div class="container-fluid">
    <div class="student-details">
        <div class="student-meta-box">
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        
                        <div class="file-preview" style="display: block">
                            @php
                                $std_file = $ticketOrComment->file;
                                $ext = strtolower(str_replace('"]', '', pathinfo($std_file, PATHINFO_EXTENSION)));
                                $attached_file = str_replace('"]', '', $std_file);
                                $attached_file = str_replace('["', '', $attached_file);
                                $preview_files = ['jpg', 'jpeg', 'png', 'heic', 'mp4', 'mov', 'mp3', 'mp4', 'pdf'];                                
                            @endphp
                           
                            @if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'heic')
                                <img class="img-responsive mt-20" style="width: 100%; height:auto"
                                    src="{{ asset($attached_file) }}">
                            @elseif($ext == 'mp4' || $ext == 'mov')
                                <video class="mt-20 video_play" width="100%" controls>
                                    <source src="{{ asset($attached_file) }}" type="video/mp4">
                                    <source src="mov_bbb.ogg" type="video/ogg">
                                    Your browser does not support HTML video.
                                </video>
                            @elseif($ext == 'mp3')
                                <audio class="mt-20 audio_play" controls style="width: 100%">
                                    <source src="{{ asset($attached_file) }}" type="audio/ogg">
                                    <source src="horse.mp3" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @elseif($ext == 'pdf')
                                {{-- <embed src="{{asset($attached_file)}}#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="600px" /> --}}

                                <object data='{{ asset($attached_file) }}' type="application/pdf" width="100%"
                                    height="800">

                                    <iframe src='{{ asset($attached_file) }}' width="100%"height="800">
                                        <p>This browser does not support PDF!</p>
                                    </iframe>

                                </object>
                            @endif
                            @if (!in_array($ext, $preview_files))
                                {{-- <h3 class="text-warning">{{$ext}} File Not Previewable</h3> --}}
                                <div class="alert alert-warning">
                                    {{ $ext }} File Not Previewable</a>.
                                </div>
                            @endif
                            <div class="mt-40 d-flex justify-content-between">
                                {{-- <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button> --}}
                                @php
                                    $set_filename = time() . '_' . $std_file;
                                @endphp
                                <a class="primary-btn tr-bg" download="{{ $set_filename }}"
                                    href="{{ asset($attached_file) }}"> <span class="pl ti-download">
                                        @lang('common.download')</span></a>
                                {{-- {{route('download-uploaded-content-admin',$uploadedContent->id)}} --}}
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $('#show_files').on('click', function() {
        $('.file-preview').show();
        $('.content_info').hide();
    });
</script>
<script type="text/javascript">
    jQuery('.has-modal').on('hidden.bs.modal', function(e) {

        $('.video_play').get(0).play();
        $('.video_play').trigger('pause');

        $('.audio_play').get(0).play();
        $('.audio_play').trigger('pause');
    });
</script>
