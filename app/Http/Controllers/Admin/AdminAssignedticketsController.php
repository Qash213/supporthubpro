<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\MailSend;
use Illuminate\Http\Request;

use App\Models\Ticket\Ticket;
use App\Models\User;
use Auth;
use App\Models\tickethistory;

use Mail;
use App\Mail\mailmailablesend;
use App\Notifications\TicketAssignNotification;

class AdminAssignedticketsController extends Controller
{

    public function create(Request $request)
    {
        $this->validate($request, [
            'assigned_user_id' => 'required',
        ]);

        $calID = Ticket::find($request->assigned_id);
        $calID->myassignuser_id	 = Auth::id();
        $calID->selfassignuser_id = null;
        $calID->save();

        $calID->ticketassignmutliple()->sync($request->assigned_user_id);

        // user informatiom
        // $users = User::with('roles')->findOrFail($request->assigned_user_id);
        $users = User::with('roles')->where('status', 1)->findOrFail($request->assigned_user_id);
        // Assignee

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $calID->id;

        $tickethistory->ticketnote = $calID->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $calID->overduestatus;
        $tickethistory->status = $calID->status;
        $tickethistory->assignUser = $users;
        $tickethistory->currentAction = 'Assigner';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        $ticketData = [
            'ticket_username' => $calID->cust->username,
            'ticket_id' => $calID->ticket_id,
            'ticket_title' => $calID->subject,
            'ticket_description' => $calID->message,
            'ticket_customer_url' => route('gusetticket', encrypt($calID->ticket_id)),
            'ticket_admin_url' => url('/admin/ticket-view/'.encrypt($calID->ticket_id)),
        ];


        try{

            $assignee = $calID->ticketassignmutliples;
            foreach($assignee as $assignees){
                $user = User::where('id',$assignees->toassignuser_id)->get();
                foreach($user as $users){

                    if($users->id == $assignees->toassignuser_id){
                            $users->notify(new TicketAssignNotification($calID));
                            if($users->usetting->emailnotifyon == 1){
                                dispatch((new MailSend($users->email, 'when_ticket_assign_to_other_employee', $ticketData)));
                            }
                    }
                }
            }

        }catch(\Exception $e){
            return response()->json(['code'=>200, 'success'=> lang('The ticket was successfully assigned.', 'alerts')], 200);
        }

        return response()->json(['code'=>200, 'success'=> lang('The ticket was successfully assigned.', 'alerts')], 200);

    }

    public function show(Request $req, $id){

        if($req->ajax())
        {

            $output = '';
            $id = decrypt($id);
            $assign = Ticket::find($id);
            $assugnuser_id = $assign->ticketassignmutliples->pluck('toassignuser_id')->toArray();

            $data = User::where('status', 1)->get();

            $total_row = $data->count();

            if($total_row > 0){
                $output .='<option label="Select Agent"></option>';
                foreach($data as $row){
                    if(in_array(Auth::user()->id, $assugnuser_id)){
                        $output .= '
                        <option  value="'.$row->id.'"' .(in_array($row->id, $assugnuser_id)? 'selected': '').  '>'.$row->name.' ('.(!empty($row->getRoleNames()[0])? $row->getRoleNames()[0] : '').')</option>

                        ';
                    }else{
                        if(Auth::user()->id != $row->id){
                            if(!empty($assugnuser_id)){
                                $output .= '
                                <option  value="'.$row->id.'"' .(in_array($row->id, $assugnuser_id)? 'selected': '').  '>'.$row->name.' ('.(!empty($row->getRoleNames()[0])? $row->getRoleNames()[0] : '').')</option>

                                ';
                            }else{
                                $output .= '
                                <option  value="'.$row->id.'">'.$row->name.' ('.(!empty($row->getRoleNames()[0])? $row->getRoleNames()[0] : '').')</option>

                                ';
                            }
                        }
                    }
                }

            }
            $data = array(
                'assign_user_exist'=> !empty($assugnuser_id) ? 'yes' : 'no',
                'assign_data'=> $assign,
                'table_data' => $output,
                'total_data' => $total_row
            );

            return response()->json($data);
        }

    }

    public function update(Request $req, $id)
    {
        $id = decrypt($id);
        $calID = Ticket::find($id);
        $calID->myassignuser_id	 = null;
        $calID->selfassignuser_id = null;
        $calID->save();
        $calID->ticketassignmutliple()->detach($req->assigned_userid);

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $calID->id;

        $tickethistory->ticketnote = $calID->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $calID->overduestatus;
        $tickethistory->status = $calID->status;
        $tickethistory->currentAction = 'UnAssigned Ticket';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        return response()->json(['data'=> $calID, 'success'=> lang('Updated successfully', 'alerts')]);
    }
}
