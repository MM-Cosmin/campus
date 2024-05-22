<?php

namespace Modules\Saas\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Saas\Entities\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\Saas\Http\Requests\TicketCategoryRequestForm;

class SaasTicketCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try{
            $itemCategories=Category::all();
            return view('saas::tickets.category',compact('itemCategories'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
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
    public function store(TicketCategoryRequestForm $request)
    {
        try{           
            Category::create($request->all());
            Toastr::success('Operation done successfully.', 'Success');
            return redirect()->route('ticket.category');
        }catch(Throwable $e){           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
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
        try{
            $itemCategories=Category::all();
            $editData=$itemCategories->where('id',$id)->first();
            return view('saas::tickets.category',compact('itemCategories','editData'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(TicketCategoryRequestForm $r,$id)
    {
        try{         
            Category::find($id)->update($r->all());
            Toastr::success('Operation done successfully.', 'Success');
            return redirect()->route('ticket.category');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function category_delete_view($id)
    {
        try{
            $url=route('ticket.category_delete',$id);
            return view('saas::tickets.modal', compact('url'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            Category::find($id)->delete();
            Toastr::success('Operation done successfully.', 'Success');
            return redirect()->route('ticket.category');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
