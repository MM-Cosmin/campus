@extends('backEnd.master')
@section('title')
    {{ __('whatsappsupport::general.agents') }}
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ __('whatsappsupport::general.agents') }}</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">{{ __('whatsappsupport::general.whatsapp_support') }}</a>
                    <a
                        href="{{ route('whatsapp-support.agents') }}">{{ __('whatsappsupport::general.agents') }}</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-flex justify-content-between" style="width:100%;">
                            <h3 class="mb-0 mr-30">{{ __('whatsappsupport::general.agents') }}</h3>
                            <ul>
                                <li><a href="{{ route('whatsapp-support.agents.create') }}"
                                        class="primary-btn radius_30px fix-gr-bg text-white mb-15"><i class="ti-plus"></i>
                                        {{ __('whatsappsupport::general.add_agent') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-20">
                    <div class="">
                        <x-table>
                            <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('whatsappsupport::general.id') }}</th>
                                        <th scope="col">{{ __('whatsappsupport::general.name') }}</th>
                                        <th scope="col">{{ __('whatsappsupport::general.number') }}</th>
                                        <th scope="col">{{ __('whatsappsupport::general.designation') }}</th>
                                        <th scope="col">{{ __('whatsappsupport::general.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($agents as $index => $agent)
                                        <tr>
                                            <th><a>{{ $index + 1 }}</a></th>
                                            <td><a href="">{{ $agent->name }}</a> </td>
                                            <td>{{ $agent->number }}</td>
                                            <td>{{ $agent->designation }}</td>
                                            <td>
                                                <div class="dropdown CRM_dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenu2" data-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false">
                                                        {{ __('whatsappsupport::general.select') }}
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                        aria-labelledby="dropdownMenu2">
                                                        <a class="dropdown-item"
                                                            href="{{ route('whatsapp-support.agents.show', $agent->id) }}"
                                                            type="button">{{ __('whatsappsupport::general.edit') }}</a>
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#deleteWhatsAppModal{{ $index }}"
                                                            class="dropdown-item"
                                                            type="button">{{ __('whatsappsupport::general.delete') }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade admin-query"
                                            id="deleteWhatsAppModal{{ $index }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ __('whatsappsupport::general.delete_file') }}</h4>
                                                        <button type="button" class="close text-success"
                                                            data-dismiss="modal">&times;
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>{{ __('whatsappsupport::general.are_you_sure_to_delete') }}</h4>
                                                        </div>
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">{{ __('whatsappsupport::general.cancel') }}</button>
                                                            <a href="{{ route('whatsapp-support.agents.delete', $agent->id) }}"
                                                                class="primary-btn fix-gr-bg"
                                                                type="submit">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
