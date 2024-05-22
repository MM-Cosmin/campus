<?php


if(!function_exists('before_insert_callback')){
    function before_insert_callback($school, $data, $table){
        $callback_function = 'before_insert_'.$table;
        if(function_exists($callback_function)){
            return $callback_function($school, $data);
        }
        return $data;
    }
}

if(!function_exists('before_insert_sm_about_pages')){
    function before_insert_sm_about_pages($school, $data){
        $data->title = 'About ' . $school->school_name;
        return $data;
    }
}

if(!function_exists('before_insert_sm_news_pages')){
    function before_insert_sm_news_pages($school, $data){
        $data->title = 'Nes ' . $school->school_name;
        return $data;
    }
}

if(!function_exists('before_insert_sm_home_page_settings')){
    function before_insert_sm_home_page_settings($school, $data){
        $data->long_title = 'About ' . $school->school_name;
        return $data;
    }
}

if(!function_exists('before_insert_sm_course_pages')){
    function before_insert_sm_course_pages($school, $data){
        $data->title = 'About ' . $school->school_name;
        return $data;
    }
}

if(!function_exists('before_insert_sm_payment_gateway_settings')){
    function before_insert_sm_payment_gateway_settings($school, $data){
        if($data->gateway_name == 'Stripe'){
            $data->gateway_secret_key = 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1-isWmBFnw1h2j';
            $data->gateway_secret_word = 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1';
        } else if($data->gateway_name == 'Paystack'){
            $data->gateway_secret_key = 'sk_live_2679322872013c265e161bc8ea11efc1e822bce1-isWmBFnw1h2j';
            $data->gateway_publisher_key = 'pk_live_e5738ce9aade963387204f1f19bee599176e7a71';
        }else if($data->gateway_name == 'PayPal'){
            $data->gateway_username = 'demo@paypal.com';
            $data->gateway_password = '12334589';
            $data->gateway_client_id = 'AaCPtpoUHZEXCa3v006nbYhYfD0HIX-dlgYWlsb0fdoFqpVToATuUbT43VuUE6pAxgvSbPTspKBqAF0x69';
            $data->gateway_secret_key = 'EJ6q4h8w0OanYO1WKtNbo9o8suDg6PKUkHNKv-T6F4APDiq2e19OZf7DfpL5uOlEzJ_AMgeE0L2PtTEj69';
        }
        return $data;

    }
}
