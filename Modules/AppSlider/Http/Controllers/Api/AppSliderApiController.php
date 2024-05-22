<?php

namespace Modules\AppSlider\Http\Controllers\Api;

use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\AppSlider\Entities\AppSlider;
use Illuminate\Contracts\Support\Renderable;

class AppSliderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function saasAdmin(Request $request)
    {
        try {
            $appSliders = AppSlider::where('school_id', null)->get();
            if(is_null($appSliders)){
                $slider = new AppSlider();
                $slider->title = "Test Slider";
                $slider->save();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['appSliders'] = $appSliders->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Error.', $th->getMessage());
        }
    }
    public function index(Request $request)
    {

        try {
            $appSliders = AppSlider::where('school_id', 1)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['appSliders'] = $appSliders->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Error.', $th->getMessage());
        }
    }

    public function saasGetSliders(Request $request, $school_id)
    {
        $appSliders = AppSlider::where('school_id', $school_id)->get();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['appSliders'] = $appSliders->toArray();
            return ApiBaseMethod::sendResponse($data, null);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $appSlider = AppSlider::findOrFail($id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['appSlider'] = $appSlider;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Error.', $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $input = $request->all();
                $validator = Validator::make($input, [
                    'title' => ['required'],
                    'url' => ['required', 'url'],
                    'slider_image' => ['required'],
                    'status' => ['required'],
                    'school_id' => ['required'],
                ]);
                if ($validator->fails()) {
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                    }
                }
            }
            $path = 'public/uploads/appSlider/';
            $appSlider = new AppSlider();
            $appSlider->title = $request->title;
            $appSlider->url = $request->url;
            $appSlider->slider_image = fileUpload($request->slider_image, $path);
            $appSlider->active_status = $request->status;
            $appSlider->school_id = $request->school_id;
            $result = $appSlider->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Slider Created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Error.', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $input = $request->all();
                $validator = Validator::make($input, [
                    'id' => ['required'],
                    'title' => ['required'],
                    'url' => ['required', 'url'],
                    'slider_image' => ['required'],
                    'status' => ['required'],
                    'school_id' => ['required'],
                ]);
                if ($validator->fails()) {
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                    }
                }
            }
            $path = 'public/uploads/appSlider/';
            $appSlider = AppSlider::findOrFail($request->id);
            $appSlider->title = $request->title;
            $appSlider->url = $request->url;
            $appSlider->slider_image = fileUpdate($appSlider->slider_image, $request->slider_image, $path);
            $appSlider->active_status = $request->status;
            $appSlider->school_id = auth()->user()->school_id;
            $result = $appSlider->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Slider Updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Error.', $th->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            AppSlider::destroy($id);
            return ApiBaseMethod::sendResponse(null, 'Slider Delete successfully');
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Error.', $th->getMessage());
        }
    }
}
