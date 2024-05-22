<?php

namespace Modules\AppSlider\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\AppSlider\Entities\AppSlider;
use Modules\AppSlider\Http\Requests\AppSliderFormRequest;
use Modules\AppSlider\Http\Requests\AppSliderUpdateFormRequest;

class AppSliderController extends Controller
{

    public function index(Request $request)
    {

        try {
            $saas=0;
            $appSliders=AppSlider::query();
            if ($request->is('saas/appslider')) {
                $appSliders->where('school_id', null);
                $saas=1;
            } else {
                $appSliders->where('school_id', auth()->user()->school_id);
            }
            $appSliders=$appSliders->get();
            return view('appslider::index', compact('appSliders', 'saas'));
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(AppSliderFormRequest $request)
    {
        try {
            $path='public/uploads/appSlider/';
            $appSlider=new AppSlider();
            $appSlider->title = $request->title;
            $appSlider->url = $request->url;
            $appSlider->slider_image = fileUpload($request->slider_image, $path);
            $appSlider->active_status = $request->status;
            $appSlider->school_id = $request->saas_admin==1 ? null : auth()->user()->school_id;
            $appSlider->save();
            
            Toastr::success('Operation successful', 'Success');
            if ($request->saas_admin==1) {
                return redirect()->route('appslider.saas.index');
            } else {
                return redirect()->route('appslider.index');
            }
        } catch (\Throwable $th) {
            Toastr::error('Slider Created Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        return view('appslider::show');
    }

    public function edit(Request $request, $id)
    {
        try {
            $editData = AppSlider::checkCondition()->where('id', $id)->first();
            $saas = $editData ? ($editData->school_id==null ? 1:0 ) : 0;
            if (!$editData) {
                Toastr::error('Not Found', 'Failed');
                return redirect()->back();
            }
            $appSliders=AppSlider::query();
            if ($saas==1) {
                $appSliders->where('school_id', null);
            } else {
                $appSliders->where('school_id', auth()->user()->school_id);
            }
            $appSliders=$appSliders->get();
            return view('appslider::index', compact('appSliders', 'editData', 'saas'));
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(AppSliderUpdateFormRequest $request)
    {
        try {
            $path='public/uploads/appSlider/';
            $appSlider = AppSlider::findOrFail($request->id); 
            $appSlider->title = $request->title;
            $appSlider->url = $request->url;
            $appSlider->slider_image = fileUpdate($appSlider->slider_image, $request->slider_image, $path);
            $appSlider->active_status = $request->status;
            $appSlider->save();
            
            Toastr::success('Operation successful', 'Success');
            if ($request->saas_admin==1) {
                return redirect()->route('appslider.saas.index');
            } else {
                return redirect()->route('appslider.index');
            }
        } catch (\Throwable $th) {
            Toastr::error('Slider Update Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            AppSlider::checkCondition()->find($id)->delete();

            Toastr::success('Operation successful', 'Success');
            if ($request->is('saas/appslider')) {
                return redirect()->route('appslider.saas.index');
            } else {
                return redirect()->route('appslider.index');
            }
        } catch (\Throwable $th) {
            Toastr::error('Slider Delete Operation Failed', 'Failed');
            if ($request->is('saas/appslider')) {
                return redirect()->route('appslider.saas.index');
            } else {
                return redirect()->route('appslider.index');
            }
        }
    }
}
