<?php

namespace Modules\Gmeet\Http\Controllers;

use App\SmStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\Gmeet\Http\Requests\GmeetVirtualClassRequestForm;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualClassRepositoryInterface;

class GmeetVirtualClassController extends Controller
{
    protected $virtualClassRepository;
    public function __construct(
        GmeetVirtualClassRepositoryInterface $virtualClassRepository
    ) { 
        $this->virtualClassRepository = $virtualClassRepository;
    }
    public function index()
    { 
        $data =  $this->virtualClassRepository->index();      
        return view('gmeet::virtualClass.index', $data);
    }

    public function create()
    {
        return view('gmeet::create');
    }


    public function store(GmeetVirtualClassRequestForm $request)
    {    
       try {
            $this->virtualClassRepository->create($request->validated());
            Toastr::success(trans('gmeet::gmeet.Gmeet Virtual Class Created Successfully'), trans('common.Success'));
            return redirect()->route('g-meet.virtual-class.index');
       } catch (\Throwable $th) {
            Toastr::error(trans('gmeet::gmeet.Gmeet Virtual Class Create Failed'), trans('common.Error'));
            return redirect()->route('g-meet.virtual-class.index');
       }
    }

    public function show($id)
    { 
        $data['virtualClass'] =  $this->virtualClassRepository->findById($id);       
        return view('gmeet::virtualClass.show', $data);
    }

    public function edit($id)
    {
        $data =  $this->virtualClassRepository->edit($id);       
        return view('gmeet::virtualClass.index', $data);
    }


    public function update(GmeetVirtualClassRequestForm $request, $id)
    {
        try {
            $this->virtualClassRepository->update($id, $request->validated());
            Toastr::success(trans('gmeet::gmeet.Gmeet Virtual Class Updated Successfully'), trans('common.Success'));
            return redirect()->route('g-meet.virtual-class.index');
        } catch (\Throwable $th) {           
            Toastr::error(trans('gmeet::gmeet.Gmeet Virtual Class Update Failed'), trans('common.Error'));
            return redirect()->route('g-meet.virtual-class.index');
        }
    }


    public function destroy(Request $request, $id)
    {
        try {
            $this->virtualClassRepository->deleteById($id);
            return redirect()->route('g-meet.virtual-class.index');
        } catch (\Throwable $th) {
         
            Toastr::error(trans('gmeet::gmeet.Gmeet Virtual Class Delete Failed'), trans('common.Error'));
            return redirect()->route('g-meet.virtual-class.index');
        }
    }

    public function myChild(int $id)
    {

        try {
            if (Auth::user()->role_id == 3) {
                $data['records'] = StudentRecord::where('student_id', $id)->where('school_id', auth()->user()->school_id)->where('academic_id', getAcademicId())->get();
                $student = SmStudent::where('id', $id)->first();
                return view('gmeet::virtualClass.index', $data);
            }
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
