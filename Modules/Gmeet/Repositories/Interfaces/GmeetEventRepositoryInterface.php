<?php

namespace Modules\Gmeet\Repositories\Interfaces;

interface GmeetEventRepositoryInterface 
{
    public function client();
    public function createEvent(array $payload, $model = null);
    public function deleteEvent(string $event_id);
    public function getEvent(string $event_id);
}
