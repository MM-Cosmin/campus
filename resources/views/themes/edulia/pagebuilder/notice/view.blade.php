<section class="section_padding noticeboard">
    <div class="container p-0">
        <div class="row">
            <div class="offset-lg-2 col-lg-10">
                <div class="noticeboard_inner">
                    <div class="noticeboard_inner_head">
                        <h5>{{ pagesetting('notice_heading') }}</h5>
                    </div>
                    <div class='noticeboard_inner_wrapper'>
                        <x-notice :count="pagesetting('notice_count')" :btn="pagesetting('view_detail_btn')"> </x-notice>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
