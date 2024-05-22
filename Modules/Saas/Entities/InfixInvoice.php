<?php
namespace Modules\Saas\Entities;

use Illuminate\Database\Eloquent\Model;

class InfixInvoice extends Model
{
    public static function amountInvoice($id, $discount_type, $discount_amount, $tax_type, $tax)
    {
        $products = InfixInvoiceProduct::where('invoice_id', $id)->get();
        $amount   = 0 ;
        foreach ($products as $product) {
            $amount += $product->quantity * $product->price;
        }

        if ($tax_type == "BD") {
            $tax_amount = $amount / 100 * $tax;

            $amount = $amount + $tax_amount;
        }

        if ($discount_type != "") {
            if ($discount_type == "P") {
                $percentage = $amount / 100 * $discount_amount;
                $amount = $amount - $percentage;
            } elseif ($discount_type == "F") {
                $amount = $amount - $discount_amount;
            }
        }

        if ($tax_type == "AD") {
            $tax_amount = $amount / 100 * $tax;
            $amount = $amount + $tax_amount;
        }
        return $amount;
    }



    public static function taxInvoice($id, $discount_type, $discount_amount, $tax_type, $tax)
    {
        $products = InfixInvoiceProduct::where('invoice_id', $id)->get();
        $amount = 0;
        foreach ($products as $product) {
            $amount += $product->quantity * $product->price;
        }


        if ($tax_type == "BD") {
            $tax_amount = $amount / 100 * $tax;

            $amount = $amount + $tax_amount;
        }



        if ($discount_type != "") {
            if ($discount_type == "P") {
                $percentage = $amount / 100 * $discount_amount;
                $amount = $amount - $percentage;
            } elseif ($discount_type == "F") {
                $amount = $amount - $discount_amount;
            }
        }


        if ($tax_type == "AD") {
            $tax_amount = $amount / 100 * $tax;
        }
        return $tax_amount;
    }

    public static function discountInvoice($id, $discount_type, $discount_amount, $tax_type, $tax)
    {
        $products = InfixInvoiceProduct::where('invoice_id', $id)->get();
        $amount = 0;
        foreach ($products as $product) {
            $amount += $product->quantity * $product->price;
        }

        if ($tax_type == "BD") {
            $tax_amount = $amount / 100 * $tax;
            $amount = $amount + $tax_amount;
        }


        $discount = 0;
        
        if ($discount_type != "") {
            if ($discount_type == "P") {
                $percentage = $amount / 100 * $discount_amount;
                $discount = $percentage;
            } elseif ($discount_type == "F") {
                $discount = $discount_amount;
            }
        }
        return $discount;
    }

    public static function TotalamountInvoice($id, $discount_type, $discount_amount)
    {
        $products = InfixInvoiceProduct::where('invoice_id', $id)->get();
        $amount = 0;
        foreach ($products as $product) {
            $amount += $product->quantity * $product->price;
        }
        return $amount;
    }

    public function invoiceProducts()
    {
        return $this->hasMany('Spondonit\Invoice\Models\InfixInvoiceProduct', 'invoice_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('App\SmStaff', 'customer_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\SmPaymentMethhod', 'payment_method_id', 'id');
    }


    public function currency()
    {
        return $this->belongsTo('App\SmCurrency', 'currency_id', 'id');
    }
}
