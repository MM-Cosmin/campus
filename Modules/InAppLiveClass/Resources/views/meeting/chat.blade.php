<div class="agora-chat d-flex flex-column h-100">

    <div id="chatView" class="agora-chat-box pb-30">

    </div>

    @if($chat)
        <div class="agora-chat-footer mt-15 py-15 px-15 border-top border-gray200 d-flex align-items-center ">

            <div class="flex-grow-1">
            <textarea name="message" id="messageInput" class="form-control " rows="3"
                      placeholder="{{ trans('common.Type Your Message') }}"></textarea>
            </div>


            <button type="submit" id="sendMessage" class="send-message-btn btn btn-primary p-0 rounded-circle ml-15">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M1.00001 8.23874C1.00001 6.30847 19.5691 -0.903676 21.9511 1.47647C24.3281 3.85165 17.0188 22.4279 15.1021 22.4279C12.868 22.4279 11.5555 13.9178 11.0264 12.3929C9.50714 11.8561 1.00001 10.4811 1.00001 8.23874Z"
                        stroke="currentColor" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    @else
        <div
            class="no-result default-no-result d-flex align-items-center justify-content-center flex-column w-100 h-100 pb-40">
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h3 class="text-dark-blue font-16 mb-0">{{ trans('chat.chat_not_active') }}</h3>
                <p class=" text-center text-gray font-14">{{ trans('chat.chat_not_active_hint') }}</p>
            </div>
        </div>
    @endif
</div>


<script>
    var rtmToken = '{{ $rtmToken }}';
</script>

<script src="{{asset('public/modules/inappliveclass/script.js')}}"></script>
