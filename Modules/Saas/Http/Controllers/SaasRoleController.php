<?php

namespace Modules\Saas\Http\Controllers;



use Toastr;
use App\Role;
use Validator;
use App\SmSchool;
use App\ApiBaseMethod;
use App\SmModulePermission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\SmModulePermissionAssign;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\SmSchoolModulePermissionAssign;
use Modules\Saas\Entities\SaasTableList;

class SaasRoleController extends Controller
{
    public function __construct()
    {
        $roles = Role::all();
        // $roles->truncate();
        // $this->middleware('TimeZone');
    }

    public function index(Request $request)
    {

        $roles = Role::where('active_status', '=', 1)
            ->whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])
            ->where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->orderBy('id', 'desc')
            ->get();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse($roles, null);
        }
        return view('saas::systemSettings.role.role', compact('roles'));
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            //'name' => "required|unique:roles,school_id," . Auth::user()->school_id
            'name' => 'required| max:255'
            // 'name' => "required|unique:roles,school_id," . Auth::user()->school_id
        ]);
       
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $role = new Role();
        $role->name = $request->name;
        $role->type = 'User Defined';
        $role->school_id = Auth::user()->school_id;
        $role->created_by = Auth::user()->id;
        $role->updated_by = Auth::user()->id;
        // return $role;
        $result = $role->save();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($result) {
                return ApiBaseMethod::sendResponse(null, 'Role has been created successfully');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        } else {
            if ($result) {
                return redirect()->back()->with('message-success', 'Role has been created successfully');
            } else {
                return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }
    }
    public function edit(Request $request, $id)
    {
        $role = Role::find($id);
         $roles = Role::where('active_status', '=', 1)
            ->whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])
            ->where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->orderBy('id', 'desc')
            ->get();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['role'] = $role;
            $data['roles'] = $roles->toArray();
            return ApiBaseMethod::sendResponse($data, null);
        }
        return view('saas::systemSettings.role.role', compact('role', 'roles'));
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required | max:255"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::find($request->id);
        $role->name = $request->name;
        $role->updated_by = Auth::user()->id;
        $result = $role->save();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($result) {
                return ApiBaseMethod::sendResponse(null, 'Role has been updated successfully');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        } else {
            if ($result) {
                return redirect()->back()->with('message-success', 'Role has been updated successfully');
            } else {
                return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }
    }
    public function delete(Request $request)
    {


        $id = 'role_id';

        $tables = SaasTableList::getTableList($id);

        try {
            $delete_query = Role::destroy($request->id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Role has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($delete_query) {
                    return redirect()->back()->with('message-success-delete', 'Role has been deleted successfully');
                } else {
                    return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';

            return redirect()->back()->with('message-danger-delete', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
        }










        // $role = Role::destroy($request->id);

        // if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //     if ($role) {
        //         return ApiBaseMethod::sendResponse(null, 'Role has been deleted successfully');
        //     } else {
        //         return ApiBaseMethod::sendError('Something went wrong, please try again');
        //     }
        // } else {
        //     if ($role) {
        //         return redirect()->back()->with('message-success-delete', 'Role has been deleted successfully');
        //     } else {
        //         return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
        //     }
        // }
    }

    public function modulePermission(){
        $roles = Role::where('active_status', '=', 1)
            ->whereIn('school_id', array(1, Auth::user()->school_id))
            ->where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->orderBy('id', 'desc')
            ->get();
        $institutions = SmSchool::where('id', '!=', 1)->get();
        return view('saas::superadminReport.modulePermission', compact('roles', 'institutions'));
    }

    public function schoolModulePermission(){
        $roles = Role::where('active_status', '=', 1)
            ->whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])
            ->where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->orderBy('id', 'desc')
            ->get();
        return view('saas::systemSettings.schoolModulePermission', compact('roles'));
    }

    public function schoolAssignModulePermission($id)
    {

        $role = Role::find($id);

        if ($id == 2) {
            $modules = SmModulePermission::where('dashboard_id', 2)->where('id', '!=', 22)->get();
        } elseif ($id == 3) {
            $modules = SmModulePermission::where('dashboard_id', 3)->where('id', '!=', 36)->get();
        } elseif ($id == 1) {
            $modules = SmModulePermission::where('id', '!=', 18)->where('id', '!=', 1)->where('dashboard_id', 1)->get();
        } else {
            $modules = SmModulePermission::where('dashboard_id', 1)->where('id', '!=', 1)->get();
        }


        $modules = $modules->groupBy('dashboard_id');



        $already_assigned = SmModulePermissionAssign::select('module_id')->where('role_id', $id)->where('school_id', Auth::user()->school_id)->get();

        $already_assigned_ids = [];
        foreach ($already_assigned as $value) {
            $already_assigned_ids[] = $value->module_id;
        }


        $school_wise_module_permissions = SmSchoolModulePermissionAssign::select('module_id')->where('school_id', Auth::user()->school_id)->get();

        $school_wise_module_ids = [];
        foreach ($school_wise_module_permissions as $value) {
            $school_wise_module_ids[] = $value->module_id;
        }


        return view('saas::systemSettings.assignModulePermission', compact('role', 'modules', 'already_assigned_ids', 'school_wise_module_ids'));
    }


    public function schoolAssignModulePermissionStore(Request $request)
    {

        SmModulePermissionAssign::where('role_id', $request->role_id)->where('school_id', Auth::user()->school_id)->delete();

        if (isset($request->permissions)) {
            foreach ($request->permissions as $permission) {
                $role_permission = new SmModulePermissionAssign();
                $role_permission->role_id = $request->role_id;
                $role_permission->module_id = $permission;
                $role_permission->school_id = Auth::user()->school_id;
                $role_permission->save();
            }
        }
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse(null, 'Module permission has been assigned successfully');
        }
        Toastr::success('Operation successful', 'Success');
        return redirect('module-permission');
    }





    public function modulePermissionView($id){

        // $role = Role::find($request->role);

        $institution = SmSchool::find($id);
        
        // if ($request->role == 2) {
        //     $modules = SmModulePermission::where('dashboard_id', 2)->where('id', '!=', 22)->get();
        // } elseif ($request->role == 3) {
        //     $modules = SmModulePermission::where('dashboard_id', 3)->where('id', '!=', 36)->get();
        // } elseif ($request->role == 1) {
        //     $modules = SmModulePermission::where('id', '!=', 18)->where('id', '!=', 1)->where('dashboard_id', 1)->get();
        // } else {
        $modules = SmModulePermission::where('dashboard_id', 1)->where('id', '!=', 1)->get();


        // }

        $modules = $modules->distinct('dashboard_id');





        $already_assigned = SmSchoolModulePermissionAssign::select('module_id')->where('school_id', $id)->get();



        $already_assigned_ids = [];
        foreach ($already_assigned as $value) {
            $already_assigned_ids[] = $value->module_id;
        }

        return view('saas::superadminReport.assignModulePermission', compact('modules', 'already_assigned_ids', 'institution'));
    }
    public function modulePermissionStore(Request $request){

        SmSchoolModulePermissionAssign::where('school_id', $request->school_id)->delete();

        if (isset($request->permissions)) {
            foreach ($request->permissions as $permission) {
                $role_permission = new SmSchoolModulePermissionAssign();
                $role_permission->module_id = $permission;
                $role_permission->school_id = $request->school_id;
                $role_permission->save();
            }
        }


        \Session::flash('message-success', 'Module permission has been assigned successfully!');
        return redirect('administrator/module-permission');
    }
}
