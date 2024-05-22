<?php

namespace Modules\Saas\Http\Controllers;

use DB;
use App\SmBaseGroup;
use App\SmBaseSetup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Saas\Entities\SaasTableList;

class SaasBaseSetupController extends Controller
{
    public function __construct()
	{
		$this->middleware('PM');
		// $this->middleware('TimeZone');
	}

	public function index()
	{
		$base_groups = SmBaseGroup::where('active_status', '=', 1)->get();
		return view('saas::systemSettings.baseSetup.base_setup', compact('base_groups'));
	}
	public function store(Request $request)
	{
		$request->validate([
			'name' => "required",
			'base_group' => "required"
		]);
		$base_setup = new SmBaseSetup();
		$base_setup->base_setup_name = $request->name;
		$base_setup->school_id =  Auth::user()->school_id;
		$base_setup->base_group_id = $request->base_group;
		$result = $base_setup->save();
		if ($result) {
			Toastr::success('Operation successfull', 'Success');
			return redirect('administrator/base-setup');
		} else {
			Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
		}
	}
	public function edit($id)
	{
		$base_setup = SmBaseSetup::find($id);
		$base_groups = SmBaseGroup::where('active_status', '=', 1)->get();
		return view('saas::systemSettings.baseSetup.base_setup', compact('base_setup', 'base_groups'));
	}

	public function update(Request $request)
	{
		$request->validate([
			'name' => "required",
			'base_group' => "required"
		]);

		$base_group = SmBaseSetup::find($request->id);
		$base_group->base_setup_name = $request->name;
		$base_group->base_group_id = $request->base_group;
		$result = $base_group->save();
		if ($result) {
			Toastr::success('Operation successfull', 'Success');
			return redirect('administrator/base-setup');
		} else {
			Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
		}
	}


	public function delete(Request $request)
	{
		$id = 'gender_id';
		$tables = SaasTableList::getTableList($id);
		try {
			$delete_query = SmBaseSetup::destroy($request->id);
			if ($delete_query) {
				Toastr::success('Operation successfull', 'Success');
				return redirect('administrator/base-setup');
			} else {
				Toastr::error('Operation Failed', 'Failed');
            	return redirect()->back();
			}
		} catch (\Illuminate\Database\QueryException $e) {
			$msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
			Toastr::warning('Failed'.$msg, 'Warning');
			return redirect('administrator/base-setup');
		} catch (\Exception $e) {
			Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
		}
	}
}
