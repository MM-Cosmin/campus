<?php

namespace Modules\Gmeet\Repositories\Eloquents;

use App\SmGeneralSettings;
use App\SmStaff;
use Carbon\Carbon;
use Modules\Gmeet\Entities\GoogleAccount;
use Modules\Gmeet\Entities\GmeetSettings;
use Modules\Gmeet\Repositories\Interfaces\GmeetEventRepositoryInterface;

class GmeetEventRepository implements GmeetEventRepositoryInterface
{

    public function client()
    {     
        $client = new \Google_Client();
        $client->setApplicationName("Gmeet Live Class");
        $setting = GmeetSettings::where('school_id', auth()->user()->school_id)->first();
        gMainSettings();
        if ($setting->individual_login ==1) {
            $client->setClientId(gMainSettings()->api_key);
            $client->setClientSecret(gMainSettings()->api_secret);
        } else {
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
        }

        $client->setRedirectUri(config('services.google.redirect_uri'));
        $client->setScopes(config('services.google.scopes'));
        $client->setApprovalPrompt(config('services.google.approval_prompt'));
        $client->setAccessType(config('services.google.access_type'));
        $client->setIncludeGrantedScopes(config('services.google.include_granted_scopes'));
        $this->client = $client;
        return $client;
    }
    public function createEvent(array $payload, $model = null)
    {

        $client = $this->client();
        $generalSettings = SmGeneralSettings::where('school_id', auth()->user()->school_id)->first();
        date_default_timezone_set($generalSettings->timeZone->time_zone);
        $now = Carbon::now()->setTimezone($generalSettings->timeZone->time_zone);

        $settings = GmeetSettings::where('is_main', 1)->first();
        if($settings->individual_login ==1) {
            $user = GoogleAccount::where('user_id', auth()->user()->id)
                    ->where('school_id', auth()->user()->school_id)->first();
        } else {
            $user = GoogleAccount::where('school_id', auth()->user()->school_id)->first();
        }
       
        $client->setAccessToken($user->token);
     
        // If there is no previous token or it's expired.
        if (!$client->isAccessTokenExpired()) {
            $refreshTokenSaved = $client->getRefreshToken();
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                return false;
            }
        }
        // end
        $client->setAccessToken($user->token);
        $accessToken = $client->getAccessToken();
        
        $start_date = Carbon::parse($payload['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($payload['time']));
        $start_time = Carbon::parse($start_date)->format("Y-m-d\TH:i:s");
        $end_time = Carbon::parse($start_date)->addMinute($payload['duration'])->format("Y-m-d\TH:i:s");
       
        $recurring = $this->recurring($payload);
        $attendees = [];
        if(auth()->user()->role_id !=1) {
            $users = SmStaff::whereIn('role_id', [1,5])->where('school_id', auth()->user()->school_id)
            ->get('email')->toArray();
            $attendees =  $users;
        }
        
        $service = new \Google_Service_Calendar($client);
       
        $event = new \Google_Service_Calendar_Event(array(
            'summary' => gv($payload, 'topic'),
            'location' => $generalSettings->address,
            'description' => gv($payload, 'description'),
            'start' => array(
                'dateTime' => $start_time,
                'timeZone' => $generalSettings->timeZone->time_zone,
            ),
            'end' => array(
                'dateTime' => $end_time,
                'timeZone' => $generalSettings->timeZone->time_zone,
            ),
            'recurrence' => $recurring,
            'attendees' => $attendees,
            'reminders' => array(
                'useDefault' => false,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => gUserSettings()->email_notification ?? 60),
                    array('method' => 'popup', 'minutes' => gUserSettings()->popup_notification ?? 20),
                ),
            ),
            'visibility'=>"private",
            "conferenceData" => array(
                "createRequest" => array(
                    "conferenceSolutionKey" => array(
                        "type" => "hangoutsMeet",
                    ),
                    "requestId" => "INFIXSCHOOL123",
                ),
            ),
        ));

        $opts = array('sendNotifications' => true, 'conferenceDataVersion' => true);
        if($model && $model->event_id) {            
            $event = $service->events->update('primary', $model->event_id, $event);
        } else {
            $event = $service->events->insert('primary', $event, $opts);
        }
       
        if ($model) {
            $model->update([
                'gmeet_url' => $event->hangoutLink,
                'event_id' => $event->id,
                'calendar_link' => $event->htmlLink,
                'calender_status' => $event->status,
                'recurring_event_id'=>$event->recurringEventId,
            ]);
        }

    }

    public function deleteEvent(string $event_id)
    {
        $client = $this->client();
        $service = $this->checkExpireToken($client);
        $service->events->delete('primary', $event_id);
    }
    private function recurring($payload)
    {
         $repeat = [];
        if (gv($payload, 'is_recurring') == 1) {

            $recurring_end_date = Carbon::parse($payload['recurring_end_date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($payload['time']));

            $until_date = Carbon::parse($recurring_end_date)->format("Y-m-d");
            $until = str_replace('-', "", $until_date);

            $days = gv($payload, 'days');
            $str_days = null;
            if (!empty($days)) {
                $str_days = implode(',', $days);
            }
            $interval = gv($payload, 'recurring_repeat_day');

            if (gv($payload, 'recurring_type') == 1) {
                $repeat = array("RRULE:FREQ=DAILY;INTERVAL=$interval;UNTIL=$until"); /*space not allow*/
               
            } elseif (gv($payload, 'recurring_type') == 2) {
                $repeat = array("RRULE:FREQ=WEEKLY;INTERVAL=$interval;UNTIL=$until;BYDAY=$str_days"); /*space not allow*/
                
            } elseif (gv($payload, 'recurring_type') == 3) {
                $repeat = array("RRULE:FREQ=MONTHLY;INTERVAL=$interval;UNTIL=$until"); /*space not allow*/
               
            }
        }
        return $repeat;
    }
    private function checkExpireToken($client)
    {
        // If there is no previous token or it's expired.
        if (!$client->isAccessTokenExpired()) {
            $refreshTokenSaved = $client->getRefreshToken();
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                return false;
            }
        }
        $client = $this->client();
        $user = GoogleAccount::where('user_id', auth()->user()->id)->first();
        $client->setAccessToken($user->token);
        $this->checkExpireToken($client);
        $client->setAccessToken($user->token);
        $accessToken = $client->getAccessToken();
        return $service = new \Google_Service_Calendar($client);
        // ==================
    }
    public function getEvent(string $event_id)
    {
        $service = $this->checkExpireToken;
        $event = $service->events->get('primary', $event_id);
        return $event;
    }
}
