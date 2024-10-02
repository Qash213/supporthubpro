<?php

namespace App\Http\Controllers\User\Ticket;

use App\Http\Controllers\Controller;
use App\Jobs\MailSend;
use App\Jobs\SendSMS;
use App\Mail\mailmailablesend;
use App\Models\Apptitle;
use App\Models\Articles\Article;
use App\Models\CCMAILS;
use App\Models\Customfield;
use App\Models\Footertext;
use App\Models\usersettings;
use App\Models\Pages;
use App\Models\Projects;
use App\Models\Seosetting;
use App\Models\TicketCustomfield;
use App\Models\Ticket\Category;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use App\Models\User;
use App\Notifications\TicketCreateNotifications;
use Auth;
use Illuminate\Http\Request;
use Mail;
use URL;
use App\Models\tickethistory;
use App\Models\Ratingtoken;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Setting;

use App\Models\Announcement;
use App\Models\CategoryEnvato;
use App\Models\Employeerating;
use App\Models\Groups;
use App\Models\Holiday;
use App\Models\MessageTemplates;
use App\Models\Subcategorychild;
use App\Models\Userrating;
use DOMDocument;

class TicketController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {


        if (setting('CUSTOMER_TICKET') == 'no') {

            $categories = Category::whereIn('display', ['ticket', 'both'])->where('status', '1')
                ->get();

            $title = Apptitle::first();
            $data['title'] = $title;

            $footertext = Footertext::first();
            $data['footertext'] = $footertext;

            $seopage = Seosetting::first();
            $data['seopage'] = $seopage;

            $now = now();
            $announcement = announcement::whereDate('enddate', '>=', $now->toDateString())->whereDate('startdate', '<=', $now->toDateString())->get();
            $data['announcement'] = $announcement;

            $announcements = Announcement::whereNotNull('announcementday')->get();
            $data['announcements'] = $announcements;

            $holidays = Holiday::whereDate('startdate', '<=', $now->toDateString())->whereDate('enddate', '>=', $now->toDateString())->where('status',1)->get();
            $data['holidays'] =  $holidays;

            $post = Pages::all();
            $data['page'] = $post;

            $populararticle = Article::orderBy('views', 'desc')->latest()->paginate(5);
            $data['populararticles'] = $populararticle;

            $customfields = Customfield::whereIn('displaytypes', ['both', 'createticket'])->where('status', '1')->get();
            $data['customfields'] = $customfields;

            $projects = Projects::select('projects.*', 'projects_categories.category_id')->join('projects_categories', 'projects_categories.projects_id', 'projects.id')->get();

            // customer restrict to create tickets based on allowed to create.
            if (setting('RESTRICT_TO_CREATE_TICKET') == 'on' && setting('MAXIMUM_ALLOW_TICKETS') > 0) {
                $customer = Auth::guard('customer')->user();
                $star1 = now()->subHour(setting('MAXIMUM_ALLOW_HOURS'));
                $star2 = now();
                $latestReplies = $customer->tickets()->whereBetween('created_at', [$star1, $star2])->get()->take(setting('MAXIMUM_ALLOW_TICKETS'));
                if ($latestReplies->isNotEmpty()) {
                    $totalcount = 0;
                    foreach ($latestReplies as $comment) {
                        if ($comment->user_id !== null) {
                            $totalcount++;
                        }
                    }
                    if (!$totalcount) {
                        $currentTcikets = $latestReplies->last();
                        $data['difference'] = $currentTcikets->created_at->addHour(setting('REPLY_ALLOW_IN_HOURS'))->diffForHumans(now());
                        $ticketscount = Ticket::where('cust_id', $customer->id)->whereBetween('created_at', [$star1, $star2])->count();
                        if ($ticketscount < setting('MAXIMUM_ALLOW_TICKETS')) {
                            return view('user.ticket.create', compact('categories', 'title', 'footertext'))->with($data);
                        } else {
                            return redirect()->back()->with('error', 'You have reached maximum allow tickets to create.');
                        }
                    } else {
                        return view('user.ticket.create', compact('categories', 'title', 'footertext'))->with($data);
                    }
                } else {
                    return view('user.ticket.create', compact('categories', 'title', 'footertext'))->with($data);
                }
            } else {
                return view('user.ticket.create', compact('categories', 'title', 'footertext'))->with($data);
            }
        } else {
            return redirect()->back()->with('error', 'You cannot have access for this ticket create.');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $categories = CategoryEnvato::where('category_id', $request->category)->first();

        if (setting('ENVATO_ON') == 'on' && $categories != null) {
            if ($request->envato_id == 'undefined' || $request->envato_id == null || isset($request->envato_id) == false) {
                return response()->json(['message' => 'envatoerror', 'error' => lang('Please enter valid details to create a ticket.', 'alerts')], 200);
            }
        }

        $subcategoriess = Subcategorychild::where('category_id', $request->category)->pluck('subcategory_id')->toArray();
        if($subcategoriess != null && $request->subscategory != null && !in_array($request->subscategory, $subcategoriess)){
            return response()->json(['message' => 'subcaterror', 'error' => lang('Please enter valid details to create a ticket.', 'alerts')], 200);
        }

        $this->validate($request, [
            'subject' => 'required|max:255',
            'category' => 'required',
            'message' => 'required|no_script_tags',
            'agree_terms' =>  'required|in:agreed',

        ]);

        $ticket = Ticket::create([
            'subject' => $request->input('subject'),
            'cust_id' => Auth::guard('customer')->user()->id,
            'category_id' => $request->input('category'),
            'message' => $request->input('message'),
            'project' => $request->input('project'),
            'status' => 'New',
        ]);
        $ticket = Ticket::find($ticket->id);

        $ticket->ticket_id = setting('CUSTOMER_TICKETID') . '-' . $ticket->id;
        // Auto Overdue Ticket

        if (setting('AUTO_OVERDUE_TICKET') == 'no') {
            $ticket->auto_overdue_ticket = null;
        } else {
            if (setting('AUTO_OVERDUE_TICKET_TIME') == '0') {
                $ticket->auto_overdue_ticket = null;
            } else {
                if (Auth::guard('customer')->check() && Auth::guard('customer')->user()) {
                    if ($ticket->status == 'Closed') {
                        $ticket->auto_overdue_ticket = null;
                    } else {
                        $ticket->auto_overdue_ticket = now()->addDays(setting('AUTO_OVERDUE_TICKET_TIME'));
                    }
                }
            }
        }
        // Auto Overdue Ticket
        $categories = CategoryEnvato::where('category_id', $request->category)->first();
        if ($request->input('envato_id') &&  $categories) {
            $ticket->purchasecode = encrypt($request->input('envato_id'));
            if ($request->input('productname')) {
                $ticket->item_name = $request->input('productname');
            }
        }
        if ($request->input('envato_support')) {

            $ticket->purchasecodesupport = $request->input('envato_support');
        }
        $categoryfind = Category::find($request->category);
        $ticket->priority = $categoryfind->priority;
        if ($request->subscategory) {
            $ticket->subcategory = $request->subscategory;
        }
        $ticket->update();

        $customfields = Customfield::whereIn('displaytypes', ['both', 'createticket'])->where('status',1)->get();

        foreach ($customfields as $customfield) {
            $ticketcustomfield = new TicketCustomfield();
            $ticketcustomfield->ticket_id = $ticket->id;
            $ticketcustomfield->fieldnames = $customfield->fieldnames;
            $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
            $ticketcustomfield->fieldoptions = $customfield->fieldoptions;
            if ($customfield->fieldtypes == 'checkbox') {
                if ($request->input('custom_' . $customfield->id) != null) {

                    $string = implode(',', $request->input('custom_' . $customfield->id));
                    $ticketcustomfield->values = $string;
                }
            }
            if ($customfield->fieldtypes != 'checkbox') {
                if ($customfield->fieldprivacy == '1') {
                    $ticketcustomfield->privacymode = $customfield->fieldprivacy;
                    $ticketcustomfield->values = encrypt($request->input('custom_' . $customfield->id));
                } else {

                    $ticketcustomfield->values = $request->input('custom_' . $customfield->id);
                }
            }
            $ticketcustomfield->save();
        }

        $ccmails = new CCMAILS();
        $ccmails->ticket_id = $ticket->id;
        $ccmails->ccemails = $request->ccmail;
        $ccmails->save();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->currentAction = 'Created';
        $tickethistory->username = $ticket->cust->username;
        $tickethistory->type = $ticket->cust->userType;

        $tickethistory->save();

        foreach ($request->input('ticket', []) as $file) {

            $provider =  storage()->provider;
            $provider::mediaupload($ticket, 'uploads/ticket/' . $file, 'ticket');
        }

        // Create a New ticket reply
        // $notificationcat = $ticket->category->groupscategoryc()->get();
        // $groupIds = $notificationcat->pluck('group_id')->toArray();
        // $groupstatus = false;
        // foreach($groupIds as $groupid){
        //    $groupexist = Groups::where('groupstatus', '1')->find($groupid);
        //    if($groupexist){
        //     $groupstatus = true;
        //    }

        // }

        // $icc = array();

        // if ($groupstatus) {

        //     foreach ($notificationcat as $igc) {
        //         $groups = $igc->groupsc()
        //                       ->where('groupstatus', 1)
        //                       ->with('groupsuser')
        //                       ->get();

        //         foreach ($groups as $group) {
        //             $users = $group->groupsuser;

        //             foreach ($users as $user) {
        //                 $icc[] = $user->users_id;
        //             }
        //         }
        //     }

        //     if (!$icc) {
        //         $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
        //         foreach ($admins as $admin) {
        //             $admin->notify(new TicketCreateNotifications($ticket));
        //         }
        //     } else {

        //         $user = User::whereIn('id', $icc)->get();
        //         foreach ($user as $users) {
        //             $users->notify(new TicketCreateNotifications($ticket));
        //         }
        //         $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
        //         foreach ($admins as $admin) {
        //             if ($admin->getRoleNames()[0] == 'superadmin') {
        //                 $admin->notify(new TicketCreateNotifications($ticket));
        //             }
        //         }
        //     }
        // } else {
        //     foreach (usersdata() as $admin) {
        //         $admin->notify(new TicketCreateNotifications($ticket));
        //     }
        // }

        $request->session()->put('customerticket', Auth::guard('customer')->id());
        $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();
        $ticketData = [
            'ticket_username' => $ticket->cust->username,
            'ticket_id' => $ticket->ticket_id,
            'ticket_title' => $ticket->subject,
            'ticket_description' => $ticket->message,
            'ticket_status' => $ticket->status,
            'ticket_customer_url' => route('loadmore.load_data', encrypt($ticket->ticket_id)),
            'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
        ];

        // $notifyUsers = collect();

        try {

            if($ticket->cust->phonesmsenable == 1 && $ticket->cust->phoneVerified == 1 && setting('twilioenable') == 'on'){
                dispatch((new SendSMS($ticket->cust->phone, 'created_ticket', $ticketData)));
            }

            $today = Carbon::today();
            $holidays = Holiday::whereDate('startdate', '<=', $today)->whereDate('enddate', '>=', $today)->where('status', '1')->get();

            if ($holidays->isNotEmpty() && setting('24hoursbusinessswitch') != 'on') {
                dispatch((new MailSend($ticket->cust->email, 'customer_send_ticket_created_that_holiday_or_announcement', $ticketData)));
                if($ccemailsend->ccemails != null){
                    dispatch((new MailSend($ccemailsend->ccemails, 'customer_send_ticket_created_that_holiday_or_announcement', $ticketData)));
                }
            } else {
                dispatch((new MailSend($ticket->cust->email, 'customer_send_ticket_created', $ticketData)));
                if($ccemailsend->ccemails != null){
                    dispatch((new MailSend($ccemailsend->ccemails, 'customer_send_ticket_created', $ticketData)));
                }
            }


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
                        $admin->notify(new TicketCreateNotifications($ticket));
                        if ($admin->usetting->emailnotifyon == 1) {
                            dispatch((new MailSend($admin->email, 'admin_send_email_ticket_created', $ticketData)));
                        }
                    }
                } else {

                    $user = User::whereIn('id', $icc)->where('status', 1)->get();
                    foreach ($user as $users) {
                        $users->notify(new TicketCreateNotifications($ticket));
                        if ($users->usetting->emailnotifyon == 1) {
                            dispatch((new MailSend($users->email, 'admin_send_email_ticket_created', $ticketData)));
                        }
                    }
                    $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new TicketCreateNotifications($ticket));
                        if ($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1) {
                            dispatch((new MailSend($admin->email, 'admin_send_email_ticket_created', $ticketData)));
                        }
                    }
                }
            } else {
                foreach (usersdata() as $admin) {
                    $admin->notify(new TicketCreateNotifications($ticket));
                    if ($admin->usetting->emailnotifyon == 1) {
                        dispatch((new MailSend($admin->email, 'admin_send_email_ticket_created', $ticketData)));
                    }
                }
            }
        } catch (\Exception $e) {

            return response()->json(['description' => $ticket->message, 'subject' => $ticket->subject, 'id' => $ticket->id, 'customer_url' =>route('loadmore.load_data', encrypt($ticket->ticket_id)), 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
        }


        return response()->json(['description' => $ticket->message, 'subject' => $ticket->subject, 'id' => $ticket->id, 'customer_url' => route('loadmore.load_data', encrypt($ticket->ticket_id)), 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
    }

    public function storeMedia(Request $request)
    {
        $path = public_path('uploads/ticket');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function activeticket()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $activetickets = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->whereIn('status', ['New', 'Re-Open', 'Inprogress'])->latest('updated_at')->get();
        $data['activetickets'] = $activetickets;

        return view('user.ticket.viewticket.activeticket', compact('title', 'footertext'))->with($data);
    }

    public function closedticket()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $closedtickets = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->where('status', 'Closed')->latest('updated_at')->get();
        $data['closedtickets'] = $closedtickets;

        return view('user.ticket.viewticket.closedticket', compact('title', 'footertext'))->with($data);
    }

    public function onholdticket()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $onholdtickets = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->where('status', 'On-Hold')->latest('updated_at')->get();
        $data['onholdtickets'] = $onholdtickets;

        return view('user.ticket.viewticket.onholdticket', compact('title', 'footertext'))->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req, $ticket_id)
    {
        $ticket_id = decrypt($ticket_id);
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments()->with('ticket')->paginate(5);
        $category = $ticket->category;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $now = now();
        $announcement = announcement::whereDate('enddate', '>=', $now->toDateString())->whereDate('startdate', '<=', $now->toDateString())->get();
        $data['announcement'] = $announcement;

        $announcements = Announcement::whereNotNull('announcementday')->get();
        $data['announcements'] = $announcements;

        $holidays = Holiday::whereDate('startdate', '<=', $now->toDateString())->whereDate('enddate', '>=', $now->toDateString())->where('status',1)->get();
        $data['holidays'] =  $holidays;


        // customer restrict to reply for the ticket.
        $commentsNull = $ticket->comments()->get();

        $latestcomment = $ticket->comments()->latest('created_at')->first();

        if ($commentsNull->all() != null) {
            foreach ($commentsNull as $latestone) {
                if ($latestone->lastseen == null && $latestone->user_id != null) {
                    $latestone->lastseen = now();
                    $latestone->save();
                }
            }
        }


        if (setting('RESTRICT_TO_REPLY_TICKET') == 'on' && $commentsNull->all() != null && setting('MAXIMUM_ALLOW_REPLIES') > 0) {

            $star1 = now()->subHour(setting('REPLY_ALLOW_IN_HOURS'));
            $star2 = now();
            $latestReplies = $ticket->comments()->whereBetween('created_at', [$star1, $star2])->get()->take(setting('MAXIMUM_ALLOW_REPLIES'));

            if ($latestReplies->isNotEmpty()) {
                $totalcount = 0;
                foreach ($latestReplies as $comment) {
                    if ($comment->user_id !== null) {
                        $totalcount++;
                    }
                }

                if (!$totalcount) {
                    $currentTcikets = $latestReplies->last();
                    $data['difference'] = $currentTcikets->created_at->addHour(setting('REPLY_ALLOW_IN_HOURS'))->diffForHumans(now());
                    $createdcount = $ticket->comments()->where('cust_id', Auth::guard('customer')->user()->id)->whereBetween('created_at', [$star1, $star2])->count();

                    if ($ticket->cust_id == Auth::guard('customer')->id()) {
                        if ($req->page) {
                            $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                            return response()->json(['html' => $view]);
                        }

                        return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
                    } else {
                        return back()->with('error', lang('Cannot Access This Ticket'));
                    }
                } else {
                    $createdcount = '';

                    if ($req->page) {
                        $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                        return response()->json(['html' => $view]);
                    }

                    return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
                }
            } else {
                $createdcount = '';

                if ($req->page) {


                    $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                    return response()->json(['html' => $view]);
                }

                return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
            }
        } else {
            if ($ticket->cust_id == Auth::guard('customer')->id()) {
                $createdcount = '';

                if ($req->page) {
                    $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                    return response()->json(['html' => $view]);
                }

                return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
            } else {
                return back()->with('error', lang('Cannot Access This Ticket'));
            }
        }
    }

    /**
     * Close the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request, $ticket_id)
    {
        $ticket_id = decrypt($ticket_id);

        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->status = 'Re-Open';
        $ticket->replystatus = null;
        $ticket->closedby_user = null;

        $ticket->update();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->currentAction = 'Re-opened';
        $tickethistory->username = Auth::guard('customer')->user()->username;
        $tickethistory->type = Auth::guard('customer')->user()->userType;

        $tickethistory->save();

        $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

        $ticketData = [
            'ticket_username' => $ticket->cust->username,
            'ticket_id' => $ticket->ticket_id,
            'ticket_title' => $ticket->subject,
            'ticket_description' => $ticket->message,
            'ticket_status' => $ticket->status,
            'ticket_customer_url' => route('loadmore.load_data', encrypt($ticket->ticket_id)),
            'ticket_admin_url' => url('/admin/ticket-view/' . encrypt($ticket->ticket_id)),
        ];

        try {

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
                            if ($admin->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($admin->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                            }
                        }
                    } else {

                        if ($ticket->myassignuser) {
                            $assignee = $ticket->ticketassignmutliples;
                            foreach ($assignee as $assignees) {
                                $user = User::where('id', $assignees->toassignuser_id)->get();
                                foreach ($user as $users) {
                                    if ($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin') {
                                        $users->notify(new TicketCreateNotifications($ticket));
                                        if ($users->usetting->emailnotifyon == 1) {
                                            dispatch((new MailSend($users->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                                        }
                                    }
                                }
                            }
                        } else if ($ticket->selfassignuser_id) {
                            $self = User::findOrFail($ticket->selfassignuser_id);
                            if ($self->getRoleNames()[0] != 'superadmin') {
                                $self->notify(new TicketCreateNotifications($ticket));
                                if ($self->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($self->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                                }
                            }
                        } else if ($icc) {
                            $user = User::whereIn('id', $icc)->get();
                            foreach ($user as $users) {
                                $users->notify(new TicketCreateNotifications($ticket));
                                if ($users->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($users->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                                }
                            }
                        } else {
                            $users = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach ($users as $user) {
                                if ($user->getRoleNames()[0] != 'superadmin') {
                                    $user->notify(new TicketCreateNotifications($ticket));
                                    if ($user->usetting->emailnotifyon == 1) {
                                        dispatch((new MailSend($user->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                                    }
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
                                if ($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin') {
                                    $users->notify(new TicketCreateNotifications($ticket));
                                    if ($users->usetting->emailnotifyon == 1) {
                                        dispatch((new MailSend($users->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                                    }
                                }
                            }
                        }
                    } else if ($ticket->selfassignuser_id) {
                        $self = User::findOrFail($ticket->selfassignuser_id);
                        if ($self->getRoleNames()[0] != 'superadmin') {
                            $self->notify(new TicketCreateNotifications($ticket));
                            if ($self->usetting->emailnotifyon == 1) {
                                dispatch((new MailSend($self->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                            }
                        }
                    } else {
                        foreach (usersdata() as $user) {
                            if ($user->getRoleNames()[0] != 'superadmin') {
                                $user->notify(new TicketCreateNotifications($ticket));
                                if ($user->usetting->emailnotifyon == 1) {
                                    dispatch((new MailSend($user->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                                }
                            }
                        }
                    }
                }
            } else {
                foreach (usersdata() as $admin) {
                    if ($admin->getRoleNames()[0] == 'superadmin') {
                        $admin->notify(new TicketCreateNotifications($ticket));
                        if ($admin->usetting->emailnotifyon == 1) {
                            dispatch((new MailSend($admin->email, 'admin_sendemail_whenticketreopen', $ticketData)));
                        }
                    }
                }
            }


            dispatch((new MailSend($ticket->cust->email, 'customer_send_ticket_reopen', $ticketData)));
            if($ccemailsend->ccemails != null){
                dispatch((new MailSend($ccemailsend->ccemails, 'customer_send_ticket_reopen', $ticketData)));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with("success", lang('The ticket has been successfully reopened.', 'alerts'));
        }

        return redirect()->back()->with("success", lang('The ticket has been successfully reopened.', 'alerts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function sublist(Request $request)
    {

        $parent_id = $request->cat_id;

        $subcategories = Projects::select('projects.*', 'projects_categories.category_id')->join('projects_categories', 'projects_categories.projects_id', 'projects.id')
            ->where('projects_categories.category_id', $parent_id)
            ->get();

        return response()->json([
            'subcategories' => $subcategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rating($ticket_id)
    {


        $ratingticket = Ratingtoken::where('token', $ticket_id)->first();
        if (!$ratingticket) {

            return redirect('customer/')->with("error", lang('Your rating has already been submitted.'));
        }
        $ticket = Ticket::where('id', $ratingticket->ticket_id)->first();
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $rating = $ticket->comments()->whereNotNull('user_id')->get();
        $comment = Comment::select('user_id')->where('ticket_id', $ticket->id)->distinct()->get();
        // $ticket->comments()->select('user_id')->distinct()->get();
        if ($rating->isEmpty()) {
            return redirect()->back();
        } else {
            return view('user.ticket.rating', compact('ticket', 'comment', 'title', 'footertext'))->with($data);
        }
    }

    /// rating system ///
    public function star5($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star5');
        $user->usetting->update();

        return redirect('customer/')->with('success', lang('Thank you for rating us.', 'alerts'));
    }

    public function star4($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star4');
        $user->usetting->update();

        return redirect('customer/')->with('success', lang('Thank you for rating us.', 'alerts'));
    }

    public function star3($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star3');
        $user->usetting->update();

        return redirect('customer/')->with('success', lang('Thank you for rating us.', 'alerts'));
    }

    public function star2($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star2');
        $user->usetting->update();

        return redirect('customer/')->with('success', lang('Thank you for rating us.', 'alerts'));
    }

    public function star1($id)
    {

        $user = User::with('usetting')->findorFail($id);

        $user->usetting->increment('star1');
        $user->usetting->update();
        return redirect('customer/')->with('success', lang('Thank you for rating us.', 'alerts'));
    }

    public function ticketrating(Request $req)
    {


        $ticketfinding = Ticket::find($req->ticket_id);

        $ratingticket = Userrating::where('ticket_id', $req->ticket_id)->first();
        if ($ratingticket) {
            $ratingticket->ratingstar = $req->ratingticket;
            $ratingticket->ratingcomment = $req->ratingcomment;
            $ratingticket->update();

            $employeeratingloop = Employeerating::where('urating_id', $ratingticket->id)->get();
            foreach ($employeeratingloop as $employeeratings) {
                $employeeratings->delete();
            }

            $commentsfind = $ticketfinding->comments()->where('user_id', '!=', null)->distinct()->get();
            foreach ($commentsfind as $commentfinds) {
                $employeerating = new Employeerating();
                $employeerating->urating_id = $ratingticket->id;
                $employeerating->rating = $ratingticket->ratingstar;
                $employeerating->user_id = $commentfinds->user_id;
                $employeerating->save();
            }
        } else {

            $ticketrating = new Userrating();
            $ticketrating->ticket_id = $req->ticket_id;
            $ticketrating->ratingstar = $req->ratingticket;
            $ticketrating->ratingcomment = $req->ratingcomment;
            $ticketrating->cust_id = $ticketfinding->cust->id;
            $ticketrating->save();

            $ticketsfind = Ticket::where('id', $req->ticket_id)->first();
            $commentsfind = $ticketsfind->comments()->where('user_id', '!=', null)->distinct()->get();
            foreach ($commentsfind as $commentfinds) {
                $employeerating = new Employeerating();
                $employeerating->urating_id = $ticketrating->id;
                $employeerating->rating = $ticketrating->ratingstar;
                $employeerating->user_id = $commentfinds->user_id;
                $employeerating->save();
            }
        }
        $ratingticketdelete = Ratingtoken::where('ticket_id', $req->ticket_id)->first();
        $ratingticketdelete->delete();

        return redirect('/')->with('success', lang('Thank you for rating us.', 'alerts'));
    }
    /// end rating system ///

    // Print Ticket
    public function pdfmake($id)
    {
        $id = decrypt($id);
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $page = Pages::all();
        $data['page'] = $page;

        $showprintticket = Ticket::findOrFail($id);
        $data['showprintticket'] = $showprintticket;

        return view('user.ticket.ticketshowpdf')->with($data);
    }
}
