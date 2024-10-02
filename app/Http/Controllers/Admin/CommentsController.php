<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\MailSend;
use App\Jobs\SendEmails;
use App\Jobs\SendSMS;
use Illuminate\Http\Request;

use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Category;
use App\Models\User;
use App\Models\Customer;
use Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Hash;
use App\Notifications\TicketCreateNotifications;
use App\Mail\mailmailablesend;
use Mail;
use App\Models\Ratingtoken;
use App\Models\CCMAILS;
use App\Models\tickethistory;
use App\Models\EmailTemplate;
use App\Models\Groups;
use App\Models\Imap_setting;
use App\Models\TicketDraft;
use Exception;
use GuzzleHttp\Psr7\stream_for;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\Mime\Email;
use Swift_Attachment;
use Symfony\Component\Mime\Part\DataPart;
use Twilio\Rest\Client;
use App\Notifications\TicketDraftNotification;
use DOMDocument;

class CommentsController extends Controller
{
    public function ticketdraftimage(Request $request, $id)
    {
        $im = Media::find($id);
        if ($im) {
            $im->delete();

            return response()->json(['success' => lang('The image is deleted successfully.', 'alerts')]);
        } else {
            abort(404);
        }
    }

    public function ticketdraft(Request $request)
    {
        $this->validate($request, [
            'ticket_id' => 'required',
            'comment' => 'required'
        ]);

        $draftiId = $request->draft_id;

        $draftdata =  [
            'ticket_id' => $request->ticket_id,
            'description' => $request->comment,
        ];


        $ticketdraft = TicketDraft::updateOrCreate(['id' => $draftiId], $draftdata);

        foreach ($request->input('comments', []) as $file) {
            $provider =  storage()->provider;
            $provider::mediaupload($ticketdraft, 'uploads/comment/' . $file, 'ticketdrafts');
        }

        $ticket = Ticket::findOrFail($request->ticket_id);

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        if ($draftiId != null) {
            $tickethistory->currentAction = 'Tiket Draft Modified';
        } else {
            $tickethistory->currentAction = 'Ticket Draft Created';
        }
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        if ($draftiId == null) {
            $ticketData = [
                'username' => Auth::user()->name,
                'ticket_id' => $ticket->ticket_id,
                'ticket_description' => $request->comment,
                'created_or_respond' => 'Created',
                'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
            ];
        } else {
            $ticketData = [
                'username' => Auth::user()->name,
                'ticket_id' => $ticket->ticket_id,
                'ticket_description' => $request->comment,
                'created_or_respond' => 'Updated',
                'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
            ];
        }

        try {
            $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();


            if ($ticket->category) {
                $notificationcat = $ticket->category->groupscategoryc()->get();
                $groupIds = $notificationcat->pluck('group_id')->toArray();
                $groupstatus = false;
                foreach ($groupIds as $groupid) {
                    $groupexist = Groups::where('groupstatus', '1')->find($groupid);
                    if ($groupexist) {
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
                    if (!$icc) {
                        $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();

                        foreach ($admins as $admin) {
                            $admin->notify(new TicketDraftNotification($ticketData));
                            if ($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($admin->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                            }
                        }
                    } else {

                        if ($ticket->myassignuser_id) {
                            $assignee = $ticket->ticketassignmutliples;
                            foreach ($assignee as $assignees) {
                                $user = User::where('id', $assignees->toassignuser_id)->where('status', 1)->get();
                                foreach ($user as $users) {
                                    $users->notify(new TicketDraftNotification($ticketData));
                                    if ($users->id == $assignees->toassignuser_id && $users->usetting->emailnotifyon == 1) {
                                        dispatch((new MailSend($users->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                                    }
                                }
                            }
                            $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                            foreach ($admins as $admin) {
                                $admin->notify(new TicketDraftNotification($ticketData));
                                if ($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($admin->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                                }
                            }
                        } else if ($ticket->selfassignuser_id) {

                            $self = User::where('status', 1)->findOrFail($ticket->selfassignuser_id);
                            $self->notify(new TicketDraftNotification($ticketData));
                            if ($self->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($self->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                            }
                            $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                            foreach ($admins as $admin) {
                                $admin->notify(new TicketDraftNotification($ticketData));
                                if ($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($admin->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                                }
                            }
                        } else if ($icc) {

                            $user = User::whereIn('id', $icc)->where('status', 1)->get();
                            foreach ($user as $users) {
                                $users->notify(new TicketDraftNotification($ticketData));
                                if ($users->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($users->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                                }
                            }


                            $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();

                            foreach ($admins as $admin) {
                                $admin->notify(new TicketDraftNotification($ticketData));
                                if ($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($admin->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                                }
                            }
                        } else {
                            $users = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                            foreach ($users as $user) {
                                $user->notify(new TicketDraftNotification($ticketData));
                                if ($user->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($user->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                                }
                            }
                        }
                    }
                } else {
                    if ($ticket->myassignuser) {
                        $assignee = $ticket->ticketassignmutliples;
                        foreach ($assignee as $assignees) {
                            $user = User::where('id', $assignees->toassignuser_id)->where('status', 1)->get();
                            foreach ($user as $users) {
                                $users->notify(new TicketDraftNotification($ticketData));
                                if ($users->id == $assignees->toassignuser_id && $users->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($users->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                                }
                            }
                        }
                        $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new TicketDraftNotification($ticketData));
                            if ($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($admin->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                            }
                        }
                    } else if ($ticket->selfassignuser_id) {
                        $self = User::findOrFail($ticket->selfassignuser_id);
                        $self->notify(new TicketDraftNotification($ticketData));
                        if ($self->usetting->emailnotifyon == 1) {
                            dispatch((new MailSend($self->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                        }
                        $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new TicketDraftNotification($ticketData));
                            if ($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($admin->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                            }
                        }
                    } else {
                        foreach (usersdata() as $user) {
                            $user->notify(new TicketDraftNotification($ticketData));
                            if ($user->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($user->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                            }
                        }
                    }
                }
            } else {
                $user = User::where('id', $ticket->lastreply_mail)->where('status', 1)->get();
                foreach ($user as $users) {
                    $users->notify(new TicketDraftNotification($ticketData));
                    if ($users->usetting->emailnotifyon == 1) {
                        dispatch((new MailSend($users->email, 'Send_email_to_admin_when_ticket_draft_created', $ticketData)));
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(['success' => lang('The ticket Draft was successfully created.', 'alerts')]);
        }

        return response()->json(['success' => lang('The ticket Draft was successfully created.', 'alerts')]);
    }

    public function draftdelete(Request $request)
    {

        $ticketdraft = TicketDraft::find($request->id);

        $ticket = Ticket::find($ticketdraft->ticket_id);

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->currentAction = 'Tiket Draft deleted';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        if ($ticketdraft) {
            foreach ($ticketdraft->getMedia('ticketdrafts') as $ticketdr) {
                $ticketdr->delete();
            }
            $ticketdraft->delete();
        }
        return response()->json(['success' => lang('The draft is deleted successfully.', 'alerts')]);
    }

    public function postComment(Request $request,  $ticket_id)
    {
        $ticket_id = decrypt($ticket_id);

        if ($request->status == 'Solved') {

            $this->validate($request, [
                'comment' => 'required'
            ]);
            $comment = Comment::create([
                'ticket_id' => $request->input('ticket_id'),
                'user_id' => Auth::user()->id,
                'cust_id' => null,
                'comment' => $request->input('comment'),
            ]);

            $ticketdraft = TicketDraft::where('ticket_id', $request->ticket_id)->first();

            if ($ticketdraft) {

                foreach ($ticketdraft->getMedia('ticketdrafts') as $ticketdr) {
                    if (!file_exists('public/temp/')) {
                        mkdir('public/temp/', 0777, true);
                    }
                    $localTempFilePath = 'public/temp/' . $ticketdr->file_name;
                    $existprovider = existprovider($ticketdr->disk);
                    if ($existprovider)
                        $content = $existprovider->provider::getdraft($ticketdr);

                    file_put_contents($localTempFilePath, $content);
                    $provider =  storage()->provider;

                    $media = $provider::draftupload($comment, $localTempFilePath);
                    $ticketdr->delete();
                }
                $ticketdraft->delete();
            }


            foreach ($request->input('comments', []) as $file) {
                $provider =  storage()->provider;
                $provider::mediaupload($comment, 'uploads/comment/' . $file, 'comments');
            }

            $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
            $ticket->status = 'Closed';
            $ticket->replystatus = $request->input('status');
            // Auto Close Ticket
            $ticket->auto_close_ticket = null;
            // Auto Response Ticket
            $ticket->auto_replystatus = null;
            $ticket->last_reply = now();
            $ticket->closing_ticket = now();
            $ticket->auto_overdue_ticket = null;
            $ticket->overduestatus = null;
            $ticket->importantticket = null;
            $ticket->closedby_user = Auth::id();
            $ticket->lastreply_mail = Auth::id();

            if ($request->reopen_on_off == null) {
                $ticket->ticketreopen = 'stopreopen';
            } else {
                $ticket->ticketreopen = null;
            }
            $ticket->update();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;

            $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $ticket->overduestatus;
            $tickethistory->status = $ticket->status;
            $tickethistory->replystatus = $ticket->replystatus;
            $tickethistory->currentAction = 'Closed';
            $tickethistory->username = $comment->user->name;
            $tickethistory->type = $comment->user->getRoleNames()[0];

            $tickethistory->save();

            $cust = Customer::find($ticket->cust_id);
            $cust->notify(new TicketCreateNotifications($ticket));

            // create ticket notification
            if ($ticket->category) {
                $notificationcat = $ticket->category->groupscategoryc()->get();
                $groupIds = $notificationcat->pluck('group_id')->toArray();
                $groupstatus = false;
                foreach ($groupIds as $groupid) {
                    $groupexist = Groups::where('groupstatus', '1')->find($groupid);
                    if ($groupexist) {
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

                    if (!$icc) {
                        $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new TicketCreateNotifications($ticket));
                        }
                    } else {

                        $user = User::whereIn('id', $icc)->get();
                        foreach ($user as $users) {
                            $users->notify(new TicketCreateNotifications($ticket));
                        }
                        $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                        foreach ($admins as $admin) {
                            if ($admin->getRoleNames()[0] == 'superadmin') {
                                $admin->notify(new TicketCreateNotifications($ticket));
                            }
                        }
                    }
                } else {
                    foreach (usersdata() as $admin) {
                        $admin->notify(new TicketCreateNotifications($ticket));
                    }
                }
            }
            // Notification category Empty
            if (!$ticket->category) {
                $admins = User::get();
                foreach ($admins as $admin) {
                    $admin->notify(new TicketCreateNotifications($ticket));
                }
            }


            $ratingtoken =  Ratingtoken::create([

                'token' => str_random(64),
                'ticket_id' => $ticket->id,
            ]);

            $closed_agent = User::findOrFail(Auth::id());
            $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();


            if (setting('ticketrating') == 'on') {
                if ($ticket->cust->userType == 'Guest') {
                    $ticketData = [
                        'closed_agent_name' => $closed_agent->name,
                        'closed_agent_role' => $closed_agent->getRoleNames()[0],
                        'ticket_username' => $ticket->cust->username,
                        'ticket_title' => $ticket->subject,
                        'ticket_id' => $ticket->ticket_id,
                        'comment' => $comment->comment,
                        'ticket_status' => $ticket->status,
                        'ticket_customer_url' => route('gusetticket', encrypt($ticket->ticket_id)),
                        'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
                    ];
                }
                if ($ticket->cust->userType == 'Customer') {
                    $ticketData = [
                        'closed_agent_name' => $closed_agent->name,
                        'closed_agent_role' => $closed_agent->getRoleNames()[0],
                        'ticket_username' => $ticket->cust->username,
                        'ticket_title' => $ticket->subject,
                        'ticket_id' => $ticket->ticket_id,
                        'comment' => $comment->comment,
                        'ticket_status' => $ticket->status,
                        'ticket_customer_url' => route('loadmore.load_data', encrypt($ticket->ticket_id)),
                        'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
                    ];
                }
            } else {
                if ($ticket->cust->userType == 'Guest') {
                    $ticketData = [
                        'closed_agent_name' => $closed_agent->name,
                        'closed_agent_role' => $closed_agent->getRoleNames()[0],
                        'ticket_username' => $ticket->cust->username,
                        'ticket_title' => $ticket->subject,
                        'ticket_id' => $ticket->ticket_id,
                        'comment' => $comment->comment,
                        'ticket_status' => $ticket->status,
                        'ratinglink' => route('guest.rating', $ratingtoken->token),
                        'ticket_customer_url' => route('gusetticket', encrypt($ticket->ticket_id)),
                        'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
                    ];
                }
                if ($ticket->cust->userType == 'Customer') {
                    $ticketData = [
                        'closed_agent_name' => $closed_agent->name,
                        'closed_agent_role' => $closed_agent->getRoleNames()[0],
                        'ticket_username' => $ticket->cust->username,
                        'ticket_title' => $ticket->subject,
                        'ticket_id' => $ticket->ticket_id,
                        'comment' => $comment->comment,
                        'ticket_status' => $ticket->status,
                        'ratinglink' => route('guest.rating', $ratingtoken->token),
                        'ticket_customer_url' => route('loadmore.load_data', encrypt($ticket->ticket_id)),
                        'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
                    ];
                }
            }

            try {
                if($ticket->cust->phonesmsenable == 1 && $ticket->cust->phoneVerified == 1 && setting('twilioenable') == 'on'){
                    dispatch((new SendSMS($ticket->cust->phone, 'ticket_closed', $ticketData)));
                }

                if ($ticket->tickettype == 'emalitoticket') {
                    $replySubject = 'Re: ' . $ticket->subject;
                    if ($request->rating_on_off == null) {
                        $emailtempcode = 'send_mail_to_customer_when_ticket_closed_by_admin';
                    } else {
                        $emailtempcode = 'customer_rating';
                    }

                    $emailtemplate = EmailTemplate::where('code', $emailtempcode)->first();
                    $body = $emailtemplate->body;

                    $imaps = Imap_setting::find($ticket->imap_id);
                    $imap_username = $imaps->imap_username;


                    foreach ($ticketData as $key => $value) {
                        $body = str_replace('{{' . $key . '}}', $value, $body);
                        $body = str_replace('{{ ' . $key . ' }}', $value, $body);
                    }
                    $fileNames = [];


                    $email = (new Email())->html($body);

                    Mail::raw('', function ($message) use ($email, $ticket, $imap_username, $replySubject, $comment, &$fileNames) {
                        $message->from($imap_username)
                            ->to($ticket->cust->email)
                            ->subject($replySubject)
                            ->getHeaders()->addTextHeader('In-Reply-To', '<' . $ticket->MessageID . '>');

                        foreach ($comment->getMedia('comments') as $commentss) {
                            if (!file_exists('public/temp/')) {
                                mkdir('public/temp/', 0777, true);
                            }
                            $localTempFilePath = 'public/temp/' . $commentss->file_name;
                            $existprovider = existprovider($commentss->disk);
                            if ($existprovider)
                                $contentPath = $existprovider->provider::tempImage($commentss, $localTempFilePath);

                            $message->attach($contentPath);
                            $fileNames[] = $localTempFilePath;
                        }

                        $message->getHeaders()->addTextHeader('References', '<' . $ticket->MessageID . '>');
                        $message->setBody($email->getBody(), 'text/html');
                    });

                    // Mail::send([], [], function ($message) use ($ticket, $replySubject, $body, $comment,$imap_username, &$fileNames) {
                    //     $message->to($ticket->cust->email)
                    //         ->from($imap_username)
                    //         ->subject($replySubject)
                    //         ->setBody($body, 'text/html');
                    //     foreach ($comment->getMedia('comments') as $commentss) {
                    //         if (!file_exists('public/temp/')) {
                    //             mkdir('public/temp/', 0777, true);
                    //         }
                    //          $localTempFilePath = 'public/temp/' . $commentss->file_name;
                    //          $existprovider = existprovider($commentss->disk);
                    //          if ($existprovider)
                    //              $contentPath = $existprovider->provider::tempImage($commentss,$localTempFilePath);

                    //         $message->attach($contentPath);
                    //         $fileNames[] = $localTempFilePath;
                    //     }
                    //     $headers = $message->getHeaders();
                    //     $headers->removeAll('In-Reply-To');
                    //     $headers->removeAll('References');
                    //     $headers->addTextHeader('In-Reply-To', '<' . $ticket->MessageID . '>');
                    //     $headers->addTextHeader('References', '<' . $ticket->MessageID . '>');
                    // });

                    foreach ($fileNames as $filePath) {
                        if (file_exists($filePath)) {
                            \File::delete($filePath);
                        }
                    }
                } else {
                    if ($request->rating_on_off == null) {
                        dispatch((new MailSend($ticket->cust->email, 'send_mail_to_customer_when_ticket_closed_by_admin', $ticketData)));
                    } else {
                        dispatch((new MailSend($ticket->cust->email, 'customer_rating', $ticketData)));
                    }
                }

                if ($ticket->category) {
                    $notificationcat = $ticket->category->groupscategoryc()->get();
                    $groupIds = $notificationcat->pluck('group_id')->toArray();
                    $groupstatus = false;
                    foreach ($groupIds as $groupid) {
                        $groupexist = Groups::where('groupstatus', '1')->find($groupid);
                        if ($groupexist) {
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

                        if (!$icc) {
                            $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach ($admins as $admin) {
                                if ($admin->getRoleNames()[0] != 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($admin->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                                }
                            }
                        } else {

                            if ($ticket->myassignuser) {
                                $assignee = $ticket->ticketassignmutliples;
                                foreach ($assignee as $assignees) {
                                    $user = User::where('id', $assignees->toassignuser_id)->get();
                                    foreach ($user as $users) {
                                        if ($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin' && $users->usetting->emailnotifyon == 1) {
                                            dispatch((new MailSend($users->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                                        }
                                    }
                                }
                            } else if ($ticket->selfassignuser_id) {
                                $self = User::findOrFail($ticket->selfassignuser_id);
                                if ($self->getRoleNames()[0] != 'superadmin' && $self->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($self->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                                }
                            } else if ($icc) {
                                $user = User::whereIn('id', $icc)->get();
                                foreach ($user as $users) {
                                    if ($users->usetting->emailnotifyon == 1) {
                                        dispatch((new MailSend($users->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                                    }
                                }
                            } else {
                                $users = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                foreach ($users as $user) {
                                    if ($user->getRoleNames()[0] != 'superadmin' && $user->usetting->emailnotifyon == 1) {
                                        dispatch((new MailSend($user->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                                    }
                                }
                            }
                        }
                    } else {

                        if ($ticket->myassignuser) {
                            $assignee = $ticket->ticketassignmutliples;
                            foreach ($assignee as $assignees) {
                                $user = User::where('id', $assignees->toassignuser_id)->get();
                                foreach ($user as $users) {
                                    if ($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin' && $users->usetting->emailnotifyon == 1) {
                                        dispatch((new MailSend($users->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                                    }
                                }
                            }
                        } else if ($ticket->selfassignuser_id) {
                            $self = User::findOrFail($ticket->selfassignuser_id);
                            if ($self->getRoleNames()[0] != 'superadmin' && $self->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($self->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                            }
                        } else {
                            foreach (usersdata() as $user) {
                                if ($user->getRoleNames()[0] != 'superadmin' && $user->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($user->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                                }
                            }
                        }
                    }
                }
                if (!$ticket->category) {

                    if ($ticket->myassignuser) {
                        $assignee = $ticket->ticketassignmutliples;
                        foreach ($assignee as $assignees) {
                            $user = User::where('id', $assignees->toassignuser_id)->get();
                            foreach ($user as $users) {
                                if ($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin' && $users->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($users->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                                }
                            }
                        }
                    } else if ($ticket->selfassignuser_id) {
                        $self = User::findOrFail($ticket->selfassignuser_id);
                        if ($self->getRoleNames()[0] != 'superadmin' && $self->usetting->emailnotifyon == 1) {
                            dispatch((new MailSend($self->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                        }
                    } else {

                        $users = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                        foreach ($users as $user) {
                            if ($user->getRoleNames()[0] != 'superadmin' && $user->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($user->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                            }
                        }
                    }
                }

                $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                foreach ($admins as $admin) {
                    if ($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                        dispatch((new MailSend($admin->email, 'send_mail_to_agent_when_ticket_closed_by_admin_or_agent', $ticketData)));
                    }
                }

                if ($ccemailsend->ccemails != null) {
                    dispatch((new MailSend($ccemailsend->ccemails, 'CCmail_sendemail_whenticketclosed', $ticketData)));
                }
            } catch (\Exception $e) {
                return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
            }

            return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
        } else {



            $this->validate($request, [
                'comment' => 'required'
            ]);
            $tic = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

            if ($tic->status == 'Closed') {
                return redirect()->back()->with("error", lang('This ticket is already closed, you are not allowed to reply for this tikcet.', 'alerts'));
            }

            if ($tic->comments()->get() != null) {
                $comm = $tic->comments()->update([
                    'display' => null
                ]);
            }

            $comment = Comment::create([
                'ticket_id' => $request->input('ticket_id'),
                'user_id' => Auth::user()->id,
                'cust_id' => null,
                'comment' => $request->input('comment'),
                'display' => 1,
            ]);

            $ticketdraft = TicketDraft::where('ticket_id', $request->ticket_id)->first();

            if ($ticketdraft) {
                foreach ($ticketdraft->getMedia('ticketdrafts') as $ticketdr) {

                    if (!file_exists('public/temp/')) {
                        mkdir('public/temp/', 0777, true);
                    }
                    $localTempFilePath = 'public/temp/' . $ticketdr->file_name;
                    $existprovider = existprovider($ticketdr->disk);
                    if ($existprovider)
                        $content = $existprovider->provider::getdraft($ticketdr);

                    file_put_contents($localTempFilePath, $content);
                    $provider =  storage()->provider;

                    $media = $provider::draftupload($comment, $localTempFilePath);
                    $ticketdr->delete();
                }

                $ticketdraft->delete();
            }

            foreach ($request->input('comments', []) as $file) {
                $provider =  storage()->provider;
                $provider::mediaupload($comment, 'uploads/comment/' . $file, 'comments');
            }

            $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
            $ticket->status = $request->input('status');
            $ticket->replystatus = 'Waiting';
            if ($request->status == 'On-Hold') {
                $ticket->note = $request->input('note');
                // Auto Close Ticket
                $ticket->auto_close_ticket = null;
                // Auto Response Ticket
                $ticket->auto_replystatus = null;
                //Auto Overdue Ticket
                $ticket->auto_overdue_ticket = null;
                $ticket->overduestatus = null;
            } else {
                // Auto Closing Ticket
                if (setting('AUTO_CLOSE_TICKET') == 'no') {
                    $ticket->auto_close_ticket = null;
                } else {
                    if (setting('AUTO_CLOSE_TICKET_TIME') == '0') {
                        $ticket->auto_close_ticket = null;
                    } else {
                        if (Auth::check() && Auth::user()) {
                            if ($ticket->status == 'Closed') {
                                $ticket->auto_close_ticket = null;
                            } else {
                                $ticket->auto_close_ticket = now()->addHours(setting('AUTO_RESPONSETIME_TICKET_TIME'))->addDays(setting('AUTO_CLOSE_TICKET_TIME'));
                            }
                        }
                    }
                }
                // End Auto Close Ticket

                // Auto Response Ticket

                if (setting('AUTO_RESPONSETIME_TICKET') == 'no') {
                    $ticket->auto_replystatus = null;
                } else {
                    if (setting('AUTO_RESPONSETIME_TICKET_TIME') == '0') {
                        $ticket->auto_replystatus = null;
                    } else {
                        if (Auth::check() && Auth::user()) {
                            $ticket->auto_replystatus = now()->addHours(setting('AUTO_RESPONSETIME_TICKET_TIME'));
                        }
                    }
                }
                // End Auto Response Ticket

                // Auto Overdue Ticket
                if (setting('AUTO_OVERDUE_TICKET') == 'no') {
                    $ticket->auto_overdue_ticket = null;
                    $ticket->overduestatus = null;
                } else {
                    if (setting('AUTO_OVERDUE_TICKET_TIME') == '0') {
                        $ticket->auto_overdue_ticket = null;
                        $ticket->overduestatus = null;
                    } else {
                        if (Auth::check() && Auth::user()) {
                            if ($ticket->status == 'Closed') {
                                $ticket->auto_overdue_ticket = null;
                                $ticket->overduestatus = null;
                            } else {
                                $ticket->auto_overdue_ticket = null;
                                $ticket->overduestatus = null;
                            }
                        }
                    }
                }
                // End Auto Overdue Ticket
            }
            $ticket->last_reply = now();
            $ticket->lastreply_mail = Auth::id();
            $ticket->importantticket = null;
            $ticket->update();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;

            $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $ticket->overduestatus;
            $tickethistory->status = $ticket->status;
            $tickethistory->currentAction = 'Responded';
            $tickethistory->username = $comment->user->name;
            $tickethistory->type = $comment->user->getRoleNames()[0];

            $tickethistory->save();

            $cust = Customer::find($ticket->cust_id);
            $cust->notify(new TicketCreateNotifications($ticket));

            $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

            if ($ticket->cust->userType == 'Guest') {
                $ticketData = [
                    'ticket_username' => $ticket->cust->username,
                    'ticket_title' => $ticket->subject,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_status' => $ticket->status,
                    'comment' => $comment->comment,
                    'ticket_customer_url' => route('guest.ticketdetailshow', encrypt($ticket->ticket_id)),
                    'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
                ];
            }
            if ($ticket->cust->userType == 'Customer') {
                $ticketData = [
                    'ticket_username' => $ticket->cust->username,
                    'ticket_title' => $ticket->subject,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_status' => $ticket->status,
                    'comment' => $comment->comment,
                    'ticket_customer_url' => route('loadmore.load_data', encrypt($ticket->ticket_id)),
                    'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
                ];
            }



            try {

                if($ticket->cust->phonesmsenable == 1 && $ticket->cust->phoneVerified == 1 && setting('twilioenable') == 'on'){
                    dispatch((new SendSMS($ticket->cust->phone, 'reply_to_customer', $ticketData)));
                }

                if ($ticket->tickettype == 'emalitoticket') {

                    $imaps = Imap_setting::find($ticket->imap_id);
                    $imap_username = $imaps->imap_username;

                    $replySubject = 'Re: ' . $ticket->subject;
                    $emailtemplate = EmailTemplate::where('code', 'customer_send_ticket_reply')->first();
                    $body = $emailtemplate->body;

                    foreach ($ticketData as $key => $value) {
                        $body = str_replace('{{' . $key . '}}', $value, $body);
                        $body = str_replace('{{ ' . $key . ' }}', $value, $body);
                    }
                    $fileNames = [];

                    try {

                        Mail::send([], [], function ($message) use ($ticket, $replySubject, $body, $comment, $imap_username, &$fileNames) {
                            $message->to($ticket->cust->email)
                                ->from($imap_username)
                                ->subject($replySubject)
                                ->html($body);
                            foreach ($comment->getMedia('comments') as $commentss) {
                                if (!file_exists('public/temp/')) {
                                    mkdir('public/temp/', 0777, true);
                                }
                                $localTempFilePath = 'public/temp/' . $commentss->file_name;
                                $existprovider = existprovider($commentss->disk);
                                if ($existprovider)
                                    $contentPath = $existprovider->provider::tempImage($commentss, $localTempFilePath);

                                $message->attach($contentPath);
                                $fileNames[] = $localTempFilePath;
                            }
                            $headers = $message->getHeaders();
                            $headers->addTextHeader('In-Reply-To', '<' . $ticket->MessageID . '>');
                            $headers->addTextHeader('References', '<' . $ticket->MessageID . '>');
                        });
                    } catch (\Exception $e) {

                    }

                    foreach ($fileNames as $filePath) {
                        if (file_exists($filePath)) {
                            \File::delete($filePath);
                        }
                    }
                } else {
                    dispatch((new MailSend($ticket->cust->email, 'customer_send_ticket_reply', $ticketData)));
                }

                if ($ccemailsend && $ccemailsend->ccemails) {
                    dispatch((new MailSend($ccemailsend->ccemails, 'customer_send_ticket_reply', $ticketData)));
                }

            } catch (\Exception $e) {
                Session::put('adminreplied', 'The response to the ticket was successful.');
                return back();
            }

            Session::put('adminreplied', 'The response to the ticket was successful.');
            return back();
        }
    }

    public function storeMedia(Request $request)
    {
        $path = public_path('uploads/comment');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name =  time() . '' . $file->getClientOriginalName();

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function latestcommentimgdelete($id)
    {
        $im = Media::find($id);
        if ($im) {
            $im->delete();

            return response()->json(['success' => lang('The image is deleted successfully.', 'alerts')]);
        } else {
            abort(404);
        }
    }

    public function updateedit(Request $request, $id)
    {
        if ($request->has('message')) {

            $this->validate($request, [
                'message' => 'required'
            ]);
            $ticket = Ticket::findOrFail($id);
            $ticket->message = $request->input('message');

            $ticket->update();
            return redirect()->back();
        } else {
            $this->validate($request, [
                'editcomment' => 'required'
            ]);

            $comment = Comment::findOrFail($id);
            $oldComment =  $comment->comment;

            $comment->comment = $request->input('editcomment');

            $comment->update();



            $ticket = Ticket::findOrFail($comment->ticket->id);

            if ($request->status == 'Solved') {
                $ticket->status = 'Closed';
                $ticket->replystatus = $request->input('status');
                // Auto Close Ticket
                $ticket->auto_close_ticket = null;
                // Auto Response Ticket
                $ticket->auto_replystatus = null;
                $ticket->last_reply = now();
                $ticket->closing_ticket = now();
                $ticket->auto_overdue_ticket = null;
                $ticket->overduestatus = null;
                $ticket->closedby_user = Auth::id();
                $ticket->lastreply_mail = Auth::id();

                $ticket->update();
            } else {
                $ticket->status = $request->input('status');
                $ticket->replystatus = 'Waiting';
                if ($request->status == 'On-Hold') {
                    $ticket->note = $request->input('note');
                    // Auto Close Ticket
                    $ticket->auto_close_ticket = null;
                    // Auto Response Ticket
                    $ticket->auto_replystatus = null;
                    //Auto Overdue Ticket
                    $ticket->auto_overdue_ticket = null;
                    $ticket->overduestatus = null;
                } else {
                    // Auto Closing Ticket
                    if (setting('AUTO_CLOSE_TICKET') == 'no') {
                        $ticket->auto_close_ticket = null;
                    } else {
                        if (setting('AUTO_CLOSE_TICKET_TIME') == '0') {
                            $ticket->auto_close_ticket = null;
                        } else {
                            if (Auth::check() && Auth::user()) {
                                if ($ticket->status == 'Closed') {
                                    $ticket->auto_close_ticket = null;
                                } else {
                                    $ticket->auto_close_ticket = now()->addHours(setting('AUTO_RESPONSETIME_TICKET_TIME'))->addDays(setting('AUTO_CLOSE_TICKET_TIME'));
                                }
                            }
                        }
                    }
                    // End Auto Close Ticket

                    // Auto Response Ticket

                    if (setting('AUTO_RESPONSETIME_TICKET') == 'no') {
                        $ticket->auto_replystatus = null;
                    } else {
                        if (setting('AUTO_RESPONSETIME_TICKET_TIME') == '0') {
                            $ticket->auto_replystatus = null;
                        } else {
                            if (Auth::check() && Auth::user()) {
                                $ticket->auto_replystatus = now()->addHours(setting('AUTO_RESPONSETIME_TICKET_TIME'));
                            }
                        }
                    }
                    // End Auto Response Ticket

                    // Auto Overdue Ticket
                    if (setting('AUTO_OVERDUE_TICKET') == 'no') {
                        $ticket->auto_overdue_ticket = null;
                        $ticket->overduestatus = null;
                    } else {
                        if (setting('AUTO_OVERDUE_TICKET_TIME') == '0') {
                            $ticket->auto_overdue_ticket = null;
                            $ticket->overduestatus = null;
                        } else {
                            if (Auth::check() && Auth::user()) {
                                if ($ticket->status == 'Closed') {
                                    $ticket->auto_overdue_ticket = null;
                                    $ticket->overduestatus = null;
                                } else {
                                    $ticket->auto_overdue_ticket = null;
                                    $ticket->overduestatus = null;
                                }
                            }
                        }
                    }
                    // End Auto Overdue Ticket
                }
                $ticket->last_reply = now();
                $ticket->lastreply_mail = Auth::id();
                $ticket->update();
            }

            foreach ($request->input('comments', []) as $file) {
                $provider =  storage()->provider;
                $provider::mediaupload($comment, 'uploads/comment/' . $file, 'comments');
            }

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $comment->ticket->id;

            $tickethistory->ticketnote = $comment->ticket->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $comment->ticket->overduestatus;
            $tickethistory->status = $ticket->status;
            $tickethistory->currentAction = 'Comment Modified';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];

            $tickethistory->save();

            return redirect()->back();
        }
    }

    public function deletecomment(Request $request, $id)
    {

        $comment = Comment::findOrFail($id);

        $comment->delete();

        $tic = Ticket::find($comment->ticket_id);
        $latestcomment = $tic->comments()->latest('created_at')->first();

        if ($latestcomment != null) {
            $comm = $latestcomment->update([
                'display' => 1
            ]);
        }

        // $comment->forceDelete();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $comment->ticket->id;

        $tickethistory->ticketnote = $comment->ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $comment->ticket->overduestatus;
        $tickethistory->status = $comment->ticket->status;
        $tickethistory->currentAction = 'Comment Deleted';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        return response()->json(['success' => lang('The ticket comment has been deleted successfully.', 'alerts'),]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ticket_id)
    {
    }

    public function imagedestroy($id)
    {   //For Deleting Users
        $commentss = Media::findOrFail($id);
        $commentss->delete();
        return response()->json([
            'success' => lang('Deleted Successfully', 'alerts')
        ]);
    }

    public function reopenticket(Request $req)
    {
        $id = decrypt($req->reopenid);
        $reopenticket = Ticket::find($id);
        $reopenticket->status = 'Re-Open';
        $reopenticket->replystatus = null;
        $reopenticket->closedby_user = null;
        $reopenticket->lastreply_mail = Auth::id();
        $reopenticket->update();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $reopenticket->id;

        $tickethistory->ticketnote = $reopenticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $reopenticket->overduestatus;
        $tickethistory->status = $reopenticket->status;
        $tickethistory->currentAction = 'Re-opened';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        $cust = Customer::with('custsetting')->find($reopenticket->cust_id);
        $cust->notify(new TicketCreateNotifications($reopenticket));

        if ($reopenticket->cust->userType == 'Guest') {
            $ticketData = [
                'ticket_username' => $reopenticket->cust->username,
                'ticket_title' => $reopenticket->subject,
                'ticket_id' => $reopenticket->ticket_id,
                'ticket_status' => $reopenticket->status,
                'ticket_customer_url' => route('gusetticket', encrypt($reopenticket->ticket_id)),
                'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($reopenticket->ticket_id)),
            ];
        }
        if ($reopenticket->cust->userType == 'Customer') {
            $ticketData = [
                'ticket_username' => $reopenticket->cust->username,
                'ticket_title' => $reopenticket->subject,
                'ticket_id' => $reopenticket->ticket_id,
                'ticket_status' => $reopenticket->status,
                'ticket_customer_url' => route('loadmore.load_data', encrypt($reopenticket->ticket_id)),
                'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($reopenticket->ticket_id)),
            ];
        }

        return response()->json([
            'success' => lang('The ticket has been successfully reopened.', 'alerts'),
        ]);
    }
}
