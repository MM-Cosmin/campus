<?php

namespace Modules\Gmeet\Repositories\Eloquents;

use App\Repositories\Eloquents\BaseRepository;
use App\SmClass;
use App\SmNotification;
use App\SmSection;
use App\SmStaff;
use App\SmStudent;
use App\SmWeekend;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Gmeet\Entities\GmeetVirtualClass;
use Modules\Gmeet\Entities\GoogleAccount;
use Modules\Gmeet\Entities\GmeetSettings;
use Modules\Gmeet\Repositories\Interfaces\GmeetEventRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetSettingsRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualClassRepositoryInterface;

class GmeetVirtualClassRepository extends BaseRepository implements GmeetVirtualClassRepositoryInterface
{
    private $settingRepository;
    private $smWeekend;
    private $smSection;
    private $smClass;
    private $smStaff;
    private $smStudent;
    private $googleAccount;
    private $eventRepository;
    public function __construct(
        GmeetVirtualClass $model,
        SmWeekend $smWeekend,
        SmClass $smClass,
        SmStaff $smStaff,
        SmStudent $smStudent,
        SmSection $smSection,
        GoogleAccount $googleAccount,
        GmeetEventRepositoryInterface $eventRepository,
        GmeetSettingsRepositoryInterface $settingRepository
    ) {
        parent::__construct($model);
        $this->smClass = $smClass;
        $this->smStaff = $smStaff;
        $this->smWeekend = $smWeekend;
        $this->smSection = $smSection;
        $this->smStudent = $smStudent;
        $this->googleAccount = $googleAccount;
        $this->eventRepository = $eventRepository;
        $this->settingRepository = $settingRepository;
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
        $teacher_ids = auth()->user()->role_id == 1 ? $payload['teacher_ids'] : auth()->user()->id;
        $model->teachers()->attach($teacher_ids);
        $student_ids = studentRecords((object) $payload, null, null)->pluck('student_id')->unique();
        $userList = $this->smStudent->whereIn('id', $student_ids)->select('user_id', 'role_id', 'parent_id')->get();
        $this->setNotification($userList);
        if (hasKeySecret()) {
            $this->eventRepository->createEvent($payload, $model);
        }
        return $model;
    }
    public function edit(int $modelId): array
    {
        $data = [];
        $data['editdata'] = $this->findById($modelId);
        $assign_day = array(explode(',', $data['editdata']->weekly_days));
        $data['class_sections'] = $this->smSection->whereIn('id', $data['editdata']->class->classSections->pluck('section_id'))->get();
        $data += $this->requiredData();
        return $data;
    }
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);
        $updated = $model->update($this->formatParams($payload, $modelId));
        $updatedModel = $this->findById($modelId);
        if (auth()->user()->role_id == 1) {
            $updatedModel->teachers()->detach();
            $updatedModel->teachers()->attach($payload['teacher_ids']);
        }
        if (hasKeySecret()) {
            $this->eventRepository->createEvent($payload, $updatedModel);
        }
        return $updated;
    }
    private function formatParams(array $payload, int $modelId = null): array
    {

        $start_date = Carbon::parse($payload['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($payload['time']));
        $path = 'public/uploads/gmeet/';
        $file = gv($payload, 'attached_file');
        $formatParam = [
            'class_id' => gv($payload, 'class'),            
            'section_id' => gv($payload, 'section'),
            'topic' => gv($payload, 'topic'),
            'gmeet_url' => gv($payload, 'gmeet_url'),
            'description' => gv($payload, 'description'),
            'date_of_meeting' => gv($payload, 'date'),
            'time_of_meeting' => gv($payload, 'time'),
            'meeting_duration' => gv($payload, 'duration'),
            'time_before_start' => gv($payload, 'time_before_start'),
            'start_time' => Carbon::parse($start_date)->toDateTimeString(),
            'visibility' => gv($payload, 'visibility', 'private'),
            'end_time' => Carbon::parse($start_date)->addMinute($payload['duration'])->toDateTimeString(),

        ];
        if (gv($payload, 'is_recurring')) {
            $str_days = gv($payload, 'days')
            ? implode(',', gv($payload, 'days')) : null;
            $formatParam['is_recurring'] = gv($payload, 'is_recurring');
            $formatParam['recurring_type'] = gv($payload, 'recurring_type');
            $formatParam['recurring_repeat_day'] = gv($payload, 'recurring_repeat_day');
            $formatParam['recurring_type'] = gv($payload, 'recurring_type') == 2 ? $str_days : null;
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
        return $formatParam;
    }

    public function meetings(): array
    {
        $data['meetings'] = $this->model->when(auth()->user()->role_id == 4, function ($query) {
            $query->whereHas('teachers', function ($query) {
                return $query->where('user_id', auth()->user()->id);
            });
        })->when(!in_array(auth()->user()->role_id, [1, 4, 5]), function ($query) {
            $query->whereHas('teachers', function ($query) {
                return $query->where('user_id', auth()->user()->id);
            });
        })
            ->whereNull('course_id')
            ->orderBy('id', 'DESC')
            ->where('status', 1)
            ->get();

        return $data;

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
            DB::table('gmeet_virtual_class_teachers')->where('meeting_id', $modelId)->delete();
            $model->delete();
            Toastr::success(trans('gmeet::gmeet.Gmeet Virtual Class Delete Successfully'), trans('common.Success'));
            return true;
        }
        return false;

    }
    private function requiredData(): array
    {
        $teacher_info = $this->smStaff->where('user_id', auth()->user()->id)->first();
        $data['settings'] = $this->settingRepository->gSetting();

        $data['records'] = auth()->user()->role_id == 2
        ? auth()->user()->student->studentRecords : null;
        $data['classes'] = teacherAccess() ? $teacher_info->classes : $this->smClass->get();
        $data['teachers'] = $this->smStaff->where(function($q)  {
	$q->where('role_id', 4)->orWhere('previous_role_id', 4);})->get();
        $data['days'] = $this->smWeekend->orderby('order')->get(['id', 'name', 'order', 'gmeet_day']);
        $setting = GmeetSettings::where('school_id', auth()->user()->school_id)->first();
        $data['googleAccount'] = $this->googleAccount
            ->when($setting->individual_login == 1, function ($q) {
                $q->where('user_id', auth()->user()->id);
            })->where('login_at', 1)->first();
        $data += $this->meetings();
        return $data;
    }
    private function setNotification($users, $updateStatus = 0)
    {
        $now = Carbon::now('utc')->toDateTimeString();
        $school_id = auth()->user()->school_id;
        $notification_datas = [];

        if ($updateStatus == 1) {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id' => $user->user_id,
                        'role_id' => 2,
                        'school_id' => $school_id,
                        'date' => date('Y-m-d'),
                        'message' => 'Gmeet virtual class details udpated',
                        'url' => route('g-meet.virtual-class.index'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
                array_push(
                    $notification_datas,
                    [
                        'user_id' => $user->parent_id,
                        'role_id' => 3,
                        'school_id' => $school_id,
                        'date' => date('Y-m-d'),
                        'message' => 'Gmeet virtual class details udpated of your child',
                        'url' => route('g-meet.virtual-class.index'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            };
        } else {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id' => $user->user_id,
                        'role_id' => 2,
                        'school_id' => $school_id,
                        'date' => date('Y-m-d'),
                        'message' => 'Gmeet Virtual class created for you',
                        'url' => route('g-meet.virtual-class.index'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
                array_push(
                    $notification_datas,
                    [
                        'user_id' => $user->parent_id,
                        'role_id' => 3,
                        'school_id' => $school_id,
                        'date' => date('Y-m-d'),
                        'message' => 'Gmeet Virtual class created for your child',
                        'url' => route('g-meet.virtual-class.index'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            };
        }
        SmNotification::insert($notification_datas);
    }
}
