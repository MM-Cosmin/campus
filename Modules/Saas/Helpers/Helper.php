<?php

use App\User;
use App\SmNotification;
use Illuminate\Support\Facades\Auth;
use Modules\Saas\Entities\SaasSettings;

if(!function_exists('saasMenuStatus')){
    function saasMenuStatus($name){

        $sidebar_name=SaasSettings::where('name',$name)->where('saas_status',1)->first();
        if($sidebar_name){
            return true;
        }
        return false;
    }
}
if(!function_exists('saasTicketNotification')) {
    function saasTicketNotification($ticket, $request = null) {   
        if($ticket->assign_user && auth()->user()->id == $ticket->assign_user) {
            return false;
        }
        $saas_admin = User::where('is_administrator', 'yes')
        ->where('role_id', 1)->select('id', 'email', 'school_id', 'role_id')->first();
 
        $school_id = $saas_admin->school_id;
        
        $data = new SmNotification();
        $data->message = 'New Ticket Created';
        $data->url = route('user.ticket_view', $ticket->id);
        $data->user_id = $saas_admin->id;
        $data->role_id = $saas_admin->role_id;
        $data->school_id =  $school_id;
        $data->academic_id =  Null;
        $data->created_by = Auth::user()->id;
        $data->updated_by = Auth::user()->id;
        $data->save();
        if($data){
            return true;
        }
    }
}