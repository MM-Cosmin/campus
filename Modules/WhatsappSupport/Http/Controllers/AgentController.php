<?php

namespace Modules\WhatsappSupport\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Modules\WhatsappSupport\Entities\Agents;
use Modules\WhatsappSupport\Http\Requests\AgentRequest;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agents::all();
        return view('whatsappsupport::agents.index', compact('agents'));
    }

    public function create()
    {
        return view('whatsappsupport::agents.create');
    }

    public function store(AgentRequest $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required",
            'designation' => "required",
            'number' => "required|regex:/\+8801\d{1,9}/",
            'avatar' => "nullable|mimes:jpg,jpeg,png|max:" . $maxFileSize,
            'status' => "required",
            'always_available' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            if ($request->always_available == '0') {
                if (!$request->has('day')) {
                    session()->flash('error', 'Please select/check at least one working day!');
                    return redirect()->back()->withInput();
                }
            }
            $destination =  'public/whatsapp-support/';
            $avatar = ($request->avatar) ? fileUpload($request->avatar, $destination) : '';

            $agent = Agents::create([
                'name' => $request->name,
                'designation' => $request->designation,
                'number' => $request->number,
                'avatar' => $avatar,
                'status' => $request->status,
                'always_available' => $request->always_available,
            ]);
            if ($request->always_available == '0') {
                foreach ($request->day as $day) {
                    $index = $this->getDayIndex($day);
                    $startTime = Carbon::parse($request->start[$index])->format('H:i:s');
                    $endTime = Carbon::parse($request->end[$index])->format('H:i:s');
                    if ($startTime > $endTime) {
                        Toastr::error('Start Time can\'t be greater than End Time', 'Failed');
                        return redirect()->back();
                    } else {
                        $agent->times()->updateOrCreate(
                            [
                                'agent_id' => $agent->id,
                                'day' => $day
                            ],
                            [
                                'day' => $day,
                                'start' => $startTime,
                                'end' => $endTime,
                            ]
                        );
                    }
                }
            }
            Toastr::success('Agent Added', 'Success');
            return redirect()->route('whatsapp-support.agents');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $agent = Agents::find($id);
        return view('whatsappsupport::agents.edit', compact('agent'));
    }

    public function update(AgentRequest $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required",
            'designation' => "required",
            'number' => "required|regex:/\+8801\d{1,9}/",
            'avatar' => "nullable|mimes:jpg,jpeg,png|max:" . $maxFileSize,
            'status' => "required",
            'always_available' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            if ($request->always_available == '0') {
                if (!$request->has('day')) {
                    session()->flash('error', 'Please select/check at least one working day!');
                    return redirect()->back()->withInput();
                }
            }
            $destination =  'public/whatsapp-support/';
            $agent = Agents::find($request->agent_id);
            $avatar = ($request->avatar) ? fileUpdate($agent->avatar, $request->avatar, $destination) : '';
            $agent->update([
                'name' => $request->name,
                'designation' => $request->designation,
                'number' => $request->number,
                'avatar' => $avatar,
                'status' => $request->status,
                'always_available' => $request->always_available,
            ]);
            if ($request->always_available == '0') {
                $agent->times()->delete();
                foreach ($request->day as $day) {
                    $index = $this->getDayIndex($day);
                    $agent->times()->updateOrCreate(
                        [
                            'agent_id' => $agent->id,
                            'day' => $day
                        ],
                        [
                            'day' => $day,
                            'start' => Carbon::parse($request->start[$index])->format('H:i:s'),
                            'end' => Carbon::parse($request->end[$index])->format('H:i:s')
                        ]
                    );
                }
            }
            Toastr::success('Agent Updated', 'Success');
            return redirect()->route('whatsapp-support.agents');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $agent = Agents::find($id);
            $agent->times()->delete();
            $agent->delete();
            Toastr::success('Agent Deleted!', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getDayIndex($day)
    {
        switch ($day) {
            case 'Sunday':
                return 0;
            case 'Monday':
                return 1;
            case 'Tuesday':
                return 2;
            case 'Wednesday':
                return 3;
            case 'Thursday':
                return 4;
            case 'Friday':
                return 5;
            case 'Saturday':
                return 6;
        }
    }
}
