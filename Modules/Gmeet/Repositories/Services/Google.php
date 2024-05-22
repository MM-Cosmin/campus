<?php
namespace Modules\Gmeet\Repositories\Services;
use Modules\Gmeet\Entities\GmeetSettings;

class Google
{
    protected $client;

    function __construct()
    {
        $client = new \Google_Client();
        $client->setApplicationName("Gmeet Live Class");
        $setting = GmeetSettings::where('school_id', auth()->user()->school_id)->first();
        if($setting->individual_login ==1) {
            $client->setClientId(gMainSettings()->api_key);
            $client->setClientSecret(gMainSettings()->api_secret);
        }else {           
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
        }
        $client->setRedirectUri(config('services.google.redirect_uri'));
        $client->setScopes(config('services.google.scopes'));
        $client->setApprovalPrompt(config('services.google.approval_prompt'));
        $client->setAccessType(config('services.google.access_type'));
        $client->setIncludeGrantedScopes(config('services.google.include_granted_scopes'));
        $this->client = $client;
    }
    public function __call($method, $args)
    {
        if (! method_exists($this->client, $method)) {
            throw new \Exception("Call to undefined method '{$method}'");
        }

        return call_user_func_array([$this->client, $method], $args);
    }
    public function service($service)
    {
        $className = "Google_Service_$service";

        return new $className($this->client);
    }
    public function connectUsing($token)
    {
        $this->client->setAccessToken($token);

        return $this;
    }
    public function revokeToken($token = null)
    {
        $token = $token ?? $this->client->getAccessToken();

        return $this->client->revokeToken($token);
    }
}
