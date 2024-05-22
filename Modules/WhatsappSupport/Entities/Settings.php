<?php

namespace Modules\WhatsappSupport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Settings extends Model
{
    protected $guarded = [];

    protected $table = 'settings';

    public function isMulti()
    {
        return $this->agent_type == 'multi';
    }

    public function isNotDisableForAdmin()
    {
        if (!$this->disable_for_admin_panel) {
            return true;
        } elseif ($this->disable_for_admin_panel && Request::is('admin/*')) {
            return false;
        } else {
            return true;
        }
    }

    public function isSingle()
    {
        return $this->agent_type == 'single';
    }

    public function forDesktop()
    {
        return $this->availability == 'desktop';
    }

    public function forMobile()
    {
        return $this->availability == 'mobile';
    }

    public function forBoth()
    {
        return $this->availability == 'both';
    }
}
