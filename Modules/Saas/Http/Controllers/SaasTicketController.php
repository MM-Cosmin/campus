<?php

namespace Modules\Saas\Http\Controllers;

use DB;
use validate;
use Throwable;
use App\SmStaff;
use App\SmSchool;
use App\SmNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Saas\Entities\Ticket;
use Illuminate\Routing\Controller;
use Modules\Saas\Entities\Comment;
use Modules\Saas\Entities\Category;
use Modules\Saas\Entities\Priority;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Saas\Entities\CommentMultiAttachment;
use Modules\Saas\Entities\TicketMultiAttachment;
use Modules\Saas\Http\Requests\TicketCategoryRequestForm;
use function GuzzleHttp\Psr7\try_fopen;
use Illuminate\Support\Facades\Validator;

class SaasTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

        // ticket
    function index(){
        try{
            $category=Category::latest()->get();
            $priority=Priority::latest()->get();

            if (Auth::user()->is_administrator=='yes') {
                $ticket=Ticket::with('school', 'category', 'priority', 'agent_user')->latest()->get();
            } else {
                $ticket = Ticket::with('school', 'category', 'priority', 'agent_user')->latest()->where('assign_user',Auth::user()->school_id)->orWhere('created_by', Auth::user()->id)->get();
            }
            $staffs = SmStaff::get(['id', 'user_id', 'full_name']);
            return view('saas::tickets.ticket', compact('category','priority','ticket', 'staffs'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    function my_ticket(){
        try{
            $category=Category::latest()->get();
            $priority=Priority::latest()->get();

            if (Auth::user()->is_administrator=='yes') {
                $ticket=Ticket::with('school', 'category', 'priority', 'agent_user')->latest()->get();
            } else {
                $ticket=Ticket::with('school', 'category', 'priority', 'agent_user')->latest()->where('created_by',Auth::user()->id)->get();
            }
            $staffs = SmStaff::get(['id', 'user_id', 'full_name']);
            return view('saas::tickets.ticket', compact('category','priority','ticket', 'staffs'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function add_ticket(){
        try{
            $category=Category::latest()->get();
            $priority=Priority::latest()->get();
            $schools = SmSchool::where('id', '>', 1)->get(['id', 'school_name']);
            return view('saas::user.add_ticket', compact('category','priority', 'schools') );
        }catch(Throwable $e){
          
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    function ticket_store(Request $r){
        $validator = Validator::make($r->all(), [
            'subject' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|integer',
            'school_id' => 'required|integer',
            'priority' => 'required|integer',
        ],[
            'school_id.required' => 'The school field is required.'
        ]);
        if ($validator->fails()) {
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $super_admin_user_id = User::where('role_id', 1)
        ->when($r->school_id, function($q) use($r){
            $q->where('school_id', $r->school_id);
        }, function($elseQ){
            $elseQ->where('school_id', auth()->user()->school_id);
        })->value('id');
       
        try{
            $ticket=new Ticket();
            $ticket->user_id = $super_admin_user_id ?? null;
            $ticket->subject=$r->subject;
            $ticket->assign_user= $r->user_agent;
            $ticket->description=$r->description;
            $ticket->category_id=$r->category;
            $ticket->priority_id=$r->priority;
            $ticket->active_status=$r->active_status ?? 0;
            $ticket->created_by= Auth::user()->id;
            $ticket->school_id= $r->school_id ?? Auth::user()->school_id;         
            $ticket->save();

            if(($r->filled('files')))
            {              
              
                $path = 'public/uploads/ticket/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                foreach($r->get('files') as $file) {
                    $decode = json_decode($file);
                
                    $image = $decode->data;  // your base64 encoded
                    $image = str_replace('data:image/png;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = rand(1,10000).$decode->name;
                    $output = \File::put($path. '/' . $imageName, base64_decode($image));
                   
                    $newFile = new TicketMultiAttachment();
                    $newFile->file = $path.$imageName;
                    $newFile->ticket_id = $ticket->id;
                    $newFile->save();
                }
            }
            // for saas admin
                
            saasTicketNotification($ticket);
            if($r->school_id) {
                $data=new SmNotification();
                $data->message = 'New Ticket Created';
                $data->url = route('user.ticket_view',$ticket->id);
                $data->user_id = $super_admin_user_id;
                $data->school_id =  $r->school_id;
                $data->academic_id =  Null;
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
                $data->save();
            }
            Toastr::success('Operation Successfully.', 'Success');
            return redirect()->route('admin.ticket_list');
        }catch(Throwable $e){
             ;
            Toastr::error('Operation Failed', 'Failed');
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
            $allcom =Comment::all();
            return view('saas::user.ticket-view', compact('data','comment','allcom'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    function comment_store(Request $r)
    {
        $r->validate([
            'message' => 'required|string',
            'file'       =>'sometimes|required|mimes:doc,pdf,docx,jpg,jpeg,png',
        ]);
        try{
            $active_status = $r->active_status;
            $ticket = Ticket::findOrFail($r->id);
            $assigned_user = User::where('id', $ticket->assign_user)->first() ?? null;
            $replied_user = Auth::user();
            $ticket_user = User::findOrFail($ticket->user_id);
            if ($ticket) {
                $comment= new Comment();
                $comment->user_id=Auth::user()->id;
                $comment->client_id=$ticket->user_id;
                $comment->ticket_id=$ticket->id;
                $comment->comment=$r->message;

               

                $comment->school_id =  Auth::user()->school_id;
                $comment->created_by = Auth::user()->id;
                $comment->updated_by = Auth::user()->id;
                $comment->save();
                if($r->filled('files')) {
                    $path = 'public/uploads/comment/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    foreach($r->get('files') as $file) {
                        $decode = json_decode($file);
                    
                        $image = $decode->data;  // your base64 encoded
                        $image = str_replace('data:image/png;base64,', '', $image);
                        $image = str_replace(' ', '+', $image);
                        $imageName = rand(1,10000).$decode->name;
                        $output = \File::put($path. '/' . $imageName, base64_decode($image));
                    
                        $newFile = new CommentMultiAttachment();
                        $newFile->file = $path.$imageName;
                        $newFile->comment_id = $comment->id;
                        $newFile->save();
                    }
                }
                // notify to saas admin
                // if ticket replier is not saas admin

                if($assigned_user && ($comment->created_by != 1 && $assigned_user->id != 1)){
                    $notificatio=new SmNotification();
                    $notificatio->message = @$replied_user->full_name. ' replied to '.@$assigned_user->full_name . ' conversation #'.$ticket->id;
                    $notificatio->url = route('user.ticket_view',$ticket->id);
                    $notificatio->user_id = 1;
                    $notificatio->school_id =  1;
                    $notificatio->academic_id =  Null;
                    $notificatio->created_by = Auth::user()->id;
                    $notificatio->updated_by = Auth::user()->id;
                    $notificatio->save();
                }

                // notify assigned user if another user replied
                if($assigned_user && ($comment->user_id != $assigned_user->id)){
                    $notificatio=new SmNotification();
                    $notificatio->message = @$replied_user->full_name. ' replied to '.@$assigned_user->full_name . ' conversation #'.$ticket->id;
                    $notificatio->url = route('user.ticket_view',$ticket->id);
                    $notificatio->user_id = $assigned_user->id;
                    $notificatio->school_id =  $assigned_user->school_id;
                    $notificatio->academic_id =  Null;
                    $notificatio->created_by = Auth::user()->id;
                    $notificatio->updated_by = Auth::user()->id;
                    $notificatio->save();
                }

                // notify ticket creator if assigned user replied
                if($ticket->user_id != $comment->user_id){
                    $notificatio=new SmNotification();
                    $notificatio->message = @$replied_user->full_name. ' replied to your conversation #'.$ticket->id;
                    $notificatio->url = route('user.ticket_view',$ticket->id);
                    $notificatio->user_id = $ticket_user->id;
                    $notificatio->school_id =  $ticket_user->school_id;
                    $notificatio->academic_id =  Null;
                    $notificatio->created_by = Auth::user()->id;
                    $notificatio->updated_by = Auth::user()->id;
                    $notificatio->save();
                }


               /* if ($ticket->created_by!=Auth::user()->id) {
                    $data=new SmNotification();
                    $data->message = 'Comment on your ticket';
                    $data->url = route('user.ticket_view',$ticket->id);
                    $data->user_id = $ticket->created_by;
                    $data->school_id =  Auth::user()->school_id;
                    $data->academic_id =  Null;
                    $data->created_by = Auth::user()->id;
                    $data->updated_by = Auth::user()->id;
                    $data->save();
                }*/
                // notify school admin
                if($ticket->school_id !== auth()->user()->school_id){
                    $school_admin = User::where('school_id', $ticket->school_id)->where('role_id', 1)->first();

                    $notificatio=new SmNotification();
                    $notificatio->message = @$replied_user->full_name. ' replied to '.$assigned_user->full_name . ' conversation #'.$ticket->id;
                    $notificatio->url = route('user.ticket_view',$ticket->id);
                    $notificatio->user_id = $school_admin->id;
                    $notificatio->school_id =  $school_admin->school_id;
                    $notificatio->academic_id =  Null;
                    $notificatio->created_by = Auth::user()->id;
                    $notificatio->updated_by = Auth::user()->id;
                    $notificatio->save();
                }
                
                if($active_status !== $ticket->active_status) {
                    $ticket->active_status = $r->active_status;
                    $ticket->save();
                }
                return redirect('ticket-view/'.$r->id);
        }
        else {
            return redirect()->back()->with('message-danger','Comment not send !');
        }
        }catch(\Exception $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    function ticket_edit($id){
        try{
            $editData=Ticket::findOrFail($id);
            $category=Category::latest()->get();
            $priority=Priority::latest()->get();
            $schools = SmSchool::where('id', '>', 1)->get(['id', 'school_name']);
            return view('saas::user.add_ticket', compact('category','priority','editData', 'schools') );
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    function ticket_update(Request $r,$id){
        $r->validate([
            'subject' => 'required|string',
            'description' => 'required|string',         
            'user' => 'sometimes|nullable|integer',
            'category' => 'required|integer',
            'priority' => 'required|integer'
        ]);
        try{
            $ticket=Ticket::findOrFail($id);
            if (Auth::user()->role_id == 1) {
                if (isset($r->user_agent) && $ticket->active_status == 0)  {
                    $data=new SmNotification();
                    $data->user_id = Auth::user()->id;
                    $data->role_id = Auth::user()->role_id;
                    $data->message = 'Check this ticket';
                    $data->url = route('admin.ticket_view',$ticket->id);
                    $data->user_id = $r->user_agent;
                    $data->school_id =  Auth::user()->school_id;
                    $data->created_by = Auth::user()->id;
                    $data->updated_by = Auth::user()->id;
                    $data->save();
                }
                if (!isset($r->user_agent)) {
                    if ($r->active_status == 3) {
                        $message='Close this ticket';
                    }
                    elseif ($r->active_status == 2) {
                        $message='Complete this ticket';
                    }
                    else {
                        $message='Ongoing this ticket';
                    }
                    $data=new SmNotification();
                    $data->user_id = Auth::user()->id;
                    $data->role_id = $ticket->user->role_id;
                    $data->message = $message;
                    $data->url = route('user.ticket_view',$ticket->id);
                    $data->school_id =  Auth::user()->school_id;
                    $data->created_by = Auth::user()->id;
                    $data->updated_by = Auth::user()->id;
                    $data->save();
                }

                $ticket->update([
                    'subject'   => $r->subject,
                    'description'   => $r->description,                   
                    'category_id'   => $r->category,
                    'priority_id'   => $r->priority,
                    'active_status'   => $r->active_status,
                    'school_id'=> $r->school_id ?? Auth::user()->school_id,
                ]);
                $ticket->updated_by = Auth::user()->id;
                $ticket->save();
            }
            else{
                $ticket->update([
                    'subject'   => $r->subject,
                    'description'   => $r->description,
                    'category_id'   => $r->category,
                    'priority_id'   => $r->priority,
                    'active_status'   => $r->active_status,
                    'school_id'=> $r->school_id ?? Auth::user()->school_id,
                ]);
                $ticket->updated_by = Auth::user()->id;
                $ticket->save();
                if ($r->active_status == 2) {

                    $data=new SmNotification();
                    $data->user_id = Auth::user()->id;                   
                    $data->role_id = Auth::user()->role_id;
                    $data->message = 'Complete this ticket';
                    $data->url = route('admin.ticket_view',$ticket->id);                   
                    $data->school_id =  Auth::user()->school_id;
                    $data->created_by = Auth::user()->id;
                    $data->updated_by = Auth::user()->id;
                    $data->save();

                    $message='Complete this ticket';
                    $userdata = new SmNotification();
                    $userdata->user_id = Auth::user()->id;                   
                    $userdata->role_id = Auth::user()->role_id;
                    $userdata->message = $message;
                    $userdata->url = route('user.ticket_view',$ticket->id);
                    $userdata->school_id =  Auth::user()->school_id;
                    $userdata->created_by = Auth::user()->id;
                    $userdata->updated_by = Auth::user()->id;
                    $userdata->save();
                }

            }
            if($r->filled('files')) {

                $path = 'public/uploads/ticket/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                if($r->get('files')){
                    foreach($r->get('files') as $file) {
                        $decode = json_decode($file);
                    
                        $image = $decode->data;  // your base64 encoded
                        $image = str_replace('data:image/png;base64,', '', $image);
                        $image = str_replace(' ', '+', $image);
                        $imageName = rand(1,10000).$decode->name;
                        $output = \File::put($path. '/' . $imageName, base64_decode($image));
                    
                        $newFile = new TicketMultiAttachment();
                        $newFile->file = $path.$imageName;
                        $newFile->ticket_id = $ticket->id;
                        $newFile->save();
                    }
                }
            }
            Toastr::success('Operation done successfully.', 'Success');
            return redirect()->route('admin.ticket_list');
        }catch(Throwable $e){
             ;
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }


    function ticket_search(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'priority' => 'required',
            'active_status' => 'required'
        ]);
        try{           
            $data = $this->loadData($request);
            return view('saas::tickets.ticket', $data);
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function ticket_delete_view($id){
        try{
            $url=route('admin.ticket_delete',$id);
            return view('saas::tickets.modal', compact('url'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function ticket_delete($id){
        try{
            Ticket::findOrFail($id)->delete();
            return redirect()->route('admin.ticket_list')->with('message-success','Ticket deleted !');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }
        
    public function comment_reply(Request $r){
        $r->validate([
            'comment' => 'required|string',
            'file'       =>'sometimes|required|mimes:doc,pdf,docx,jpg,jpeg,png',
        ]);

        try{
            $comment = Comment::find($r->comment_id);
            $data=Comment::create([
                'user_id' => Auth::user()->id,
                'client_id' => $comment->client_id,
                'ticket_id'   => $comment->ticket_id,
                'comment'   => $r->comment,
                'comment_id'   => $r->id,
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

            $message='Reply your comment';
            $userdata=new SmNotification();
            $userdata->user_id = Auth::user()->id;
            $userdata->role_id = Auth::user()->role_id;
            $userdata->message = $message;
            $userdata->url = route('user.ticket_view',$comment->ticket_id);
            $userdata->school_id =  Auth::user()->school_id;
            $userdata->academic_id =  Null;
            $userdata->created_by = Auth::user()->id;
            $userdata->updated_by = Auth::user()->id;
            $userdata->save();

            return redirect()->back();
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function openTicket(){

    }

    public function ticketList(){
        try{
            $category=Category::latest()->get();
            $priority=Priority::latest()->get();
            $ticket=Ticket::with('school', 'category', 'priority', 'agent_user')->where('assign_user','=',Auth::user()->school_id)->get();
            return view('saas::tickets.school_ticket', compact('category','priority','ticket'));
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function sendNotificationToUser($data)
    {

    }
    public function unAssignTicket()
    {
        $ticket = Ticket::whereNull('assign_user')->with('school', 'category', 'priority', 'agent_user')->get();
        $category=Category::latest()->get();
        $priority=Priority::latest()->get();
        $staffs = SmStaff::get(['id', 'user_id', 'full_name']);
        return view('saas::tickets.un_assign_ticket_list', compact('category','priority','ticket', 'staffs'));
    }
    public function unAssignTicketSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string',
            'priority' => 'required|string',
            'active_status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $this->loadData($request);
        return view('saas::tickets.un_assign_ticket_list', $data);
    }
    private function loadData($request):array
    {
        $data = [];
        $data['ticket'] = Ticket::with('school', 'category', 'priority', 'agent_user')
        ->orderBy('id','desc')
        ->when($request->type, function($q) use ($request){
            $q->whereNull('assign_user');
        })->when($request->category, function($q) use($request){
            $q->where('category_id', $request->category);
        })->when($request->priority, function($q) use ($request) {
            $q->where('priority_id', $request->priority);
        })->when($request->active_status, function($q) use ($request){
            $q->where('active_status', $request->active_status);
        })->when(auth()->user()->role_id !=1 , function($q) {
            $q->where('created_by', auth()->user()->id);
        })->when(auth()->user()->is_administrator != 'yes' && auth()->user()->role_id == 1, function($q){
            $q->where('school_id', auth()->user()->school_id);
        })->get();
      
        $data['category']= Category::latest()->get();
        $data['priority']= Priority::latest()->get();
        $data['staffs'] = SmStaff::get(['id', 'user_id', 'full_name']);
        $data['category_id'] = $request->category;
        $data['priority_id'] = $request->priority;
        $data['status_id'] = $request->active_status;
        return $data;
    }
    private function notification($request, $ticket)
    {
            $saas_admin = User::where('is_administrator', 'yes')
            ->where('role_id', 1)->first(['id', 'email', 'school_id']);
            $school_id = $$saas_admin->school_id;

            $data = new SmNotification();
            $data->message = 'New Ticket Created';
            $data->url = route('user.ticket_view', $ticket->id);
            $data->user_id = $saas_admin->id;
            $data->school_id =  $school_id;
            $data->academic_id =  Null;
            $data->created_by = Auth::user()->id;
            $data->updated_by = Auth::user()->id;
            $data->save();
    }
    public function viewTicketModal(int $ticket_image_id)
    {
        $ticketOrComment = TicketMultiAttachment::findOrFail($ticket_image_id);
        return view('saas::tickets.ticket_image_view', compact('ticketOrComment'));
    }
    public function viewCommentAttachmentModal(int $comment_attachment_id)
    {
        $ticketOrComment = CommentMultiAttachment::findOrFail($comment_attachment_id);              
        return view('saas::tickets.ticket_image_view', compact('ticketOrComment'));
    }
    public function deleteAttachment(Request $request)
    {
        try {
            $type = $request->type ?? 'ticket';
            $id = $request->id;
            if ($type == 'ticket') {
                TicketMultiAttachment::where('id', $id)->delete();
            }
            if($type == 'comment') {
                CommentMultiAttachment::where('id', $id)->delete();
            }
            return response()->json(['message'=>__('common.Operation successful')]);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()]);
        }
    }
}
