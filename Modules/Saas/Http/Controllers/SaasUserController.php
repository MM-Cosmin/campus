<?php

namespace Modules\Saas\Http\Controllers;

use Throwable;
use App\SmNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Saas\Entities\Ticket;
use Illuminate\Routing\Controller;
use Modules\Saas\Entities\Comment;
use Modules\Saas\Entities\Category;
use Modules\Saas\Entities\Priority;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SaasUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function index(){
        return view('saas::user.index');
    }
    function tickets(){
        try{
            $ticket=Ticket::where('user_id',Auth::user()->id)->orderBy('id','desc')->get();
            return view('saas::user.ticket-list',compact('ticket'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function add_ticket(){
        try{
            $category=Category::latest()->get();
            $priority=Priority::latest()->get();
            return view('saas::user.add_ticket', compact('category','priority') );
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function ticket_store(Request $r){
        $this->validate($r,[
            'subject' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|integer',
            'priority' => 'required|integer'
        ]);
        try{
            $ticket=Ticket::create([
                'user_id' => Auth::user()->id,
                'subject'   => $r->subject,
                'description'   => $r->description,
                'category_id'   => $r->category,
                'priority_id'   => $r->priority,
            ]);

            $ticket->school_id =  Auth::user()->school_id;
            $ticket->created_by = Auth::user()->id;
            $ticket->updated_by = Auth::user()->id;
            $ticket->save();

            $data=new SmNotification();
            $data->user_id = $ticket->user_id;
            $data->ticket_id = $ticket->id;
            $data->role_id = $ticket->user->role_id;
            $data->message = $ticket->user->username.' created a ticket';
            $data->link = route('admin.ticket_view',$ticket->id);
            $data->received_id =1;
            $data->school_id =  Auth::user()->school_id;
            $data->created_by = Auth::user()->id;
            $data->updated_by = Auth::user()->id;
            $data->save();

            Toastr::success('Operation Successfully.', 'Success');
            return redirect()->route('user.ticket');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function ticket_edit($id){
        try{
            $editData=Ticket::findOrFail($id);
            $category=Category::latest()->get();
            $priority=Priority::latest()->get();
            return view('saas::user.add_ticket', compact('category','priority','editData') );
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function ticket_update(Request $r,$id){
        $this->validate($r,[
            'subject' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|integer',
            'priority' => 'required|integer'
        ]);
        try{
            $ticket= Ticket::findOrFail($id)->update([
                'user_id' => Auth::user()->id,
                'subject'   => $r->subject,
                'description'   => $r->description,
                'category_id'   => $r->category,
                'priority_id'   => $r->priority,
            ]);
            $ticket->school_id =  Auth::user()->school_id;
            $ticket->created_by = Auth::user()->id;
            $ticket->updated_by = Auth::user()->id;
            $ticket->save();

            Toastr::success('Operation Successfully.', 'Success');
            return redirect()->route('user.ticket');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function ticket_delete_view($id){
        try{
            $url=route('user.ticket_delete',$id);
            return view('saas::tickets.modal', compact('url'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function ticket_delete($id){
        try{
            Ticket::findOrFail($id)->delete();
            Toastr::success('Operation Successfully.', 'Success');
            return redirect()->route('user.ticket');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function ticket_view($id){
        try{
            $data=Ticket::findOrFail($id);
            $comment=Comment::where('ticket_id',$data->id)
                ->leftjoin('sm_staffs','sm_staffs.user_id','=','comments.user_id')
                ->select('comments.*','sm_staffs.full_name','sm_staffs.staff_photo')
                ->get();
            return view('saas::user.ticket-view', compact('data','comment'));
        }catch(Throwable $e){
                Toastr::error('Operation Failed', 'Error');
                return redirect()->back();
            }
    }
    function comment_store(Request $r){
        $validator=$this->validate($r,[
            'comment' => 'required|string',
            'file'       =>'sometimes|nullable|mimes:doc,pdf,docx,jpg,jpeg,png',
        ]);
        try{
            $ticket=Ticket::findOrFail($r->id);
            if ($ticket) {
                $data=Comment::create([
                    'user_id' => Auth::user()->id,
                    'client_id' => Auth::user()->id,
                    'ticket_id'   => $ticket->id,
                    'comment'   => $r->comment,
                    'file'  =>null
                ]);
                $data->school_id =  Auth::user()->school_id;
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
                $data->save();
                $fileName = "";
                if($r->file('file') != ""){
                    $file = $r->file('file');
                    $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('public/uploads/comment/', $fileName);
                    $data->file= 'public/uploads/comment/'.$fileName;
                    $data->save();
                }

                $data=new SmNotification();
                $data->user_id = Auth::user()->id;
                $data->ticket_id = $ticket->id;
                $data->role_id = Auth::user()->role_id;
                $data->message = $ticket->user->username.' comment on this ticket';
                $data->link = route('admin.ticket_view',$ticket->id);
                $data->received_id =1;
                $data->school_id =  Auth::user()->school_id;
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
                $data->save();
                return redirect()->back();
            }
            else {
                return redirect()->back()->with('message-danger','Comment not send !');
            }
        }catch(Throwable $e){
                Toastr::error('Operation Failed', 'Error');
                return redirect()->back();
            }
    }
    function reopen_ticket($id){
        try{
            $ticket=Ticket::findOrFail($id);
            if($ticket->active_status == 3){
                $ticket->update([
                    'active_status' => 0
                ]);

                $data=new SmNotification();
                $data->user_id = Auth::user()->id;
                $data->ticket_id = $ticket->id;
                $data->role_id = Auth::user()->role_id;
                $data->message = $ticket->user->username.' re open  this ticket';
                $data->link = route('admin.ticket_view',$ticket->id);
                $data->received_id =1;
                $data->school_id =  Auth::user()->school_id;
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
                $data->save();
                if ($ticket->assign_user) {
                    $data=new SmNotification();
                    $data->user_id = Auth::user()->id;
                    $data->ticket_id = $ticket->id;
                    $data->role_id = Auth::user()->role_id;
                    $data->message = $ticket->user->username.' re open  this ticket';
                    $data->link = route('admin.ticket_view',$ticket->id);
                    $data->received_id = $ticket->assign_user;
                    $data->school_id =  Auth::user()->school_id;
                    $data->created_by = Auth::user()->id;
                    $data->updated_by = Auth::user()->id;
                    $data->save();
                }
                Toastr::success('Operation Successfull', 'Error');
                return redirect()->back();
            }
                Toastr::warning('Ticket Already Open !', 'Warning');
                return redirect()->back();
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function active_ticket(){
        try{
            $ticket=Ticket::where('user_id',Auth::user()->id)->where('active_status',0)->get();
            return view('saas::user.ticket-list',compact('ticket'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    function complete_ticket(){
        try{
            $ticket=Ticket::where('user_id',Auth::user()->id)->where('active_status',1)->get();
            return view('saas::user.ticket-list',compact('ticket'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
    function comment_reply(Request $r){
        $this->validate($r,[
            'comment' => 'required|string',
            'file'       =>'sometimes|required|mimes:doc,pdf,docx,jpg,jpeg,png',
        ]);

        try{
            $comment=Comment::find($r->comment_id);
            $data=Comment::create([
                'user_id' => Auth::user()->id,
                'client_id' => $comment->client_id,
                'ticket_id'   => $comment->ticket_id,
                'comment'   => $r->comment,
                'comment_id'   => $r->comment_id,
                'file'  => null
            ]);
            $data->school_id =  Auth::user()->school_id;
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
                $data->save();
            $fileName = "";
            if($r->file('file') != ""){
                $file = $r->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/comment/', $fileName);
                $data->file= 'public/uploads/comment/'.$fileName;
                $data->save();
            }
            $data=new SmNotification();
            $data->user_id = Auth::user()->id;
            $data->ticket_id = $comment->ticket_id;
            $data->role_id = Auth::user()->role_id;
            $data->message = Auth::user()->username.' reply on your comment';
            $data->link = route('admin.ticket_view',$comment->ticket_id);
            $data->received_id = $comment->user_id;
            $data->school_id =  Auth::user()->school_id;
            $data->created_by = Auth::user()->id;
            $data->updated_by = Auth::user()->id;
            $data->save();
            return redirect()->back();
        }catch(Throwable $e){
                Toastr::error('Operation Failed', 'Error');
                return redirect()->back();
            }
    }

}
