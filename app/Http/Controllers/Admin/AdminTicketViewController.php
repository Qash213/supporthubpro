<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Category;
use App\Models\Groupsusers;
use Auth;
use DB;
use App\Models\tickethistory;
use App\Models\Customer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdminTicketViewController extends Controller
{
    public function customerprevioustickets($cust_id)
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $cust_id = decrypt($cust_id);

        $users = Customer::find($cust_id);
        $data['users'] = $users;

        $total = Ticket::where('cust_id', $cust_id)->latest('updated_at')->get();
        $data['total'] = $total;

        $custsimillarticket = Ticket::where('cust_id', $cust_id)->latest('updated_at')->get();
        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $custsimillarticket->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $custsimillarticket->count(),
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

        $active = Ticket::where('cust_id', $cust_id)->whereIn('status', ['New', 'Re-Open', 'Inprogress'])->get();
        $data['active'] = $active;

        $closed = Ticket::where('cust_id', $cust_id)->where('status', 'Closed')->get();
        $data['closed'] = $closed;

        $onhold = Ticket::where('cust_id', $cust_id)->where('status', 'On-Hold')->get();
        $data['onhold'] = $onhold;

        return view('admin.viewticket.customerprevioustickets')->with($data);
    }

    public function selfassignticketview()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $selfassignedtickets = Ticket::where('selfassignuser_id', auth()->id())->where('status', '!=' ,'Closed')->where('status', '!=' ,'Suspend')->latest('updated_at')->get();
        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $selfassignedtickets->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $selfassignedtickets->count(),
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

        // ticket note
        $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
        $data['ticketnote'] = $ticketnote;


        $selfassignedticketsnew = Ticket::where('selfassignuser_id', auth()->id())->where('status', 'New')->count();
        $data['selfassignedticketsnew'] = $selfassignedticketsnew;

        $selfassignedticketsinprogress = Ticket::where('selfassignuser_id', auth()->id())->where('status', 'Inprogress')->count();
        $data['selfassignedticketsinprogress'] = $selfassignedticketsinprogress;

        $selfassignedticketsonhold = Ticket::where('selfassignuser_id', auth()->id())->where('status', 'On-Hold')->count();
        $data['selfassignedticketsonhold'] = $selfassignedticketsonhold;

        $selfassignedticketsreopen = Ticket::where('selfassignuser_id', auth()->id())->where('status', 'Re-Open')->count();
        $data['selfassignedticketsreopen'] = $selfassignedticketsreopen;

        $selfassignedticketsoverdue = Ticket::where('selfassignuser_id', auth()->id())->where('overduestatus', 'Overdue')->count();
        $data['selfassignedticketsoverdue'] = $selfassignedticketsoverdue;

        $selfassignedticketsclosed = Ticket::where('selfassignuser_id', auth()->id())->where('status', 'Closed')->count();
        $data['selfassignedticketsclosed'] = $selfassignedticketsclosed;

        return view('admin.superadmindashboard.mytickets.selfassignticket')->with($data);
    }

    public function myclosedtickets()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $myclosedbyuser = Ticket::where('closedby_user', auth()->id())->latest('updated_at')->get();
        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $myclosedbyuser->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $myclosedbyuser->count(),
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

        return view('admin.assignedtickets.myclosedtickets')->with($data);
    }

    public function tickettrashed()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $tickettrashed = Ticket::onlyTrashed()->latest('updated_at')->get();
        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $tickettrashed->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $tickettrashed->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $data['perPage'] = $perPage;

        if(request()->ajax()){
            return response()->json([
                'rendereddata'=>view('admin.assignedtickets.trashedtableinclude', ['ticketdata' => $data['ticketdata'], 'perPage' => $perPage])->render(),
            ]);
        }

        return view('admin.assignedtickets.trashedticket')->with($data);
    }

    public function tickettrashedrestore(Request $request, $id)
    {
        $id = decrypt($id);
        $tickettrashedrestore = Ticket::onlyTrashed()->findOrFail($id);
        $commenttrashedrestore = $tickettrashedrestore->comments()->onlyTrashed()->get();

        if (count($commenttrashedrestore) > 0) {


            $commenttrashedrestore->each->restore();

            $media = Media::onlyTrashed()->where('model_id', $tickettrashedrestore->id)->where('model_type', get_class($tickettrashedrestore))->where('collection_name', 'ticket')->get();

            foreach ($media as $media) {
                $media->restore();
            }

            foreach ($commenttrashedrestore as $comment) {
                foreach(Media::onlyTrashed()->where('model_id', $comment->id)->where('model_type', get_class($comment))
                ->where('collection_name', 'comments')->get() as $media){
                    $media->restore();
                }

            }

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $tickettrashedrestore->id;

            $tickethistory->ticketnote = $tickettrashedrestore->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $tickettrashedrestore->overduestatus;
            $tickethistory->status = $tickettrashedrestore->status;
            $tickethistory->currentAction = 'Ticket Restore';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];

            $tickethistory->save();

            foreach($tickettrashedrestore->ticket_history()->onlyTrashed()->get() as $deletetickethistory)
            {
                $deletetickethistory->restore();
            }


            $tickettrashedrestore->restore();
            return response()->json(['success'=>lang('The ticket was successfully restore.', 'alerts')]);
        }else{
            $tickettrashedrestore->restore();

            $media = Media::onlyTrashed()->where('model_id', $tickettrashedrestore->id)->where('model_type', get_class($tickettrashedrestore))->where('collection_name', 'ticket')->get();

            foreach ($media as $media) {
                $media->restore();
            }


            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $tickettrashedrestore->id;

            $tickethistory->ticketnote = $tickettrashedrestore->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $tickettrashedrestore->overduestatus;
            $tickethistory->status = $tickettrashedrestore->status;
            $tickethistory->currentAction = 'Ticket Restore';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];

            $tickethistory->save();

            foreach($tickettrashedrestore->ticket_history()->onlyTrashed()->get() as $deletetickethistory)
            {
                $deletetickethistory->restore();
            }

            return response()->json(['success'=> lang('The ticket was successfully restore.', 'alerts')]);

        }
    }

    public function tickettrasheddestroy($id)
    {
        $id = decrypt($id);
        $tickettrasheddelete = Ticket::onlyTrashed()->findOrFail($id);
        $commenttrasheddelete = $tickettrasheddelete->comments()->onlyTrashed()->get();


        if (count($commenttrasheddelete) > 0) {
            $media = $tickettrasheddelete->getMedia('ticket');

            foreach ($media as $medias) {

                    $medias->forceDelete();

            }
            $medias = $tickettrasheddelete->comments()->onlyTrashed()->get();

            foreach ($medias as $mediass) {
                foreach($mediass->getMedia('comments') as $mediasss){

                    $mediasss->forceDelete();
                }

            }
            $commenttrasheddelete->each->forceDelete();

            foreach($tickettrasheddelete->ticket_history()->onlyTrashed()->get() as $deletetickethistory)
            {
                $deletetickethistory->forceDelete();
            }
            $tickettrasheddelete->forceDelete();
            return response()->json(['success'=>lang('The ticket was successfully deleted.', 'alerts')]);
        }else{

            $media = $tickettrasheddelete->getMedia('ticket');

            foreach ($media as $medias) {

                    $medias->forceDelete();

            }

            foreach($tickettrasheddelete->ticket_history()->onlyTrashed()->get() as $deletetickethistory)
            {
                $deletetickethistory->forceDelete();
            }
            $tickettrasheddelete->forceDelete();

            return response()->json(['success'=> lang('The ticket was successfully deleted.', 'alerts')]);

        }
    }


    public function tickettrashedview($id)
    {
        $id = decrypt($id);
        $tickettrashedview = Ticket::onlyTrashed()->findOrFail($id);
        $data['tickettrashedview'] = $tickettrashedview;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.assignedtickets.trashedticketview')->with($data);
    }


    public function alltrashedticketrestore(Request $request)
    {

        $id_arrays = $request->input('id');

        $id_array = array_map(function ($encryptedValue) {
            return decrypt($encryptedValue);
        }, $id_arrays);

        $sendmails = Ticket::onlyTrashed()->whereIn('id', $id_array)->get();

        foreach($sendmails as $tickettrashedrestoreall){
            $commenttrashedrestorealls = $tickettrashedrestoreall->comments()->onlyTrashed()->get();
            foreach($commenttrashedrestorealls as $commenttrashedrestoreall){
                    $commenttrashedrestoreall->restore();
                    foreach(Media::onlyTrashed()->where('model_id', $commenttrashedrestoreall->id)->where('model_type', get_class($commenttrashedrestoreall))
                    ->where('collection_name', 'comments')->get() as $media){
                        $media->restore();
                    }
            }
            $media = Media::onlyTrashed()->where('model_id', $tickettrashedrestoreall->id)->where('model_type', get_class($tickettrashedrestoreall))->where('collection_name', 'ticket')->get();

            foreach ($media as $media) {
                $media->restore();
            }
            $tickettrashedrestoreall->restore();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $tickettrashedrestoreall->id;

            $tickethistory->ticketnote = $tickettrashedrestoreall->ticketnote->isNotEmpty();
            $tickethistory->overduestatus = $tickettrashedrestoreall->overduestatus;
            $tickethistory->status = $tickettrashedrestoreall->status;
            $tickethistory->currentAction = 'Ticket Restore';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];

            $tickethistory->save();

            foreach($tickettrashedrestoreall->ticket_history()->onlyTrashed()->get() as $deletetickethistory)
            {
                $deletetickethistory->restore();
            }

        }
        return response()->json(['success'=> lang('The ticket was successfully restore.', 'alerts')]);

    }

    public function alltrashedticketdelete(Request $request)
    {
        $id_arrays = $request->input('id');

        $id_array = array_map(function ($encryptedValue) {
            return decrypt($encryptedValue);
        }, $id_arrays);

        $sendmails = Ticket::onlyTrashed()->whereIn('id', $id_array)->get();

        foreach($sendmails as $tickettrasheddeleteeall){

            $commenttrasheddeleteall = $tickettrasheddeleteeall->comments()->onlyTrashed()->get();


            if (count($commenttrasheddeleteall) > 0) {
                $media = $tickettrasheddeleteeall->getMedia('ticket');

                foreach ($media as $medias) {

                        $medias->forceDelete();

                }

                foreach ($commenttrasheddeleteall as $mediass) {
                    foreach($mediass->getMedia('comments') as $mediasss){

                        $mediasss->forceDelete();
                    }

                    $mediass->forceDelete();
                }

                foreach($tickettrasheddeleteeall->ticket_history()->onlyTrashed()->get() as $deletetickethistory)
                {
                    $deletetickethistory->forceDelete();
                }


                $sendmails->each->forceDelete();
                return response()->json(['success'=>lang('The ticket was successfully deleted.', 'alerts')]);
            }else{

                $media = $tickettrasheddeleteeall->getMedia('ticket');

                foreach ($media as $medias) {

                    $medias->forceDelete();

                }

                foreach($tickettrasheddeleteeall->ticket_history()->onlyTrashed()->get() as $deletetickethistory)
                {
                    $deletetickethistory->forceDelete();
                }


                $sendmails->each->forceDelete();

                return response()->json(['success'=> lang('The ticket was successfully deleted.', 'alerts')]);

            }

        }
    }

    public function allactiveinprogresstickets()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $allactiveinprogresstickets = Ticket::where('status', 'Inprogress')->get();
        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $allactiveinprogresstickets->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $allactiveinprogresstickets->count(),
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

        return view('admin.superadmindashboard.activetickets.activeinprogressticket')->with($data);
    }

    public function allactivereopentickets()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $allactivereopentickets = Ticket::whereIn('status', ['Re-Open'])->get();
        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $allactivereopentickets->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $allactivereopentickets->count(),
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

        return view('admin.superadmindashboard.activetickets.activereopenticket')->with($data);
    }

    public function allactiveonholdtickets()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $allactiveonholdtickets = Ticket::whereIn('status', ['On-Hold'])->get();
        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $allactiveonholdtickets->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $allactiveonholdtickets->count(),
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

        return view('admin.superadmindashboard.activetickets.activeonholdticket')->with($data);
    }

    public function allactiveassignedtickets()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $allactiveassignedtickets = Ticket::whereIn('status', ['Re-Open','Inprogress','On-Hold'])
            ->where(function ($query) {
                $query->whereNotNull('selfassignuser_id')->orWhereNotNull('myassignuser_id');
            })
            ->groupBy('tickets.id')->get();
        $perPage = request()->input('per_page', 10);
        $currentPage = request()->input('page', 1);
        $finalResult = $allactiveassignedtickets->forPage($currentPage, $perPage)->values();

        $data['ticketdata'] = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalResult,
            $allactiveassignedtickets->count(),
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

        return view('admin.superadmindashboard.activetickets.activeassignedticket')->with($data);
    }

    public function tickethistory($id)
    {
        $id = decrypt($id);
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        $ticket = Ticket::where('ticket_id', $id)->firstOrFail();
        $data['ticket'] = $ticket;
        return view('admin.tickethistory.index')->with($data);
    }

}
