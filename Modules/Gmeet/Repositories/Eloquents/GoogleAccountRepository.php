<?php

namespace Modules\Gmeet\Repositories\Eloquents;

use Modules\Gmeet\Entities\GoogleAccount;
use Modules\Gmeet\Repositories\Services\Google;
use Modules\Gmeet\Repositories\Interfaces\GoogleAccountRepositoryInterface;

class GoogleAccountRepository implements GoogleAccountRepositoryInterface

{
    public function store($request, $google)
    {
       
        if (!$request->has('code')) {       
            return redirect($google->createAuthUrl());
        }

        // Use the given code to authenticate the user.
        $google->authenticate($request->get('code'));
       
        $account = $google->service('Oauth2');
        
        $userInfo = $account->userinfo->get();
        
        GoogleAccount::where('user_id', auth()->user()->id)->updateOrCreate(
            [
                'user_id'=>auth()->user()->id,             
                'google_id' => $userInfo->id,
                'email' =>$userInfo->email,                
                'token' => $google->getAccessToken(),
                'school_id'=>auth()->user()->id,
                'login_at'=>1
            ]
        );
        if($request->type == 'meeting') {
            return redirect()->route('g-meet.virtual-class.index');
        }
        return redirect()->back();
    }
    /** 
    // - Revoke the authentication token.
    // - Delete the Google Account.
    */
    public function destroy($googleAccount, $google)
    {
        $googleAccount->delete();
    
        // Event though it has been deleted from our database,
        // we still have access to $googleAccount as an object in memory.
        $google->revokeToken($googleAccount->token);
    
        return redirect()->back();
    }
}