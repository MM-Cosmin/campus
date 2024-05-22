@extends('backEnd.master')
@section('title')
@lang('system_settings.backup_settings')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.backup_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="{{route('sms-settings')}}">@lang('system_settings.backup_settings')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-40">@lang('system_settings.upload_database')</h3>
                        </div>
                        @if(isset($session))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'session/'.$session->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'backup-store',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">                                
                                <div class="primary_input">
                                    <div class="primary_file_uploader">
                                        
                                            <input class="primary_input_field {{ $errors->has('content_file') ? ' is-invalid' : '' }}" readonly="true" type="text" 
                                            placeholder="{{isset($editData->file) && $editData->file != ""? getFilePath3($editData->file):'Upload File'}} "  id="placeholderInput" name="content_file">
                                        <button class="" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="browseFile">@lang('common.browse')</label>
                                            <input type="file" class="d-none form-control" name="content_file" id="browseFile">
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @if(isset($session))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
                                            @endif
                                          
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div> 
                </div>  
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0"> @lang('system_settings.database_backup_list')</h3>
                        </div>
                    </div>
                    <div class="col-lg-8 text-right mb-20">
                        <a href="{{route('get-backup-files',1)}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-arrow-circle-down pr-2"></span>
                            @lang('system_settings.image_backup')
                        </a>
                        <!-- <a href="{{route('get-backup-files',2)}}" class="primary-btn small fix-gr-bg ">
                            <span class="ti-arrow-circle-down pr-2"></span>
                            @lang('system_settings.full_project_backup')
                        </a>  -->
                        <a href="{{route('get-backup-db')}}" class="primary-btn small fix-gr-bg ">
 
                            <span class="ti-arrow-circle-down pr-2"></span>
 
                           @lang('system_settings.database_backup')
 
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        
                        <table class="display school-table school-table-style" cellspacing="0" width="100%">
                            <thead>
                                


                                <tr>  
                                    <th>Size</th>
                                    <th>@lang('system_settings.created_date_time')</th>
                                    <th>@lang('system_settings.backup_files')</th>
                                    <th>File Type</th> 
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($sms_dbs as $sms_db)
                                <tr>
                                    <td>
                                        @php 
                                        if(file_exists($sms_db->source_link)){
                                        $size = filesize($sms_db->source_link);
                                            $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
                                            $power = $size > 0 ? floor(log($size, 1024)) : 0;
                                            echo number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
                                        }else{
                                            echo 'File already deleted.';
                                        }
                                        @endphp
                                    </td>
                                    <td>{{date('jS M, Y h:i A', strtotime($sms_db->created_at))}}</td>
                                    <td>{{$sms_db->file_name}}</td>
                                    <td> 
                                        @php
                                        if($sms_db->file_type == 0){
                                            echo 'Database';
                                        }else if($sms_db->file_type==1){
                                            echo 'Images';
                                        }else{
                                            echo 'Whole Project';
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <a  class="primary-btn small tr-bg  " href="{{route('download-files',$sms_db->id)}}"  >
 
                                            <span class="pl ti-download"></span> @lang('common.download')
                                        </a>
                                        <a  class="primary-btn small tr-bg  " href="{{route('restore-database',$sms_db->id)}}"  >
                                            <span class="pl ti-upload"></span>  @lang('system_settings.restore')
                                        </a>

                                        <!-- in pro mood the attr should add in a (data-toggle="modal") -->

                                       <a data-target="#deleteDatabase{{$sms_db->id}}" data-toggle="modal" class="primary-btn small tr-bg  " href="{{url('/'.$sms_db->id)}}"  >
                                            <span class="pl ti-close"></span>  @lang('common.delete')
                                        </a>

                                    </td>
                                </tr>



                                  <div class="modal fade admin-query" id="deleteDatabase{{$sms_db->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">                    
                                                <h4 class="modal-title"> @lang('common.delete_item')</h4> 
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4> @lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal"> @lang('common.cancel')</button>
                                                    <a href="{{route('delete_database', [$sms_db->id])}}" class="text-light">
                                                    <button class="primary-btn fix-gr-bg" type="submit"> @lang('common.delete')</button>
                                                     </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                @endforeach
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
</section>
 

@endsection
