<?php

namespace Modules\Saas\Http\Controllers;

use App\User;
use Throwable;
use Illuminate\Http\Request;
use Modules\Saas\Entities\Ticket;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;

class AssignTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('saas::index');
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
        try {
            $request->validate([
                'ticket_id' => 'required|integer',
                'assign_user' => 'required|integer',                  
            ]); 
            $user_id = (int)$request->assign_user;
            $ticket = Ticket::findOrFail($request->ticket_id);
            $ticket->assign_user = $user_id;
            $ticket->save();

            $route = route('user.ticket_view', $ticket->id);
            $assigned_user = User::where('id', $user_id)->first();

            saasTicketNotification($ticket);
          
            sendNotification('Assign New Ticket', $route, $user_id, $assigned_user->role_id);

            // other school admin   
            if($assigned_user->role_id !==1) {
                $school_admin_id = User::where('school_id', $assigned_user->school_id)->where('role_id', 1)->value('id');
                sendNotification('Assign New Ticket', $route, $school_admin_id, 1);
            }
            Toastr::success('Operation Successfully.', 'Success');
            return redirect()->route('admin.ticket_list');
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
