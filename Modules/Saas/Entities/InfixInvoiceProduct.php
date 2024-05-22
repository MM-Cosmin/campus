<?php

namespace Modules\Saas\Entities;

use Illuminate\Database\Eloquent\Model;

class InfixInvoiceProduct extends Model
{
    public function productDetail()
    {
        return $this->belongsTo('App\SmItem', 'product_id', 'id');
    }
}
