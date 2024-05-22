<?php

namespace Modules\Gmeet\Repositories\Eloquents;

use App\Repositories\Eloquents\BaseRepository;
use App\SmGeneralSettings;
use App\SmNotification;
use App\SmWeekend;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Gmeet\Entities\GmeetVirtualMeeting;
use Modules\Gmeet\Entities\GoogleAccount;
use Modules\Gmeet\Entities\GmeetSettings;
use Modules\Gmeet\Repositories\Interfaces\GmeetEventRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualMeetingRepositoryInterface;
use Modules\RolePermission\Entities\InfixRole;

class GmeetVirtualMeetingRepository extends BaseRepository implements GmeetVirtualMeetingRepositoryInterface
{
    protected $user;
    protected $role;
    protected $weekend;
    protected $googleAccount;
    protected $generalSettings;
    protected $eventRepository;
    public function __construct(
        GmeetVirtualMeeting $model,
        User $user,
        InfixRole $role,
        SmWeekend $weekend,
        GoogleAccount $googleAccount,
        SmGeneralSettings $generalSettings,
        GmeetEventRepositoryInterface $eventRepository
    ) {
        parent::__construct($model);
        $this->user = $user;
        $this->role = $role;
        $this->weekend = $weekend;
        $this->googleAccount = $googleAccount;
        $this->eventRepository = $eventRepository;
        $this->generalSettings = $generalSettings;
    }
    public function index(): array
    {
        $data = [];
        $data += $this->requiredData();
        return $data;
    }
    public function create(array $payload): ?Model
    {
        $model = $this->model->create($this->formatParams($payload));
        $model->participates()->attach($payload['participate_ids']);
        $this->setNotification($payload['participate_ids'], $payload['member_type'], $updateStatus = 0);
        if (hasKeySecret()) {
            $this->eventRepository->createEvent($payload, $model);
        }
        return $model;
    }
    public function edit(int $modelId): array
    {
        $data = [];
        $data['editdata'] = $this->findById($modelId);
        $data['participate_ids'] = DB::table('gmeet_virtual_meeting_users')->where('meeting_id', $modelId)->select('user_id')->pluck('user_id');

        $data['user_type'] = $data['editdata']->participates[0]['role_id'];
        $data['userList'] = User::where('role_id', $data['user_type'])
            ->where('school_id', auth()->user()->school_id)
            ->whereIn('id', $data['participate_ids'])
            ->select('id', 'full_name', 'role_id', 'school_id')->get();
        if (auth()->user()->role_id != 1) {
            if (auth()->user()->id != $data['editdata']->created_by) {
                Toastr::error('Meeting is created by other, you could not modify !', 'Failed');
                return redirect()->back()->send();
            }
        }
        $data += $this->requiredData();
        return $data;
    }
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);
        $updated = $model->update($this->formatParams($payload, $modelId));
        $updatedModel = $this->findById($modelId);
        if (auth()->user()->role_id == 1) {
            $updatedModel->participates()->detach();
            $updatedModel->participates()->attach($payload['participate_ids']);
        }
        $this->setNotification($payload['participate_ids'], $payload['member_type'], $updateStatus = 1);
        if (hasKeySecret()) {
            $this->eventRepository->createEvent($payload, $updatedModel);
        }
        return $updated;
    }
    public function userWiseUserList($request)
    {
        return $this->user->where('role_id', $request['user_type'])
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'full_name', 'school_id')->get();
    }
    public function deleteById(int $modelId): bool
    {
        $model = $this->findById($modelId);
        if ($model && $model->attached_file) {
            unlink($model->attached_file);
        }
        if ($model && $model->local_video) {
            unlink($model->local_video);
        }
        if ($model->event_id) {
            $this->eventRepository->deleteEvent($model->event_id); /*delete from google Calender */
        }
        if ($model) {
            DB::table('gmeet_virtual_meeting_users')->where('meeting_id', $modelId)->delete();
            $model->delete();
            Toastr::success(trans('gmeet::gmeet.Gmeet Virtual Meeting Delete Successfully'), trans('common.Success'));
            return true;
        }
        return false;

    }
    private function formatParams(array $payload, int $modelId = null)
    {
        $start_date = Carbon::parse(gv($payload, 'date'))->format('Y-m-d') . ' ' . date("H:i:s", strtotime(gv($payload, 'time')));
        $path = 'public/uploads/gmeet/';
        $file = gv($payload, 'attached_file');
        $formatParam = [
            'topic' => gv($payload, 'topic'),
            'gmeet_url' => gv($payload, 'gmeet_url'),
            'description' => gv($payload, 'description'),
            'date_of_meeting' => gv($payload, 'date'),
            'time_of_meeting' => gv($payload, 'time'),
            'meeting_duration' => gv($payload, 'duration'),
            'visibility' => gv($payload, 'visibility', 'private'),
            'time_before_start' => gv($payload, 'time_start_before'),
            'start_time' => Carbon::parse($start_date)->toDateTimeString(),
            'end_time' => Carbon::parse($start_date)->addMinute(gv($payload, 'duration'))->toDateTimeString(),
        ];
        if (gv($payload, 'is_recurring')) {
            $str_days = gv($payload, 'days')
            ? implode(',', gv($payload, 'days')) : null;
            $formatParam['is_recurring'] = gv($payload, 'is_recurring');
            $formatParam['recurring_type'] = gv($payload, 'recurring_type');
            $formatParam['recurring_repeat_day'] = gv($payload, 'recurring_repeat_day');
            $formatParam['weekly_days'] = gv($payload, 'recurring_type') == 2 ? $str_days : null;
            $formatParam['recurring_end_date'] = gv($payload, 'recurring_end_date');
        }
        if (!$modelId) {
            $formatParam['created_by'] = auth()->user()->id;
            $formatParam['school_id'] = auth()->user()->school_id;
            if ($file) {
                $formatParam['attached_file'] = fileUpload($file, $path);
            }

        } else if ($modelId && $file) {
            $model = $this->findById($modelId);
            $formatParam['attached_file'] = fileUpdate($model->attached_file, $file, $path);
        }
        if ($modelId) {
            $formatParam['updated_by'] = auth()->user()->id;
        }
        return $formatParam;
    }
    private function requiredData(): array
    {
        $time_zone_setup = $this->generalSettings->join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
            ->where('school_id', auth()->user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);

        $data['roles'] = $this->role->where(function ($q) {
            $q->where('school_id', auth()->user()->school_id)->orWhere('type', 'System');
        })->whereNotIn('id', [1, 2])->get();

        if (auth()->user()->role_id == 4) {

            $data['meetings'] = $this->model->orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
                ->orWhere('created_by', auth()->user()->id)
                ->where('status', 1)
                ->get();
        } elseif (auth()->user()->role_id == 1) {
            $data['meetings'] = $this->model->orderBy('id', 'DESC')->get();
        } else {
            $data['meetings'] = $this->model->orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
                ->where('status', 1)
                ->get();
        }

        $data['days'] = $this->weekend->orderby('order')->get(['id', 'name', 'order', 'gmeet_day']);
        $setting = GmeetSettings::where('school_id', auth()->user()->school_id)->first();
        $data['googleAccount'] = $this->googleAccount
            ->when($setting->individual_login == 1, function ($q) {
                $q->where('user_id', auth()->user()->id);
            })->where('login_at', 1)->first();
        return $data;
    }
    private function setNotification($users, $role_id, $updateStatus)
    {
        $now = Carbon::now('utc')->toDateTimeString();
        $school_id = auth()->user()->school_id;
        $notification_dates = [];

        if ($updateStatus == 1) {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_dates,
                    [
                        'user_id' => $user,
                        'role_id' => $role_id,
                        'school_id' => $school_id,
                        'academic_id' => getAcademicId(),
                        'date' => date('Y-m-d'),
                        'message' => 'Gmeet meeting is updated by ' . auth()->user()->full_name . '',
                        'url' => route('g-meet.virtual-meeting.index'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            };
        } else {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_dates,
                    [
                        'user_id' => $user,
                        'role_id' => $role_id,
                        'school_id' => $school_id,
                        'academic_id' => getAcademicId(),
                        'date' => date('Y-m-d'),
                        'message' => 'Gmeet meeting is created by ' . auth()->user()->full_name . ' with you',
                        'url' => route('g-meet.virtual-meeting.index'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            };
        }
        SmNotification::insert($notification_dates);
    }
}
