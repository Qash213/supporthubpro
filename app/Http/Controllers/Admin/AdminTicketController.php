<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\MailSend;
use Illuminate\Http\Request;

use Auth;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Category;
use App\Mail\AppMailer;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use DB;
use Mail;
use App\Mail\mailmailablesend;
use Hash;
use App\Models\Ticketnote;
use App\Models\Projects;
use App\Notifications\TicketCreateNotifications;
use App\Models\CustomerSetting;
use DataTables;
use App\Models\Groupsusers;
use App\Models\Groups;
use Str;
use App\Models\Cannedmessages;
use Carbon\Carbon;
use App\Models\Customfield;
use App\Models\TicketCustomfield;
use App\Models\CCMAILS;
use App\Models\CategoryEnvato;
use App\Models\tickethistory;
use App\Models\Holiday;
use App\Models\Subcategorychild;
use App\Models\TicketDraft;
use Illuminate\Support\Facades\Session;

class AdminTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::paginate(10);
        $categories = Category::all();

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.viewticket.showticket', compact('tickets', 'categories', 'title'))->with($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket_id)
    {
        $this->authorize('Ticket Edit');

        $ticket_id = decrypt($ticket_id);
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments()->latest()->paginate(10);

        $ticketdraft = TicketDraft::where('ticket_id', $ticket->id)->first();
        $data['ticketdraft'] = $ticketdraft;

        $custsimillarticket = Ticket::where('cust_id', $ticket->cust->id)->count();
        $data['custsimillarticket'] = $custsimillarticket;

        $category = $ticket->category;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $cannedmessage = Cannedmessages::tickedetails($ticket_id);
        $data['cannedmessages'] = $cannedmessage;

        $data['allowreply'] = false;

        $groups =  Groups::where('groupstatus', '1')->get();

        $group_id = '';
        foreach ($groups as $group) {
            $group_id .= $group->id . ',';
        }
        $groupexists = Groupsusers::whereIn('groups_id', explode(',', substr($group_id, 0, -1)))->where('users_id', Auth::id())->exists();

        $finalassigne = [];
        $assignee = $ticket->ticketassignmutliples;
        foreach($assignee as $assignees){
            array_push($finalassigne, $assignees->toassignuser_id);
        }

        if (Auth::user()->getRoleNames()[0] == 'superadmin' || in_array(Auth::user()->id, $finalassigne) || $ticket->selfassignuser_id == Auth::user()->id ) {
            $data['allowreply'] = true;
        } else {
            if($ticket->category){
                $aa = $ticket->category->groupscategoryc()->get();
                if($aa->isNotEmpty()){
                    $categoryArr = Category::with('groupscategoryc')->get();
                    foreach ($categoryArr as $individualCategory) {
                        if ($individualCategory->id == $ticket->category->id) {
                            foreach ($individualCategory->groupscategoryc as $individualGroupc) {
                                $groupId = $individualGroupc->group_id;
                                $groupUser = Groups::with('groupsuser')->get();
                                foreach ($groupUser as $individualGroup) {
                                    foreach ($individualGroup->groupsuser as $groups) {
                                        if ($groups->groups_id == $groupId) {
                                            if (($groups->users_id == Auth::user()->id)) {
                                                $data['allowreply'] = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }else{
                    foreach(usersdata() as $admin) {
                        if($admin->id == Auth::user()->id){
                            $data['allowreply'] = true;
                        }
                    }
                }
            }else{
                foreach(usersdata() as $admin) {
                    if($admin->id == Auth::user()->id){
                        $data['allowreply'] = true;
                    }
                }
            }
        }

        if(!$data['allowreply'])
            abort(403);
        if (request()->ajax()) {

            $view = view('admin.viewticket.showticketdata',compact('ticket', 'category','comments'))->render();
            return response()->json(['html'=>$view]);
        }

        return view('admin.viewticket.showticket', compact('ticket','category', 'comments', 'title','footertext'))->with($data);
    }

    public function adminticketclosing(Request $request, $id)
    {
        $this->authorize('Ticket Edit');

        $id = decrypt($id);
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'Closed';
        $ticket->auto_close_ticket = null;
        $ticket->auto_replystatus = null;
        $ticket->auto_overdue_ticket = null;
        $ticket->overduestatus = null;
        $ticket->closedby_user = Auth::id();
        $ticket->ticketreopen = 'stopreopen';
        $ticket->update();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->currentAction = 'Force Closed';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        return response()->json(['success'=>lang('Ticket closed successfully.', 'alerts')]);
    }

    public function addimportantticket(Request $request, $id)
    {
        $this->authorize('Ticket Edit');

        $id = decrypt($id);
        $ticket = Ticket::findOrFail($id);
        $ticket->importantticket = $ticket->importantticket == null ? 'on' : null;
        $ticket->update();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->currentAction = $ticket->importantticket == 'on' ? 'Mark As Starred' :  'Mark As Unstarred';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        return response()->json(['success'=>lang('Important ticket added successfully.', 'alerts')]);
    }

    public function purchasedetailsverify(Request $request)
    {
        $ticket = Ticket::findOrFail($request->id);
        $ticket->usernameverify = 'verified';
        $ticket->update();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->currentAction = 'User verified';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        return response()->json(['success'=>lang('The customer was verified successfully.', 'alerts')]);
    }


    public function wrongcustomer(Request $request)
    {
        $ticket = Ticket::findOrFail($request->id);
        $ticket->usernameverify = 'wrongcustomer';
        $ticket->update();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->currentAction = 'User Unverified';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        return response()->json(['success'=>lang('The customer mentioned details are wrong.', 'alerts')]);
    }


    public function commentshow($ticket_id){

        if(request()->ajax()){
            $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
            if(request()->id > 0){
                $comments = $ticket->comments()->where('id', '<', request()->id)
                ->orderBy('id', 'DESC')
                ->limit(6)
                ->latest()
                ->get();
            }else{
                $comments = $ticket->comments()
                ->orderBy('id', 'DESC')
                ->limit(6)
                ->latest()
                ->get();
            }

            $output = '';
            $last_id = '';
            $i = 0;
            $len = count($comments);
            if(!$comments->isEmpty())
            {
            foreach($comments as $comment){
                if($comment->user_id != null){

                    if($i == 0){
                        $output .= '
                        <div class="card-body">
                            <div class="d-sm-flex">
                                <div class="d-flex me-3">
                                    <a href="#">';
                                        if($comment->user != null){
                                            if ($comment->user->image == null){
                                                $output .= '<img src="'.asset('uploads/profile/user-profile.png').'"  class="media-object brround avatar-lg" alt="default">';
                                            }else{

                                                $output .= '<img class="media-object brround avatar-lg" alt="'.$comment->user->image.'" src="'.asset('uploads/profile/'. $ticket->user->image).'">';
                                            }
                                        }else{
                                            $output .= '<img src="'.asset('uploads/profile/user-profile.png').'"  class="media-object brround avatar-lg" alt="default">';
                                        }
                                        $output .=
                                    '</a>
                                </div>
                                <div class="media-body">';
                                    if($comment->user != null){
                                        $output .= '<h5 class="mt-1 mb-1 font-weight-semibold">'.$comment->user->name.'<span class="badge badge-primary-light badge-md ms-2">'.$comment->user->getRoleNames()[0].'</span></h5>';
                                    }else{
                                        $output .= '<h5 class="mt-1 mb-1 font-weight-semibold text-muted">~</h5>';
                                    }
                                    $output .= '<small class="text-muted"><i class="feather feather-clock"></i> '.\Carbon\Carbon::parse($comment->created_at)->diffForHumans().'</small>
                                    <span class="fs-13 mb-0 mt-1" value="">
                                        '.$comment->comment.'
                                    </span>
                                    <div class="editsupportnote-icon animated" id="supportnote-icon-'.$comment->id.'">
                                        <form action="'.url('admin/ticket/editcomment/'.$comment->id).'" method="POST">
                                            '.csrf_field().'
                                            <textarea class="editsummernote" name="editcomment">'.$comment->comment.'</textarea>
                                            <div class="btn-list mt-1">
                                                <input type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.form.submit();" value="Update">
                                            </div>
                                        </form>
                                    </div>
                                    ';
                                    if(Auth::id() == $comment->user_id){
                                        $output .= '<div class="row galleryopen">';
                                            foreach ($comment->getMedia('comments') as $commentss){
                                                $output .= '<div class="file-image-1  removespruko'.$commentss->id.'" id="imageremove'.$commentss->id.'">
                                                    <div class="product-image  ">
                                                        <a href="'.$commentss->getFullUrl().'" class="imageopen">
                                                            <img src="'.$commentss->getFullUrl().'" class="br-5" alt="'.$commentss->file_name.'">
                                                        </a>
                                                        <ul class="icons">
                                                            <li><a href="javascript:(0);" class="bg-danger " onclick="deleteticket(event.target)" data-id="'.$commentss->id.'"><i class="fe fe-trash" data-id="'.$commentss->id.'"></i>'.csrf_field().'</a></li>
                                                        </ul>
                                                    </div>
                                                    <span class="file-name-1">
                                                        '.Str::limit($commentss->file_name, 10, $end='.......').'
                                                    </span>
                                                </div>
                                                ';
                                            }
                                        $output .= '</div>';
                                    }else{
                                        $output .= '<div class="row galleryopen">';
                                            foreach ($comment->getMedia('comments') as $commentss){
                                                $output .= '<div class="file-image-1  removespruko'.$commentss->id.'" id="imageremove'.$commentss->id.'">
                                                    <div class="product-image">
                                                        <a href="'.$commentss->getFullUrl().'" class="imageopen">
                                                            <img src="'.$commentss->getFullUrl().'" class="br-5" alt="'.$commentss->file_name.'">
                                                        </a>
                                                    </div>
                                                    <span class="file-name-1">
                                                        '.Str::limit($commentss->file_name, 10, $end='.......').'
                                                    </span>
                                                </div>
                                                ';
                                            }
                                        $output .= '</div>';
                                    }
                                $output .= '</div>';

                                    if (Auth::id() == $comment->user_id){
                                        if($comment->display != null)
                                        $output .= '<div class="ms-auto">
                                        <span class="action-btns supportnote-icon" onclick="showEditForm('.$comment->id.')"><i class="feather feather-edit text-primary fs-16"></i></span>
                                    </div>';
                                    }


                            $output .= '</div>
                        </div>';
                    }else{

                        $output .= '<div class="card-body">
                            <div class="d-sm-flex">
                                <div class="d-flex me-3">
                                    <a href="#">';
                                        if($comment->user != null){
                                            if ($comment->user->image == null){
                                                $output .= '<img src="'.asset('uploads/profile/user-profile.png').'"  class="media-object brround avatar-lg" alt="default">';
                                            }else{
                                                $output .= '<img class="media-object brround avatar-lg" alt="'.$comment->user->image.'" src="'.asset('uploads/profile/'. $ticket->user->image).'">';
                                            }
                                        }else{
                                            $output .= '<img src="'.asset('uploads/profile/user-profile.png').'"  class="media-object brround avatar-lg" alt="default">';
                                        }
                                    $output .= '</a>
                                </div>
                                <div class="media-body">';
                                    if($comment->user != null){
                                        $output .= '<h5 class="mt-1 mb-1 font-weight-semibold">'.$comment->user->name.'<span class="badge badge-primary-light badge-md ms-2">'.$comment->user->getRoleNames()[0].'</span></h5>';
                                    }else{
                                        $output .= '<h5 class="mt-1 mb-1 font-weight-semibold text-muted">~</h5>';
                                    }
                                    $output .= '<small class="text-muted"><i class="feather feather-clock"></i>'.\Carbon\Carbon::parse($comment->created_at)->diffForHumans().'</small>
                                    <span class="fs-13 mb-0 mt-1" value="">
                                        '.$comment->comment.'
                                    </span>
                                    <div class="row galleryopen">';
                                        foreach ($comment->getMedia('comments') as $commentss){
                                            $output .= '<div class="file-image-1  removespruko'.$commentss->id.'" id="imageremove{{$commentss->id}}">
                                                <div class="product-image  ">
                                                    <a href="'.$commentss->getFullUrl().'" class="imageopen">
                                                        <img src="'.$commentss->getFullUrl().'" class="br-5" alt="'.$commentss->file_name.'">
                                                    </a>
                                                </div>
                                                <span class="file-name-1">
                                                    '.Str::limit($commentss->file_name, 10, $end='.......').'
                                                </span>
                                            </div>';
                                        }
                                    $output .= '</div>
                                </div>
                            </div>
                        </div>';

                    }
                }else{
                    $output .= '<div class="card-body">
                        <div class="d-sm-flex">
                            <div class="d-flex me-3">
                                <a href="#">';
                                    if ($comment->cust->image == null){
                                        $output .= ' <img src="'.asset('uploads/profile/user-profile.png').'"  class="media-object brround avatar-lg" alt="default">';
                                    }else{
                                        $output .= '<img class="media-object brround avatar-lg" alt="'.$comment->cust->image.'" src="'.asset('uploads/profile/'. $ticket->cust->image).'">';
                                    }
                                $output .= ' </a>
                            </div>
                            <div class="media-body">
                                <h5 class="mt-1 mb-1 font-weight-semibold">'.$comment->cust->username.'<span class="badge badge-primary-light badge-md ms-2">'.$comment->cust->userType.'</span></h5>
                                <small class="text-muted"><i class="feather feather-clock"></i>'.\Carbon\Carbon::parse($comment->created_at)->diffForHumans().'</small>
                                <span class="fs-13 mb-0 mt-1" value="">
                                    '.$comment->comment.'
                                </span>
                                <div class="row galleryopen">';
                                    foreach ($comment->getMedia('comments') as $commentss){
                                        $output .= '<div class="file-image-1  removespruko'.$commentss->id.'" id="imageremove'.$commentss->id.'">
                                            <div class="product-image">
                                                <a href="'.$commentss->getFullUrl().'" class="imageopen">
                                                    <img src="'.$commentss->getFullUrl().'" class="br-5" alt="'.$commentss->file_name.'">
                                                </a>
                                            </div>
                                            <span class="file-name-1">
                                                '.Str::limit($commentss->file_name, 10, $end='.......').'
                                            </span>
                                        </div>';
                                    }
                                $output .= '</div>
                            </div>
                        </div>
                    </div>';
                }
                $last_id = $comment->id;
                $i++;
            }

            $output .= '
       <div id="load_more">
        <button type="button" name="load_more_button" class="btn btn-success" data-id="'.$last_id.'" id="load_more_button">Load More</button>
       </div>
       ';
            }
            else
                {
                $output .= '
                <div id="load_more">
                    <button type="button" name="load_more_button" class="btn btn-info ">No Data Found</button>
                </div>
                ';
                }

            return response()->json(['html' => $output, 'coment' => $comments]);
        }
    }


    /**
     * Close the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function close(Request $request,$ticket_id, AppMailer $mailer)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->status = $request->input('status');

        $ticket->update();

        $ticketOwner = $ticket->user;

        $mailer->sendTicketStatusNotification($ticketOwner, $ticket);

        return redirect()->back()->with("warning", lang('The ticket has been closed.', 'alerts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('Ticket Delete');

        $id = decrypt($id);

        $ticket = Ticket::findOrFail($id);

        $comment = $ticket->comments()->get();


        if (count($comment) > 0) {
            $media = $ticket->getMedia('ticket');

            foreach ($media as $media) {

                    $media->delete();

            }
            $medias = $ticket->comments()->get();

            foreach ($medias as $mediass) {
                foreach($mediass->getMedia('comments') as $mediasss){

                    $mediasss->delete();
                }

            }
            $comment->each->delete();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;

            $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $ticket->overduestatus;
            $tickethistory->status = $ticket->status;
            $tickethistory->currentAction = 'Ticket Deleted';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];

            $tickethistory->save();

            $ticket->delete();

            return response()->json(['success'=>lang('The ticket was successfully deleted.', 'alerts')]);
        }else{

            $media = $ticket->getMedia('ticket');

            foreach ($media as $media) {

                    $media->delete();

            }

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;

            $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $ticket->overduestatus;
            $tickethistory->status = $ticket->status;
            $tickethistory->currentAction = 'Ticket Deleted';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];

            $tickethistory->save();

            foreach($ticket->ticket_history as $deletetickethistory)
            {
                $deletetickethistory->delete();
            }

            $ticket->delete();

            return response()->json(['success'=>lang('The ticket was successfully deleted.', 'alerts')]);

        }
    }


    public function ticketmassdestroy(Request $request){
        // $student_id_array = $request->input('id');

        $student_id_arrays = $request->input('id');

        $student_id_array = array_map(function ($encryptedValue) {
            return decrypt($encryptedValue);
        }, $student_id_arrays);

        $tickets = Ticket::whereIn('id', $student_id_array)->get();


        foreach($tickets as $ticket){

            $comment = $ticket->comments()->get();


            if (count($comment) > 0) {
                $media = $ticket->getMedia('ticket');

                foreach ($media as $media) {

                        $media->delete();

                }
                $medias = $ticket->comments()->get();

                foreach ($medias as $mediass) {
                    foreach($mediass->getMedia('comments') as $mediasss){

                        $mediasss->delete();
                    }

                }
                $comment->each->delete();

                $tickethistory = new tickethistory();
                $tickethistory->ticket_id = $ticket->id;

                $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
                $tickethistory->overduestatus = $ticket->overduestatus;
                $tickethistory->status = $ticket->status;
                $tickethistory->currentAction = 'Ticket Deleted';
                $tickethistory->username = Auth::user()->name;
                $tickethistory->type = Auth::user()->getRoleNames()[0];

                $tickethistory->save();

                foreach($ticket->ticket_history as $deletetickethistory)
                {
                    $deletetickethistory->delete();
                }

                $tickets->each->delete();
                return response()->json(['success'=> lang('The ticket was successfully deleted.', 'alerts')]);
            }else{

                $media = $ticket->getMedia('ticket');

                foreach ($media as $media) {

                        $media->delete();

                }

                $tickethistory = new tickethistory();
                $tickethistory->ticket_id = $ticket->id;

                $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
                $tickethistory->overduestatus = $ticket->overduestatus;
                $tickethistory->status = $ticket->status;
                $tickethistory->currentAction = 'Ticket Deleted';
                $tickethistory->username = Auth::user()->name;
                $tickethistory->type = Auth::user()->getRoleNames()[0];

                $tickethistory->save();

                foreach($ticket->ticket_history as $deletetickethistory)
                {
                    $deletetickethistory->delete();
                }
                $tickets->each->delete();
            }
        }
        return response()->json(['success'=> lang('The ticket was successfully deleted.', 'alerts')]);

    }

    // Admin Ticket View
    public function createticket()
    {

        $this->authorize('Ticket Create');
            $title = Apptitle::first();
            $data['title'] = $title;

            $footertext = Footertext::first();
            $data['footertext'] = $footertext;

            $seopage = Seosetting::first();
            $data['seopage'] = $seopage;

            $post = Pages::all();
            $data['page'] = $post;

            $categories = Category::whereIn('display',['ticket', 'both'])->where('status', '1')->get();
            $data['categories'] = $categories;

            $customfields = Customfield::whereIn('displaytypes', ['both', 'createticket'])->where('status','1')->get();
            $data['customfields'] = $customfields;

        return view('admin.viewticket.createticket')->with($data);
    }

    // Admins Creating  Ticket

    public function gueststore(Request $request)
    {

        $this->authorize('Ticket Create');

        $categories = CategoryEnvato::where('category_id',$request->category)->first();


        if(setting('ENVATO_ON') == 'on' && $categories != null){
            if($request->envato_id == 'undefined' || $request->envato_id == null || isset($request->envato_id) == false){
                return response()->json(['message' => 'envatoerror', 'error' => lang('Please enter valid details to create a ticket.', 'alerts')], 200);
            }
        }

        $subcategoriess = Subcategorychild::where('category_id', $request->category)->pluck('subcategory_id')->toArray();
        if($subcategoriess != null && $request->subscategory != null && !in_array($request->subscategory, $subcategoriess)){
            return response()->json(['message' => 'subcaterror', 'error' => lang('Please enter valid details to create a ticket.', 'alerts')], 200);
        }

        $email  = $request->email;
        $completeDomain = substr(strrchr($email, "@"), 1);
        $emaildomainlist = setting('EMAILDOMAIN_LIST');
        $emaildomainlistArray = explode(",", $emaildomainlist);

        if(setting('EMAILDOMAIN_BLOCKTYPE') == 'blockemail'){
            if(setting('EMAILDOMAIN_LIST') == null){
                $ticket = $this->emailpassgueststore($request);
                return response()->json(['message' => 'createticket', 'ticketId' => encrypt($ticket->ticket_id), 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
            }else{
                if(in_array($completeDomain, $emaildomainlistArray)){

                    return response()->json(['message' => 'domainblock', 'error' => lang('Domain is Blocked List', 'alerts')], 200);
                }
                $ticket = $this->emailpassgueststore($request);
                return response()->json(['message' => 'createticket', 'ticketId' => encrypt($ticket->ticket_id), 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
            }
        }
        if(setting('EMAILDOMAIN_BLOCKTYPE') == 'allowemail'){
            if(setting('EMAILDOMAIN_LIST') == null){
                $ticket =  $this->emailpassgueststore($request);
                return response()->json(['message' => 'createticket', 'ticketId' => encrypt($ticket->ticket_id), 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
            }else{
                if(in_array($completeDomain, $emaildomainlistArray))
                {
                    $ticket = $this->emailpassgueststore($request);
                    return response()->json(['message' => 'createticket', 'ticketId' => encrypt($ticket->ticket_id), 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
                }
                return response()->json(['message' => 'domainblock', 'error' => lang('Domain is Blocked List', 'alerts')], 200);

            }
        }

    }

    private function emailpassgueststore($request)
    {

        $this->authorize('Ticket Create');

        $this->validate($request, [
            'subject' => 'required|string|max:255',
            'category' => 'required',
            'message' => 'required',
            'email' => 'required|max:255',
        ]);

        if($request->ccemail)
        {
            $this->validate($request, [
                'ccmail' => 'email|indisposable'
            ]);
        }


        $userexits = Customer::where('email', $request->email)->count();
        if($userexits == 1){
            $guest = Customer::where('email', $request->email)->first();

        }else{
            $guest = Customer::create([

                'firstname' => '',
                'lastname' => '',
                'username' => 'GUEST',
                'email' => $request->email,
                'userType' => 'Guest',
                'password' => null,
                'country' => '',
                'timezone' => 'UTC',
                'status' => '1',
                'image' => null,

            ]);
            $customersetting = new CustomerSetting();
            $customersetting->custs_id = $guest->id;
            $customersetting->save();
        }
        $ticket = Ticket::create([
            'subject' => $request->input('subject'),
            'cust_id' => $guest->id,
            'category_id' => $request->input('category'),
            'priority' => $request->input('priority'),
            'message' => $request->input('message'),
            'project' => $request->input('project'),
            'status' => 'New',
        ]);
        $ticket = Ticket::find($ticket->id);
        $ticket->ticket_id = setting('CUSTOMER_TICKETID').'G-'.$ticket->id;
        $ticket->user_id = Auth::user()->id;

        $categories = CategoryEnvato::where('category_id',$request->category)->first();
        if($request->input('envato_id') &&  $categories){
            $ticket->purchasecode = encrypt($request->input('envato_id'));
            if($request->input('productname')){
                $ticket->item_name = $request->input('productname');
            }
        }
        if($request->input('envato_support')){

            $ticket->purchasecodesupport = $request->input('envato_support');
        }

        $categoryfind = Category::find($request->category);
        $ticket->priority = $categoryfind->priority;
        $ticket->subcategory = $request->subscategory;

        $ticket->update();

        $customfields = Customfield::whereIn('displaytypes', ['both', 'createticket'])->where('status',1)->get();

        foreach($customfields as $customfield){
            $ticketcustomfield = new TicketCustomfield();
            $ticketcustomfield->ticket_id = $ticket->id;
            $ticketcustomfield->fieldnames = $customfield->fieldnames;
            $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
            $ticketcustomfield->fieldoptions = $customfield->fieldoptions;
            if($customfield->fieldtypes == 'checkbox'){
                if($request->input('custom_'.$customfield->id) != null){

                    $string = implode(',', $request->input('custom_'.$customfield->id));
                    $ticketcustomfield->values = $string;
                }

            }
            if($customfield->fieldtypes != 'checkbox'){
                if($customfield->fieldprivacy == '1'){
                    $ticketcustomfield->privacymode  = $customfield->fieldprivacy;
                    $ticketcustomfield->values = encrypt($request->input('custom_'.$customfield->id));
                }else{

                    $ticketcustomfield->values = $request->input('custom_'.$customfield->id);
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
        $tickethistory->username = $ticket->users->name;
        $tickethistory->type = $ticket->users->getRoleNames()[0];

        $tickethistory->save();

        foreach ($request->input('ticket', []) as $file) {
            $provider =  storage()->provider;
            $provider::mediaupload($ticket,'uploads/guestticket/' . $file,'ticket');
        }
        // create ticket notification
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
                }

            }else{

                $user = User::whereIn('id', $icc)->get();
                foreach($user as $users){
                    $users->notify(new TicketCreateNotifications($ticket));
                }
                $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                foreach($admins as $admin){
                    if($admin->getRoleNames()[0] == 'superadmin'){
                        $admin->notify(new TicketCreateNotifications($ticket));
                    }
                }


            }
        }else{
            foreach(usersdata() as $admin){
                $admin->notify(new TicketCreateNotifications($ticket));
            }
        }
        $cust = Customer::with('custsetting')->find($ticket->cust_id);
        $cust->notify(new TicketCreateNotifications($ticket));

        $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

        $ticketData = [
            'ticket_username' => $ticket->cust->username,
            'ticket_id' => $ticket->ticket_id,
            'ticket_title' => $ticket->subject,
            'ticket_status' => $ticket->status,
            'ticket_description' => $ticket->message,
            'ticket_customer_url' => route('guest.ticketdetailshow', encrypt($ticket->ticket_id)),
            'ticket_admin_url' => url('/admin/ticket-view/'.encrypt($ticket->ticket_id)),
        ];

        try{

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
                        if($admin->usetting->emailnotifyon == 1){
                            dispatch((new MailSend($admin->email, 'admin_send_email_ticket_created', $ticketData)));
                        }
                    }

                }else{

                    $user = User::whereIn('id', $icc)->where('status', 1)->get();
                    foreach($user as $users){
                        if($users->usetting->emailnotifyon == 1){
                            dispatch((new MailSend($users->email, 'admin_send_email_ticket_created', $ticketData)));
                        }
                    }
                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->where('users.status', 1)->get();
                    foreach($admins as $admin){
                        if($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1){
                            dispatch((new MailSend($admin->email, 'admin_send_email_ticket_created', $ticketData)));
                        }
                    }


                }
            }else{
                foreach(usersdata() as $admin){
                    if($admin->usetting->emailnotifyon == 1){
                        dispatch((new MailSend($admin->email, 'admin_send_email_ticket_created', $ticketData)));
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
            } else {
                dispatch((new MailSend($ticket->cust->email, 'customer_send_guestticket_created', $ticketData)));
                if($ccemailsend->ccemails != null){
                    dispatch((new MailSend($ccemailsend->ccemails, 'customer_send_guestticket_created', $ticketData)));
                }
            }

        }catch(\Exception $e){
            return $ticket;
        }
        return $ticket;

    }

    public function violationdetails(Request $request, $id)
    {
        $cust = Ticket::find($id);
        $ticdata = Ticket::where('ticketviolation', 'on')->where('cust_id',$cust->cust_id)->count();

        if($ticdata < setting('max_tic_to_violation')){
            $allowedpattern = 'only_ticket';
        }else{
            $allowedpattern = 'ticket_and_customer';
        }


        return response()->json(['allowedpattern' => $allowedpattern]);
    }


    public function employeesreplyingstore(Request $request)
    {
        $this->authorize('Ticket Edit');
        $ticket = Ticket::findOrFail($request->ticketId);
        $ticket->employeesreplying = $request->userID;
        $ticket->employeereplytime = now();
        $ticket->save();

    }

    public function employeesreplyingremove(Request $request)
    {
        $this->authorize('Ticket Edit');
        $ticket = Ticket::findOrFail($request->ticketId);
        $ticket->employeesreplying = null;
        $ticket->employeereplytime = null;
        $ticket->save();
    }

    public function getemployeesreplying($ticket_id)
    {
        $this->authorize('Ticket Edit');
        $ticket = Ticket::findOrFail($ticket_id);
        $carbonInstance = Carbon::parse($ticket->employeereplytime);
        $diff_time = $carbonInstance->timezone(setting('default_timezone'))->diffForHumans();
        $empList = explode(",", $ticket->employeesreplying);

        $employee = User::get();
        $employees = [];
        $empnames = 'empnames';
        forEach($employee as $emp){
            if(in_array($emp->id , $empList) && $emp->id != Auth::id()){
                array_push($employees, $emp);
            }
        }

        return response()->json(['employees' => $employees, 'empnames' => $empnames, 'diff_time' => $diff_time]);
    }



    public function guestmedia(Request $request)
    {
        $path = public_path('uploads/guestticket/');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function note(Request $request){

        $ticketnote = Ticketnote::create([
            'ticket_id' => $request->input('ticket_id'),
            'user_id' => Auth::user()->id,
            'ticketnotes' => $request->input('ticketnote')
        ]);

        $ticket = Ticket::where('id', $request->input('ticket_id'))->firstOrFail();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->currentAction = 'Note Created';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        $user = User::findOrFail($ticketnote->user_id);
        $ticketData = [
            'ticket_id' => $ticket->ticket_id,
            'note_username' => $user->name,
            'ticket_note' => $ticketnote->ticketnotes,
            'ticket_admin_url' => url('/admin/ticket-view/'.encrypt($ticket->ticket_id)),
        ];

        try{
            $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
            foreach($admins as $admin){
                if($admin->usetting->emailnotifyon == 1 && $admin->getRoleNames()[0] == 'superadmin' && setting('NOTE_CREATE_MAILS') == 'on' && $ticketnote->user_id != $admin->id){
                    dispatch((new MailSend($admin->email, 'send_mail_to_admin_when_ticket_note_created', $ticketData)));
                }
            }
        }
        catch(\Exception $e){
            return response()->json(['success'=> lang('The note was successfully submitted.', 'alerts')]);
        }


        return response()->json(['success'=> lang('The note was successfully submitted.', 'alerts')]);
    }

    public function noteshow($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments;
        $category = $ticket->category;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.viewticket.note', compact('ticket','category', 'comments', 'title','footertext'))->with($data);
    }

    public function notedestroy($id)
    {
        $ticketnotedelete = Ticketnote::find($id);



        $ticket = Ticket::where('id', $ticketnotedelete->ticket_id)->firstOrFail();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;

            $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $ticket->overduestatus;
            $tickethistory->status = $ticket->status;
            $tickethistory->currentAction = 'Note Deleted';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];

            $tickethistory->save();

        $ticketnotedelete->delete();

        return response()->json(['success'=> lang('The note was successfully deleted.', 'alerts')]);


    }

    public function sublist(Request $request){

        $parent_id = $request->cat_id;

        $subcategories =Projects::select('projects.*','projects_categories.category_id')->join('projects_categories','projects_categories.projects_id', 'projects.id')
        ->where('projects_categories.category_id',$parent_id)
        ->get();

        return response()->json([
            'subcategories' => $subcategories
        ]);

    }


    public function changepriority(Request $req){

        $this->validate($req, [
            'priority_user_id' => 'required',
        ]);

        $priority = Ticket::find($req->priority_id);
        $priority->priority = $req->priority_user_id;
        $priority->update();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $priority->id;

        $tickethistory->ticketnote = $priority->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $priority->overduestatus;
        $tickethistory->status = $priority->status;
        $tickethistory->currentAction = 'Priority Updated';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];

        $tickethistory->save();

        $priorityname = $priority->priority;
        return response()->json(['priority' => $priorityname,'success' => lang('Updated successfully', 'alerts')], 200);
    }

    public function alltickets()
    {

        if(Auth::user()->dashboard == 'Admin'){
            return $this->adminalltickets();
        }
        if(Auth::user()->dashboard == 'Employee' || Auth::user()->dashboard == null){
            return $this->employeealltickets();
        }


    }

    public function adminalltickets()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $alltickets = Ticket::latest('updated_at')->get();

        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $alltickets->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $alltickets->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $data['perPage'] = $perPage;

        if(request()->ajax()){
            return response()->json([
                'rendereddata'=>view('admin.superadmindashboard.tabledatainclude', ['ticketdata' => $data['ticketdata'], 'perPage' => $perPage])->render(),
            ]);
        }

        $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
        $data['ticketnote'] = $ticketnote;

        return view('admin.superadmindashboard.alltickets')->with($data);

    }

    public function employeealltickets()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $agent = User::count();
        $data['agent'] = $agent;

        $customer = Customer::count();
        $data['customer'] = $customer;

        $groups =  Groups::where('groupstatus', '1')->get();

        $group_id = '';
        foreach ($groups as $group) {
            $group_id .= $group->id . ',';
        }

        $groupexists = Groupsusers::whereIn('groups_id', explode(',', substr($group_id, 0, -1)))->where('users_id', Auth::id())->exists();

        // if there in group get group tickets
        if($groupexists){

            $gticket = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereNotNull('groups_users.users_id')
            ->where('groups_users.users_id', Auth::id())
            ->latest('tickets.updated_at')
            ->get();
            $data['gtickets'] = $gticket;

        $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
        $data['ticketnote'] = $ticketnote;
        }
        // If no there in group we get the all tickets
        else{


            $gtickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereNull('groups_users.users_id')
            ->latest('tickets.updated_at')
            ->get();;
            $data['gtickets'] = $gtickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;
        }

        return view('admin.viewticket.alltickets')->with($data);
    }
}
