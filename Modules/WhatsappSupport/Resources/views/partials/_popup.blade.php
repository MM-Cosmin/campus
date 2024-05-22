@php
    $agent = new \Jenssegers\Agent\Agent();
    $agents = Modules\WhatsappSupport\Entities\Agents::/* take(5)-> */get();
    $ws_setting = Modules\WhatsappSupport\Entities\Settings::first();
@endphp
@if ($ws_setting->showing_page == 'all')
    @if (
        ($ws_setting->availability == 'mobile' && $agent->isMobile()) ||
            ($ws_setting->availability == 'desktop' && $agent->isDesktop()) ||
            $ws_setting->availability == 'both')
        @if ($ws_setting->disable_for_admin_panel != 1)
            <div class="whatsApp_icon" style="background-color: {{ $ws_setting->color }}">
                <i class="fab fa-whatsapp"></i>
            </div>
            <div class="whats_app_popup whatsApp_popup_position {{ $ws_setting->open_popup ? 'active' : '' }}"
                id="whatsapp_support_container">
                <span class="whats_app_popup_close d-flex align-items-center justify-content-center" style="background-color: {{ $ws_setting->color }}">
                    <i class="fas fa-times"></i>
                </span>
                <div class="whats_app_popup_body">
                    @if ($ws_setting->layout == 2)
                        <div class="whats_app_popup_thumb">
                            @if ($ws_setting->bubble_logo == null)
                                <img src="{{ asset('public/whatsapp-support/demo-avatar.jpg') }}"
                                    alt="">
                            @else
                                <img src="{{ asset($ws_setting->bubble_logo) }}" alt="">
                            @endif
                        </div>
                    @endif
                    <div class="whats_app_popup_msgs">
                        <div class="whats_app_popup_head" style="background-color: {{ $ws_setting->color }}">
                            <div class="whats_app_popup_thumb">
                                @if ($ws_setting->layout == 1)
                                    {{-- <div class="whats_app_popup_thumb mb_15"> --}}
                                        @if ($ws_setting->bubble_logo == null)
                                            <img src="{{ asset('public/whatsapp-support/demo-avatar.jpg') }}"
                                                alt="">
                                        @else
                                            <img src="{{ asset($ws_setting->bubble_logo) }}" alt="">
                                        @endif
                                    {{-- </div> --}}
                                @endif
                            </div>
                            <p class="intro_text">{{ $ws_setting->intro_text }}</p>
                        </div>
                        <div class="whats_app_popup_text">
                            <img src="{{ asset('public/whatsapp-support/hand.svg') }}" alt="">
                            <p> {{ $ws_setting->welcome_message }}</p>
                        </div>
                        @if ($ws_setting->agent_type != 'single')
                            <div class="whats_app_members">
                                @foreach ($agents as $agent_list)
                                    @if ($agent_list->isAvailable() || (!$agent_list->isAvailable() && $ws_setting->show_unavailable_agent))
                                        <form action="{{ route('whatsapp-support.message.send') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="agent_number"
                                                value="{{ $agent_list->number }}">
                                            <input type="hidden" name="browser" value="{{ $agent->browser() }}">
                                            <input type="hidden" name="os" value="{{ $agent->platform() }}">
                                            <input type="hidden" name="device_type"
                                                value="{{ $agent->isMobile() ? 'Mobile' : 'Desktop' }}">
                                            <div class="single_group_member">
                                                <div class="single_group_member_inner">
                                                    <div class="thumb">
                                                        @if (is_null($agent_list->avatar))
                                                            <img src="{{ asset('public/whatsapp-support/demo-avatar.jpg') }}"
                                                                alt="">
                                                        @else
                                                            <img src="{{ asset($agent_list->avatar) }}" alt="">
                                                        @endif
                                                        @if ($agent_list->isAvailable())
                                                            <span class="active_badge"></span>
                                                        @else
                                                            <span class="inactive_badge"></span>
                                                        @endif
                                                    </div>
                                                    <div class="group_member_meta">
                                                        <h4 class="font_16">{{ ucfirst($agent_list->name) }}</h4>
                                                        <span
                                                            class="mb-1 designation_color">{{ ucfirst($agent_list->designation) }}</span>
                                                        @if ($agent_list->isAvailable())
                                                            <p>Available</p>
                                                        @else
                                                            <p>Unavailable</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="whats_app_popup_input">
                                                    <div class="input-group primary_input_coupon align-items-center">
                                                        <input type="text" name="message" class="primary_input_field"
                                                            placeholder="Type message..."
                                                            aria-label="Recipient's username"
                                                            aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <button class="btn" type="submit"> <svg
                                                                    class="wws-popup__send-btn" version="1.1"
                                                                    id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                                                    y="0px" viewBox="0 0 40 40"
                                                                    style="enable-background:new 0 0 40 40;"
                                                                    xml:space="preserve">
                                                                    <style type="text/css">
                                                                        .wws-lau00001 {
                                                                            fill: {{ $ws_setting->color }}80;
                                                                        }

                                                                        .wws-lau00002 {
                                                                            fill: {{ $ws_setting->color }};
                                                                        }
                                                                    </style>
                                                                    <path id="path0_fill" class="wws-lau00001"
                                                                        d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
                                                                    <path id="path0_fill_1_" class="wws-lau00002"
                                                                        d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @if ($ws_setting->isSingle())
                    <form action="{{ route('whatsapp-support.message.send') }}" method="post">
                        @csrf
                        <input type="hidden" name="browser" value="{{ $agent->browser() }}">
                        <input type="hidden" name="os" value="{{ $agent->platform() }}">
                        <input type="hidden" name="device_type"
                            value="{{ $agent->isMobile() ? 'Mobile' : 'Desktop' }}">
                        <div class="whats_app_popup_input">
                            <div class="input-group primary_input_coupon align-items-center">
                                <input type="text" name="message" class="primary_input_field"
                                    placeholder="Type message...">
                                <div class="input-group-append">
                                    <button class="btn " type="submit">
                                        <svg class="wws-popup__send-btn" version="1.1" id="Layer_1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;"
                                            xml:space="preserve">
                                            <style type="text/css">
                                                .wws-lau00001 {
                                                    fill: {{ $ws_setting->color }}80;
                                                }

                                                .wws-lau00002 {
                                                    fill: {{ $ws_setting->color }};
                                                }
                                            </style>
                                            <path id="path0_fill" class="wws-lau00001"
                                                d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
                                            <path id="path0_fill_1_" class="wws-lau00002"
                                                d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        @endif
    @endif
@elseif(url()->current() == $ws_setting->homepage_url)
    @if (
        ($ws_setting->availability == 'mobile' && $agent->isMobile()) ||
            ($ws_setting->availability == 'desktop' && $agent->isDesktop()) ||
            $ws_setting->availability == 'both')
        @if ($ws_setting->disable_for_admin_panel != 1)
            <div class="whatsApp_icon" style="background-color: {{ $ws_setting->color }}">
                <i class="fab fa-whatsapp"></i>
            </div>

            <div class="whats_app_popup whatsApp_popup_position {{ $ws_setting->open_popup ? 'active' : '' }}"
                id="whatsapp_support_container">
                <span class="whats_app_popup_close" style="background-color: {{ $ws_setting->color }}">
                    <i class="fas fa-times"></i>
                </span>
                <div class="whats_app_popup_body">
                    @if ($ws_setting->layout == 2)
                        <div class="whats_app_popup_thumb">
                            @if ($ws_setting->bubble_logo == null)
                                <img src="{{ asset('public/whatsapp-support/demo-avatar.jpg') }}"
                                    alt="">
                            @else
                                <img src="{{ asset($ws_setting->bubble_logo) }}" alt="">
                            @endif
                        </div>
                    @endif
                    <div class="whats_app_popup_msgs">
                        <div class="whats_app_popup_head" style="background-color: {{ $ws_setting->color }}">
                            <div class="whats_app_popup_thumb mb_15">
                                @if ($ws_setting->layout == 1)
                                    <div class="whats_app_popup_thumb mb_15">
                                        @if ($ws_setting->bubble_logo == null)
                                            <img src="{{ asset('public/whatsapp-support/demo-avatar.jpg') }}"
                                                alt="">
                                        @else
                                            <img src="{{ asset($ws_setting->bubble_logo) }}" alt="">
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <p class="intro_text">{{ $ws_setting->intro_text }}</p>
                        </div>
                        <div class="whats_app_popup_text">
                            <img src="{{ asset('public/whatsapp-support/hand.svg') }}" alt="">
                            <p> {{ $ws_setting->welcome_message }}</p>
                        </div>
                        @if ($ws_setting->agent_type != 'single')
                            <div class="whats_app_members">
                                @foreach ($agents as $agent_list)
                                    @if ($agent_list->isAvailable() || (!$agent_list->isAvailable() && $ws_setting->show_unavailable_agent))
                                        <form action="{{ route('whatsapp-support.message.send') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="agent_number"
                                                value="{{ $agent_list->number }}">
                                            <input type="hidden" name="browser" value="{{ $agent->browser() }}">
                                            <input type="hidden" name="os" value="{{ $agent->platform() }}">
                                            <input type="hidden" name="device_type"
                                                value="{{ $agent->isMobile() ? 'Mobile' : 'Desktop' }}">
                                            <div class="single_group_member">
                                                <div class="single_group_member_inner">
                                                    <div class="thumb">
                                                        @if (is_null($agent_list->avatar))
                                                            <img src="{{ asset('public/whatsapp-support/demo-avatar.jpg') }}"
                                                                alt="">
                                                        @else
                                                            <img src="{{ asset($agent_list->avatar) }}"
                                                                alt="">
                                                        @endif

                                                        @if ($agent_list->isAvailable())
                                                            <span class="active_badge"></span>
                                                        @else
                                                            <span class="inactive_badge"></span>
                                                        @endif
                                                    </div>
                                                    <div class="group_member_meta">
                                                        <h4 class="font_16">{{ ucfirst($agent_list->name) }}</h4>
                                                        <span
                                                            class="mb-1 designation_color">{{ ucfirst($agent_list->designation) }}</span>
                                                        @if ($agent_list->isAvailable())
                                                            <p>Available</p>
                                                        @else
                                                            <p>Unavailable</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="whats_app_popup_input">
                                                    <div class="input-group primary_input_coupon align-items-center">
                                                        <input type="text" name="message"
                                                            class="primary_input_field" placeholder="Type message..."
                                                            aria-label="Recipient's username"
                                                            aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <button class="btn" type="submit"> <svg
                                                                    class="wws-popup__send-btn" version="1.1"
                                                                    id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                                                    y="0px" viewBox="0 0 40 40"
                                                                    style="enable-background:new 0 0 40 40;"
                                                                    xml:space="preserve">
                                                                    <style type="text/css">
                                                                        .wws-lau00001 {
                                                                            fill: {{ $ws_setting->color }}80;
                                                                        }

                                                                        .wws-lau00002 {
                                                                            fill: {{ $ws_setting->color }};
                                                                        }
                                                                    </style>
                                                                    <path id="path0_fill" class="wws-lau00001"
                                                                        d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
                                                                    <path id="path0_fill_1_" class="wws-lau00002"
                                                                        d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @if ($ws_setting->isSingle())
                    <form action="{{ route('whatsapp-support.message.send') }}" method="post">
                        @csrf
                        <input type="hidden" name="browser" value="{{ $agent->browser() }}">
                        <input type="hidden" name="os" value="{{ $agent->platform() }}">
                        <input type="hidden" name="device_type"
                            value="{{ $agent->isMobile() ? 'Mobile' : 'Desktop' }}">
                        <div class="whats_app_popup_input">
                            <div class="input-group primary_input_coupon align-items-center">
                                <input type="text" name="message" class="primary_input_field"
                                    placeholder="Type message...">
                                <div class="input-group-append">
                                    <button class="btn " type="submit">
                                        <svg class="wws-popup__send-btn" version="1.1" id="Layer_1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;"
                                            xml:space="preserve">
                                            <style type="text/css">
                                                .wws-lau00001 {
                                                    fill: {{ $ws_setting->color }}80;
                                                }

                                                .wws-lau00002 {
                                                    fill: {{ $ws_setting->color }};
                                                }
                                            </style>
                                            <path id="path0_fill" class="wws-lau00001"
                                                d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
                                            <path id="path0_fill_1_" class="wws-lau00002"
                                                d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        @endif
    @endif
@endif
