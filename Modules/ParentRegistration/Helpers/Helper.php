<?php 

function field_label($fields,$name){
    $field=$fields->where('field_name',$name)->first();
    if($field && $field->label_name){
        return $field->label_name;
    }

    return __('parentregistration::parentRegistration.'.$name);
}

if(!function_exists('OnlineRegistrationFileUpload')){
    function OnlineRegistrationFileUpload($path,$file){
        $fileName='';
       if(!$file){
           return $fileName;
       }

       $original_name=$file->getClientOriginalName();
       $str=str_replace(' ','-', $original_name);
       $name=time().'_'.$str;

       if (!file_exists($path)) {
             mkdir($path, 0777, true);
        }

        $file->move($path,$name);
        $fileName=$path.$name;

        return $fileName;
    

    }
}
