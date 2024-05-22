<?php

namespace Modules\Saas\Events;
use App\SmSchool;
use Illuminate\Queue\SerializesModels;

class InstituteRegistration
{
    use SerializesModels;

    public $institute;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SmSchool $institute)
    {
        $this->institute = $institute;
    }

}
