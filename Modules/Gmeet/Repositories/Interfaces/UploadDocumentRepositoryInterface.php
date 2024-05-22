<?php

namespace Modules\Gmeet\Repositories\Interfaces;

interface UploadDocumentRepositoryInterface
{    
    public function meetingOrClass($request, int $id);
    public function forVirtualClass($request);
    public function forVirtualMeeting($request);
    public function deleteRecordedFile($request, int $id):bool;
}