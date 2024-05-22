<?php

namespace Modules\Gmeet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gmeet\Entities\GoogleAccount;
use Modules\Gmeet\Repositories\Services\Google;

class GoogleAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Google $google)
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
                'login_at'=>1,
                'google_id' => $userInfo->id,
                'name' =>$userInfo->name,
                'email' =>$userInfo->email,
                'school_id'=>auth()->user()->school_id,
                'token' => $google->getAccessToken(),
            ]
        );
        if($request->type == 'class') {
            return redirect()->route('g-meet.virtual-class.index');
        }elseif($request->type == 'meeting') {
            return redirect()->route('g-meet.virtual-meeting.index');
        }
        return redirect()->route('admin-dashboard');
    }
    /** 
    // - Revoke the authentication token.
    // - Delete the Google Account.
    */
    public function destroy(GoogleAccount $googleAccount, Google $google)
    {
        $googleAccount->delete();
    
        // Event though it has been deleted from our database,
        // we still have access to $googleAccount as an object in memory.
        $google->revokeToken($googleAccount->token);
    
        return redirect()->back();
    }
}
