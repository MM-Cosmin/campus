<?php
namespace Modules\Zoom\Repositories\Interfaces;

interface ZoomRepositoryInterface
{
    public function index();
    public function createZoomToken();
    public function createZoom();
    public function zoomData($request);
    public function isTimeAvailableForMeeting($request, $id);
}