<?php

namespace Modules\Gmeet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\Gmeet\Http\Requests\GmeetSettingsFormRequest;
use Modules\Gmeet\Repositories\Interfaces\GmeetSettingsRepositoryInterface;

class GmeetSettingsController extends Controller
{
    protected $settingsRepository;
    public function __construct(
        GmeetSettingsRepositoryInterface $settingsRepository
    ) {
       $this->settingsRepository = $settingsRepository; 
    }
    public function index()
    {
        $data = $this->settingsRepository->index();
        return view('gmeet::settings.settings', $data);
    }
    public function update(GmeetSettingsFormRequest $request, $id)
    {
        try {
         
            $this->settingsRepository->update($id, $request->validated());
            Toastr::success(trans('gmeet::gmeet.G-Meet Settings Updated Successfully'), trans('common.Success'));
            return redirect()->route('g-meet.settings.index');
        } catch (\Throwable $th) {
            Toastr::error(trans('gmeet::gmeet.G-Meet Settings Update Failed'), trans('common.Error'));
            return redirect()->route('g-meet.settings.index');
        }
    }
}
