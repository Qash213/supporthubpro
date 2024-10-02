<?php

namespace App\Http\Controllers\User\Ticket;

use App\Http\Controllers\Controller;
use App\Jobs\MailSend;
use App\Jobs\SendSMS;
use Illuminate\Http\Request;

use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Category;
use App\Models\User;
use App\Mail\AppMailer;
use Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Hash;
use App\Notifications\TicketCreateNotifications;
use Mail;
use App\Mail\mailmailablesend;
use App\Models\Ratingtoken;
use App\Models\CCMAILS;
use App\Models\Groups;
use App\Models\tickethistory;
use Carbon\Carbon;
use App\Models\Holiday;

class CommentsController extends Controller
{
    public function postComment(Request $request,  $ticket_id)
    {
        $ticket_id = decrypt($ticket_id);
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        if($ticket->status == "Closed"){

             return redirect()->back()->with("error", lang('The ticket has been already closed.', 'alerts'));
        }
        else{
            $this->validate($request, [
                'comment' => 'required'
            ]);
            $tic = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
            if($tic->comments()->get() != null){
                $comm = $tic->comments()->update([
                    'display' => null
                ]);
            }
            $comment = Comment::create([
                'ticket_id' => $request->input('ticket_id'),
                'cust_id' => Auth::guard('customer')->user()->id,
                'user_id' => null,
                'display' => 1,
                'comment' => $request->input('comment')
            ]);

            foreach ($request->input('comments', []) as $file) {

                $provider =  storage()->provider;
                $provider::mediaupload($comment,'uploads/comment/' . $file,'comments');
            }

            // Closing the ticket
            if(request()->has(['status'])){

                $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
                if($request->input('status') == 'Closed'){
                    $ticket->status = $request->input('status');
                }else{
                    $ticket->status = $ticket->status == 'Re-Open' ? 'Inprogress' : $ticket->status;
                }
                $ticket->closing_ticket = now();
                $ticket->update();

                $ticketOwner = $ticket->user;

            }

            $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
            $ticket->last_reply = now();
            // Auto Overdue Ticket

            if(setting('AUTO_OVERDUE_TICKET') == 'no'){
                $ticket->auto_overdue_ticket = null;
                $ticket->overduestatus = null;
            }else{
                if(setting('AUTO_OVERDUE_TICKET_TIME') == '0'){
                    $ticket->auto_overdue_ticket = null;
                    $ticket->overduestatus = null;
                }else{
                    if(Auth::guard('customer')->check() && Auth::guard('customer')->user()){
                        if($ticket->status == 'Closed'){
                            $ticket->auto_overdue_ticket = null;
                            $ticket->overduestatus = null;
                        }
                        else{
                            $ticket->auto_overdue_ticket = now()->addDays(setting('AUTO_OVERDUE_TICKET_TIME'));
                            $ticket->overduestatus = null;
                        }
                    }
                }
            }
            // Auto Overdue Ticket

            // Auto Closing Ticket

            if(setting('AUTO_CLOSE_TICKET') == 'no'){
                $ticket->auto_close_ticket = null;
            }else{
                if(setting('AUTO_CLOSE_TICKET_TIME') == '0'){
                    $ticket->auto_close_ticket = null;
                }else{

                    if(Auth::guard('customer')->check() && Auth::guard('customer')->user()){
                        $ticket->auto_close_ticket = null;
                    }
                }
            }
            // End Auto Close Ticket

            // Auto Response Ticket

            if(setting('AUTO_RESPONSETIME_TICKET') == 'no'){
                $ticket->auto_replystatus = null;
            }else{
                if(setting('AUTO_RESPONSETIME_TICKET_TIME') == '0'){
                    $ticket->auto_replystatus = null;
                }else{
                    if(Auth::guard('customer')->check() && Auth::guard('customer')->user()){
                        $ticket->auto_replystatus = null;
                    }
                }
            }
            // End Auto Response Ticket

            if(request()->input(['status']) == 'Closed'){
                $ticket->replystatus = 'Solved';
            }else{
                $ticket->replystatus = 'Replied';
            }
            $ticket->update();

            if(request()->input(['status']) == 'Closed')
            {
                $tickethistory = new tickethistory();
                $tickethistory->ticket_id = $ticket->id;

                $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
                $tickethistory->overduestatus = $ticket->overduestatus;
                $tickethistory->status = $ticket->status;
                $tickethistory->replystatus = $ticket->replystatus;
                $tickethistory->currentAction = 'Closed';
                $tickethistory->username = $comment->cust->username;
                $tickethistory->type = $comment->cust->userType;

                $tickethistory->save();

                /**** End Close Ticket notificaton ****/

                $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

                $ticketData = [
                    'ticket_username' => $ticket->cust->username,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_title' => $ticket->subject,
                    'ticket_description' => $ticket->message,
                    'ticket_status' => $ticket->status,
                    'comment' => $comment->comment,
                    'ticket_customer_url' => route('loadmore.load_data', encrypt($ticket->ticket_id)),
                    'ticket_admin_url' => url('/admin/ticket-view/'.encrypt($ticket->ticket_id)),
                ];

                try{

                    if($ticket->category){
                        $notificationcat = $ticket->category->groupscategoryc()->get();
                        $groupIds = $notificationcat->pluck('group_id')->toArray();
                        $groupstatus = false;
                        foreach($groupIds as $groupid){
                        $groupexist = Groups::where('groupstatus', '1')->find($groupid);
                        if($groupexist){
                            $groupstatus = true;
                        }

                        }

                        $icc = array();

                        if ($groupstatus) {

                            foreach ($notificationcat as $igc) {
                                $groups = $igc->groupsc()
                                            ->where('groupstatus', 1)
                                            ->with('groupsuser')
                                            ->get();

                                foreach ($groups as $group) {
                                    $users = $group->groupsuser;

                                    foreach ($users as $user) {
                                        $icc[] = $user->users_id;
                                    }
                                }
                            }


                            if(!$icc){
                                $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                foreach($admins as $admin){
                                    $admin->notify(new TicketCreateNotifications($ticket));
                                    if($admin->usetting->emailnotifyon == 1){
                                        dispatch((new MailSend($admin->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                    }
                                }

                            }else{
                                if($ticket->myassignuser){
                                    $assignee = $ticket->ticketassignmutliples;
                                    foreach($assignee as $assignees){
                                        $user = User::where('id',$assignees->toassignuser_id)->get();
                                        foreach($user as $users){
                                            if($users->id == $assignees->toassignuser_id){
                                                $users->notify(new TicketCreateNotifications($ticket));
                                                if($users->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($users->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                                }
                                            }
                                        }
                                    }
                                }
                                else if ($ticket->selfassignuser_id) {
                                    $self = User::findOrFail($ticket->selfassignuser_id);
                                    $self->notify(new TicketCreateNotifications($ticket));
                                    if($self->usetting->emailnotifyon == 1){
                                        dispatch((new MailSend($self->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                    }
                                }
                                else if($icc ){
                                    $user = User::whereIn('id', $icc)->get();
                                    foreach($user as $users){
                                        $users->notify(new TicketCreateNotifications($ticket));
                                        if($users->usetting->emailnotifyon == 1){
                                            dispatch((new MailSend($users->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                        }
                                    }
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($admin->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                            }
                                        }
                                    }
                                }
                                else {
                                    $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                    foreach($users as $user){
                                        $user->notify(new TicketCreateNotifications($ticket));
                                        if($user->usetting->emailnotifyon == 1){
                                            dispatch((new MailSend($user->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                        }
                                    }
                                }
                            }
                        }else{
                            if($ticket->myassignuser){
                                $assignee = $ticket->ticketassignmutliples;
                                foreach($assignee as $assignees){
                                    $user = User::where('id',$assignees->toassignuser_id)->get();
                                    foreach($user as $users){
                                        if($users->id == $assignees->toassignuser_id){
                                            $users->notify(new TicketCreateNotifications($ticket));
                                            if($users->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($users->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                            }
                                        }
                                    }
                                }
                            } else if ($ticket->selfassignuser_id) {
                                $self = User::findOrFail($ticket->selfassignuser_id);
                                $self->notify(new TicketCreateNotifications($ticket));
                                if($self->usetting->emailnotifyon == 1){
                                    dispatch((new MailSend($self->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                }
                            } else {
                                foreach(usersdata() as $user){
                                    $user->notify(new TicketCreateNotifications($ticket));
                                    if($user->usetting->emailnotifyon == 1){
                                        dispatch((new MailSend($user->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                                    }
                                }
                            }
                        }
                    }else{
                        foreach(usersdata() as $user){
                            $user->notify(new TicketCreateNotifications($ticket));
                            if($user->usetting->emailnotifyon == 1){
                                dispatch((new MailSend($user->email, 'admin_sendemail_whenticketclosed', $ticketData)));
                            }
                        }

                    }

                    if($ticket->cust->phonesmsenable == 1 && $ticket->cust->phoneVerified == 1 && setting('twilioenable') == 'on'){
                        dispatch((new SendSMS($ticket->cust->phone, 'ticket_closed', $ticketData)));
                    }

                    dispatch((new MailSend($ticket->cust->email, 'customer_sendemail_whenticketclosed', $ticketData)));
                    if($ccemailsend->ccemails != null){
                        dispatch((new MailSend($ccemailsend->ccemails, 'CCmail_sendemail_whenticketclosed', $ticketData)));
                    }
                    /**** End Close Ticket mail and notificaton ****/


                }catch(\Exception $e){
                    if(setting('ticketrating') == 'on'){
                        return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
                    }else{

                        $ratingtoken =  Ratingtoken::create([

                            'token' => str_random(64),
                            'ticket_id' => $ticket->id,
                        ]);

                        return redirect()->route('rating', $ratingtoken->token);
                    }
                }
                if(setting('ticketrating') == 'on'){
                    return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
                }else{

                    $ratingtoken =  Ratingtoken::create([

                        'token' => str_random(64),
                        'ticket_id' => $ticket->id,
                    ]);

                    return redirect()->route('rating', $ratingtoken->token);
                }
            }else{

                $tickethistory = new tickethistory();
                $tickethistory->ticket_id = $ticket->id;

                $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
                $tickethistory->overduestatus = $ticket->overduestatus;
                $tickethistory->status = $ticket->status;
                $tickethistory->currentAction = 'Responded';
                $tickethistory->username = $comment->cust->username;
                $tickethistory->type = $comment->cust->userType;

                $tickethistory->save();

                $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

                $ticketData = [
                    'ticket_username' => $ticket->cust->username,
                    'ticket_title' => $ticket->subject,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_status' => $ticket->status,
                    'comment' => $comment->comment,
                    'ticket_customer_url' => route('loadmore.load_data', encrypt($ticket->ticket_id)),
                    'ticket_admin_url' => url('/admin/ticket-view/'.encrypt($ticket->ticket_id)),
                ];

                try{
                    /* customer reply to ticket notification and mail */
                    if($ticket->lastreply_mail == null){
                        if($ticket->category){
                            $notificationcat = $ticket->category->groupscategoryc()->get();
                            $groupIds = $notificationcat->pluck('group_id')->toArray();
                            $groupstatus = false;
                            foreach($groupIds as $groupid){
                               $groupexist = Groups::where('groupstatus', '1')->find($groupid);
                               if($groupexist){
                                $groupstatus = true;
                               }

                            }

                            $icc = array();

                            if ($groupstatus) {

                                foreach ($notificationcat as $igc) {
                                    $groups = $igc->groupsc()
                                                  ->where('groupstatus', 1)
                                                  ->with('groupsuser')
                                                  ->get();

                                    foreach ($groups as $group) {
                                        $users = $group->groupsuser;

                                        foreach ($users as $user) {
                                            $icc[] = $user->users_id;
                                        }
                                    }
                                }


                                if(!$icc){
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                    }

                                }else{
                                    if($ticket->myassignuser){
                                        $assignee = $ticket->ticketassignmutliples;
                                        foreach($assignee as $assignees){
                                            $user = User::where('id',$assignees->toassignuser_id)->where('status', 1)->get();
                                            foreach($user as $users){
                                                if($users->id == $assignees->toassignuser_id){
                                                    $users->notify(new TicketCreateNotifications($ticket));
                                                    if($users->usetting->emailnotifyon == 1){
                                                        dispatch((new MailSend($users->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                    }
                                                }
                                            }
                                        }
                                        $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                        foreach($admins as $admin){
                                            if($admin->getRoleNames()[0] == 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($ticket));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                }
                                            }
                                        }
                                    }
                                    else if ($ticket->selfassignuser_id) {
                                        $self = User::where('status', 1)->findOrFail($ticket->selfassignuser_id);
                                        $self->notify(new TicketCreateNotifications($ticket));
                                        if($self->usetting->emailnotifyon == 1){
                                            dispatch((new MailSend($self->email, 'admin_send_email_ticket_reply', $ticketData)));
                                        }
                                        $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                        foreach($admins as $admin){
                                            if($admin->getRoleNames()[0] == 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($ticket));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                }
                                            }
                                        }
                                    }
                                    else if($icc ){
                                        $user = User::whereIn('id', $icc)->where('status', 1)->get();
                                        foreach($user as $users){
                                            $users->notify(new TicketCreateNotifications($ticket));
                                            if($users->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($users->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                        $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                        foreach($admins as $admin){
                                            if($admin->getRoleNames()[0] == 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($ticket));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                        foreach($users as $user){
                                            $user->notify(new TicketCreateNotifications($ticket));
                                            if($user->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($user->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                    }
                                }
                            }else{
                                if($ticket->myassignuser){
                                    $assignee = $ticket->ticketassignmutliples;
                                    foreach($assignee as $assignees){
                                        $user = User::where('id',$assignees->toassignuser_id)->where('status', 1)->get();
                                        foreach($user as $users){
                                            if($users->id == $assignees->toassignuser_id){
                                                $users->notify(new TicketCreateNotifications($ticket));
                                                if($users->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($users->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                }
                                            }
                                        }
                                    }
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                    }
                                } else if ($ticket->selfassignuser_id) {
                                    $self = User::where('status', 1)->findOrFail($ticket->selfassignuser_id);
                                    $self->notify(new TicketCreateNotifications($ticket));
                                    if($self->usetting->emailnotifyon == 1){
                                        dispatch((new MailSend($self->email, 'admin_send_email_ticket_reply', $ticketData)));
                                    }
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                    }
                                } else {
                                    foreach(usersdata() as $user){
                                        $user->notify(new TicketCreateNotifications($ticket));
                                        if($user->usetting->emailnotifyon == 1){
                                            dispatch((new MailSend($user->email, 'admin_send_email_ticket_reply', $ticketData)));
                                        }
                                    }
                                }
                            }
                        }else{
                            foreach(usersdata() as $users){
                                $users->notify(new TicketCreateNotifications($ticket));
                                if($users->usetting->emailnotifyon == 1){
                                    dispatch((new MailSend($users->email, 'admin_send_email_ticket_reply', $ticketData)));
                                }
                            }
                        }
                    }
                    if($ticket->lastreply_mail != null){
                        if($ticket->category){
                            $notificationcat = $ticket->category->groupscategoryc()->get();
                            $groupIds = $notificationcat->pluck('group_id')->toArray();
                            $groupstatus = false;
                            foreach($groupIds as $groupid){
                            $groupexist = Groups::where('groupstatus', '1')->find($groupid);
                            if($groupexist){
                                $groupstatus = true;
                            }

                            }

                            $icc = array();

                            if ($groupstatus) {

                                foreach ($notificationcat as $igc) {
                                    $groups = $igc->groupsc()
                                                ->where('groupstatus', 1)
                                                ->with('groupsuser')
                                                ->get();

                                    foreach ($groups as $group) {
                                        $users = $group->groupsuser;

                                        foreach ($users as $user) {
                                            $icc[] = $user->users_id;
                                        }
                                    }
                                }


                                if(!$icc){
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                    }

                                }else{
                                    if($ticket->myassignuser_id){
                                        $assignee = $ticket->ticketassignmutliples;
                                        foreach($assignee as $assignees){
                                            $user = User::where('id',$assignees->toassignuser_id)->where('status', 1)->get();
                                            foreach($user as $users){
                                                if($users->id == $assignees->toassignuser_id){
                                                    $users->notify(new TicketCreateNotifications($ticket));
                                                    if($users->usetting->emailnotifyon == 1){
                                                        dispatch((new MailSend($users->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                    }
                                                }
                                            }
                                        }
                                        $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                        foreach($admins as $admin){
                                            if($admin->getRoleNames()[0] == 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($ticket));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                }
                                            }
                                        }
                                    } else if ($ticket->selfassignuser_id) {

                                        $self = User::where('status', 1)->findOrFail($ticket->selfassignuser_id);
                                        $self->notify(new TicketCreateNotifications($ticket));
                                        if($self->usetting->emailnotifyon == 1){
                                            dispatch((new MailSend($self->email, 'admin_send_email_ticket_reply', $ticketData)));
                                        }
                                        $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                        foreach($admins as $admin){
                                            if($admin->getRoleNames()[0] == 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($ticket));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                }
                                            }
                                        }
                                    } else if($icc){
                                        $user = User::whereIn('id', $icc)->where('status', 1)->get();
                                        foreach($user as $users){
                                            $users->notify(new TicketCreateNotifications($ticket));
                                            if($users->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($users->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                        $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                        foreach($admins as $admin){
                                            if($admin->getRoleNames()[0] == 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($ticket));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                }
                                            }
                                        }
                                    }else {
                                        $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                        foreach($users as $user){
                                            $user->notify(new TicketCreateNotifications($ticket));
                                            if($user->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($user->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                    }


                                }
                            }else{
                                if($ticket->myassignuser){
                                    $assignee = $ticket->ticketassignmutliples;
                                    foreach($assignee as $assignees){
                                        $user = User::where('id',$assignees->toassignuser_id)->where('status', 1)->get();
                                        foreach($user as $users){
                                            if($users->id == $assignees->toassignuser_id){
                                                $users->notify(new TicketCreateNotifications($ticket));
                                                if($users->usetting->emailnotifyon == 1){
                                                    dispatch((new MailSend($users->email, 'admin_send_email_ticket_reply', $ticketData)));
                                                }
                                            }
                                        }
                                    }
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                    }
                                } else if ($ticket->selfassignuser_id) {
                                    $self = User::where('status', 1)->findOrFail($ticket->selfassignuser_id);
                                    $self->notify(new TicketCreateNotifications($ticket));
                                    if($self->usetting->emailnotifyon == 1){
                                        dispatch((new MailSend($self->email, 'admin_send_email_ticket_reply', $ticketData)));
                                    }
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                dispatch((new MailSend($admin->email, 'admin_send_email_ticket_reply', $ticketData)));
                                            }
                                        }
                                    }
                                } else {
                                    foreach(usersdata() as $user){
                                        $user->notify(new TicketCreateNotifications($ticket));
                                        if($user->usetting->emailnotifyon == 1){
                                            dispatch((new MailSend($user->email, 'admin_send_email_ticket_reply', $ticketData)));
                                        }
                                    }
                                }
                            }
                        }else{
                            foreach(usersdata() as $users){
                                $users->notify(new TicketCreateNotifications($ticket));
                                if($users->usetting->emailnotifyon == 1){
                                    dispatch((new MailSend($users->email, 'admin_send_email_ticket_reply', $ticketData)));
                                }
                            }
                        }

                    }

                    $today = Carbon::today();
                    $holidays = Holiday::whereDate('startdate', '<=', $today)->whereDate('enddate', '>=', $today)->where('status','1')->get();

                    if ($holidays->isNotEmpty() && setting('24hoursbusinessswitch') != 'on') {
                        dispatch((new MailSend($ticket->cust->email, 'customer_send_ticket_created_that_holiday_or_announcement', $ticketData)));
                        if($ccemailsend->ccemails != null){
                            dispatch((new MailSend($ccemailsend->ccemails, 'customer_send_ticket_created_that_holiday_or_announcement', $ticketData)));
                        }
                    }else{
                        if($ccemailsend->ccemails != null){
                            dispatch((new MailSend($ccemailsend->ccemails, 'customer_send_ticket_reply', $ticketData)));
                        }
                    }

                    /* End customer reply to ticket notification and mail */

                }catch(\Exception $e){
                    return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
                }

                return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
            }
        }

    }

    public function storeMedia(Request $request)
    {
        $path = public_path('uploads/comment');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = $file->getClientOriginalName();

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }


    public function updateedit(Request $request, $id){
        if ($request->has('message')) {

            $this->validate($request, [
                'message' => 'required'
            ]);
            $ticket = Ticket::findOrFail($id);
            $ticket->message = $request->input('message');

            $ticket->update();
            return redirect()->back()->with('success', lang('Updated Successfully', 'alerts'));

        }else{
            $this->validate($request, [
                'editcomment' => 'required'
            ]);
            $comment = Comment::findOrFail($id);
            $comment->comment = $request->input('editcomment');

            $comment->update();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $comment->ticket->id;

            $tickethistory->ticketnote = $comment->ticket->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $comment->ticket->overduestatus;
            $tickethistory->status = $comment->ticket->status;
            $tickethistory->currentAction = 'Comment Modified';
            $tickethistory->username = $comment->cust->username;
            $tickethistory->type = $comment->cust->userType;

            $tickethistory->save();

            return redirect()->back()->with('success', lang('Updated Successfully', 'alerts'));
        }


    }

    public function editticketsubject(Request $request)
    {
        $ticket = Ticket::findOrFail($request->ticketId);
        $ticket->subject = $request->editsubject;
        $ticket->message = strip_tags($request->editmessage);
        $ticket->save();

        return response()->json(['ticketdata' => $ticket, 'success' => lang('Updated successfully.', 'alerts')]);

    }

    public function imagedestroy($id)
        {   //For Deleting Users
            $commentss = Media::findOrFail($id);
            $commentss->delete();
            return response()->json([
                'success' => lang('The image has been deleted successfully!', 'alerts')
            ]);
        }

}

