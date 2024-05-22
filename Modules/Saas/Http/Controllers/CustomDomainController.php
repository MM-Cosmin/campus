<?php

namespace Modules\Saas\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomDomainController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(!config('app.allow_custom_domain')){
            abort(403);
        }
        $school = auth()->user()->school;
        return view('saas::custom_domain.index', compact('school'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function validate(Request $request)
    {   
        $school = auth()->user()->school;

        $request->validate([
            'custom_domain' => 'required|url|max:191|unique:sm_schools,custom_domain,'.$school->id
        ]);

        return response()->json(true);
    
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dnsCheck(Request $request)
    {   
        $school = auth()->user()->school;
        $request->validate([
            'custom_domain' => 'required|url|max:191|unique:sm_schools,custom_domain,'.$school->id
        ]);

        $url = parse_url($request->custom_domain);

        $url = gv($url, 'host');

        if(!$url){
            return response()->json(false);
        }

        $cname = new  \Illuminate\Support\Collection (dns_get_record($url, DNS_CNAME));
    
        $count = $cname->where('target', preg_replace('#^https?://#', '', rtrim(url('/'), '/')))->count();

        if ($count){
            return response()->json(true);
        } else{
            return response()->json(false);
        }

        
    
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(!config('app.allow_custom_domain')){
            abort(403);
        }
        $school = auth()->user()->school;
        $request->validate([
            'custom_domain' => 'required|url|max:191|unique:sm_schools,custom_domain,'.$school->id
        ]);
        $url = parse_url($request->custom_domain);
        $url = gv($url, 'host');
        if(!$url){
            return response()->json(false);
        }


        $school->custom_domain = $url;
        $school->save();
        return response()->json(true);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function remove(Request $request)
    {
        $school = auth()->user()->school;
        $school->custom_domain = null;
        $school->save();
        Toastr::success('Domain remove from school successfull');
        return redirect()->back();
    }

}
