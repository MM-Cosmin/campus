<?php

namespace Modules\Saas\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Cache;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\Saas\Entities\SaasSettings;

class SaasSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {            
            $saas_settings=SaasSettings::get();
            return view('saas::settings.saas_setting',compact('saas_settings'));
        } catch (\Throwable $th) {
            Toastr::error('Operation failed', 'Error');
            return redirect('module-permission');
        }
      
    }
    public function statusChange(Request $request)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }

        try {

           $saas_setting= SaasSettings::find($request->field_id);
           if(is_null($saas_setting)){
            $saas_setting= new SaasSettings();
           }
           $saas_setting->saas_status=$request->field_status;
           $saas_setting->save();

           if($request->infix_module_id){
               $infix_module=InfixModuleInfo::find($request->infix_module_id);
               if(is_null($infix_module)){
                $infix_module= new InfixModuleInfo();
               }
               $infix_module->active_status=$request->field_status;
               $infix_module->save();
           }

            Cache::forget('saas_settings');

           return response(["done"]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('saas::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('saas::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('saas::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
