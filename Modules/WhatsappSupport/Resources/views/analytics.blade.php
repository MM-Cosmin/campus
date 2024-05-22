@extends('backEnd.master')
@section('title')
    {{ __('whatsappsupport::general.analytics') }}
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ __('whatsappsupport::general.analytics') }}</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">{{ __('whatsappsupport::general.whatsapp_support') }}</a>
                    <a
                        href="{{ route('whatsapp-support.analytics') }}">{{ __('whatsappsupport::general.analytics') }}</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row row-gap-24 mb-5">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <a href="#" class="d-block">
                        <div class="white-box single-summery cyan">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>{{ __('whatsappsupport::general.total_click') }}</h3>
                                    <p class="mb-0 invisible">{{ __('whatsappsupport::general.clicks') }}</p>
                                </div>
                                <h1 class="gradient-color2">
                                    {{ $messages->count() }}
                                </h1>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <a href="#" class="d-block">
                        <div class="white-box single-summery violet">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>{{ __('whatsappsupport::general.click_from_mobile') }}</h3>
                                    <p class="mb-0 invisible">{{ __('whatsappsupport::general.clicks') }}</p>
                                </div>
                                <h1 class="gradient-color2">
                                    {{ $messages->where('device_type', 'Mobile')->count() }}
                                </h1>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <a href="#" class="d-block">
                        <div class="white-box single-summery blue">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>{{ __('whatsappsupport::general.click_from_desktop') }}</h3>
                                    <p class="mb-0 invisible">{{ __('whatsappsupport::general.clicks') }}</p>
                                </div>
                                <h1 class="gradient-color2">
                                    {{ $messages->where('device_type', 'Desktop')->count() }}
                                </h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="white-box">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-flex">
                            <h3 class="mb-0 mr-30">{{ __('whatsappsupport::general.analytics') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="">
                        <x-table>
                            <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('whatsappsupport::general.id') }}</th>
                                        <th scope="col">{{ __('whatsappsupport::general.ip') }}</th>
                                        <th scope="col">{{ __('whatsappsupport::general.browser') }}</th>
                                        <th scope="col">{{ __('whatsappsupport::general.operating_system') }}</th>
                                        <th scope="col">{{ __('whatsappsupport::general.messages') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($messages as $index => $message)
                                        <tr>
                                            <th><a>{{ $index + 1 }}</a></th>
                                            <td>{{ $message->ip }}</td>
                                            <td>{{ $message->browser }}</td>
                                            <td>{{ $message->os }}</td>
                                            <td>{{ $message->message }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </x-table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
