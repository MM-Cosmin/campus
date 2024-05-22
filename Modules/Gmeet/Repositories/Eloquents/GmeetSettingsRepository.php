<?php

namespace Modules\Gmeet\Repositories\Eloquents;

use Modules\Gmeet\Entities\GmeetSettings;
use App\Repositories\Eloquents\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Modules\Gmeet\Repositories\Interfaces\GmeetSettingsRepositoryInterface;

class GmeetSettingsRepository extends BaseRepository implements GmeetSettingsRepositoryInterface

{
    public function __construct(
        GmeetSettings $model
    ) {
        parent::__construct($model);
    }
    public function index(): array
    {
        $data = [];       
        if(!gUserSettings()) {
            $this->create($payload = []);
        }
        $data['settings'] = $this->gSetting();
        return $data;
    }
    public function gSetting()
    {
        return gUserSettings();
    }
    public function create(array $payload): ?Model
    {
        
        return $this->model->create($this->formatParams($payload));
    }
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->model->where('id', $modelId)->where('user_id', auth()->user()->id)->first();
        
        $update = $model->update($this->formatParams($payload, $modelId));

        $value3 = url('/')."/gmeet/google/oauth";
        $key3 = 'GOOGLE_REDIRECT_URI';
        putEnvConfigration($key3, $value3);

        if(auth()->user()->role_id ==1 && gv($payload, 'type')== 'api') 
        {
            $key1 = 'GOOGLE_CLIENT_ID';
            $key2 = 'GOOGLE_CLIENT_SECRET'; 
            
            $value1 = gv($payload, 'api_key');
            $value2 = gv($payload, 'api_secret_key');
           
            putEnvConfigration($key1, $value1);
            putEnvConfigration($key2, $value2);
         
        }
        session()->put('gSettings', $update);
        return $update;

    }
    private function formatParams(array $payload, int $modelId = null):array
    {    

        if(gv($payload, 'type')== 'api_use') {
            $formatParam['use_api'] = gv($payload, 'use_api', 0);
            $formatParam['individual_login'] = gv($payload, 'use_api')==0 ? 0 : 0;
        }

        if(gv($payload, 'type')== 'api') {
            $formatParam['api_key'] = gv($payload, 'api_key');
            $formatParam['api_secret'] = gv($payload, 'api_secret_key');
        }

        if(gv($payload, 'type')== 'reminder') {
            $formatParam['email_notification'] = gv($payload, 'email_notification');
            $formatParam['popup_notification'] = gv($payload, 'popup_notification');
        }

        if(gv($payload, 'type')== 'permission') {
            $formatParam['individual_login'] = gv($payload, 'individual_login');
        }
        
        if (!$modelId) {
            $formatParam['use_api'] = gv($payload, 'use_api', 1);
            $formatParam['user_id'] = auth()->user()->id;
            $formatParam['school_id'] = auth()->user()->school_id;
        }


        return $formatParam;
    }

}
