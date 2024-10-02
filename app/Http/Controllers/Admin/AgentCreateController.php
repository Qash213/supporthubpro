<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Countries;
use App\Models\Timezone;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Ticket\Category;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\usersettings;
use App\Models\Seosetting;
use App\Models\Pages;
use Session;
use Crypt;
use Illuminate\Support\Str;
use Mail;
use App\Mail\mailmailablesend;
use App\Imports\UserImport;
use App\Jobs\MailSend;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use App\Models\Department;
use App\Models\senduserlist;
use App\Models\Ticket\Ticket;
use App\Models\ticketassignchild;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Image;
use File;
use Auth;

class AgentCreateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('Employee Access');

        $user = User::with('permissions')->get();
        $data['users'] = $user;

        $roles = Role::get();
        $data['roles'] = $roles;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $categories = Category::whereIn('display', ['ticket', 'both'])->where('status', '1')->get();
        $data['categories'] = $categories;

        return view('admin.agent.index')->with($data)->with('i', (request()->input('page', 1) - 1) * 5);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('Employee Create');
        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::get();
        $data['timezones'] = $timezones;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $roles = Role::get();
        $data['roles'] = $roles;

        $departments = Department::where('status', '1')->get();
        $data['departments'] = $departments;

        return view('admin.agent.agentprofilecreate')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('Employee Create');

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'empid' => 'required|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required',
            'password' => 'required|string|min:8',
        ]);
        if ($request->phone) {
            $request->validate([
                'phone' => 'numeric',
            ]);
        }

        if ($request->file('image') != null) {
            $request->validate([
                'image' => 'mimes:jpeg,jpg,png|required|max:5120',
            ]);
        }

        $user = User::create([
            'firstname' => Str::ucfirst($request->input('firstname')),
            'lastname' => Str::ucfirst($request->input('lastname')),
            'empid' => Str::upper($request->empid),
            'email' => $request->email,
            'status' => '1',
            'password' => Hash::make($request->password),
            'skills' => $request->skills,
            'phone' => $request->phone,
            'country' => $request->country,
            'timezone' => $request->timezone,
            'departments' => $request->department,
            'dashboard' => $request->dashboard,
            'image' => null,
            'verified' => '1',

        ]);

        $users = User::find($user->id);
        $users->name = $user->firstname . ' ' . $user->lastname;
        $users->languagues = $request->languages;
        $users->darkmode = setting('DARK_MODE');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileArray = array('image' => $file);
            $rules = array(
                'image' => 'mimes:jpeg,jpg,png|required|max:5120' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            if ($validator->fails()) {
                return redirect()->back()->with('error', lang('Please check the format and size of the file.', 'alerts'));
            } else {

                $image_name = time() . '.' . $file->getClientOriginalExtension();
                $provider = storage()->provider;
                $existprovider = existprovider($user->storage_disk);
                if($existprovider)
                   $existprovider->provider::delete('/uploads/profile' . "/" . $user->image);
                $upload =  $provider::uploadprofile($file,$image_name);
                if($upload)
                   $users->update(['image' => $image_name,'storage_disk' => storage()->storage_disk]);
                else
                   return redirect('admin/profile')->with('error', lang('Image upload failed please try again.', 'alerts'));
            }
        }


        $users->update();

        $user->assignRole([$request->role]);


        $usersetting = new usersettings();
        $usersetting->users_id = $users->id;
        $usersetting->emailnotifyon = '1';
        $usersetting->save();

        $ticketData = [
            'userpassword' => $request->password,
            'username' => $user->firstname . ' ' . $user->lastname,
            'useremail' => $user->email,
            'url' => url('/admin'),
        ];

        try {
            if ($user->usetting->emailnotifyon == 1) {
                dispatch((new MailSend($user->email, 'employee_send_registration_details', $ticketData)));
            }
        } catch (\Exception $e) {
            return redirect('admin/employee')->with('success', lang('A new employee was successfully added.', 'alerts'));
        }
        return redirect('admin/employee')->with('success', lang('A new employee was successfully added.', 'alerts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('Employee Edit');
        $id = decrypt($id);
        $user = User::where('id', $id)->first();
        $data['user'] = $user;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::get();
        $data['timezones'] = $timezones;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $roles = Role::get();
        $data['roles'] = $roles;

        $departments = Department::where('status', '1')->get();
        $data['departments'] = $departments;

        return view('admin.agent.agentprofile')->with($data);
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
        $this->authorize('Employee Edit');

        $id = decrypt($id);
        $employee = User::find($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
        ]);
        if ($request->phone) {
            $request->validate([
                'phone' => 'numeric',
            ]);
        }
        if ($request->role) {
            $request->validate([
                'role' => 'required',
            ]);
        }
        if ($request->email == $employee->email) {
            $request->validate([
                'email' => 'required|string|email|max:255',
            ]);
        } else {
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users',
            ]);
        }
        if ($request->empid == $employee->empid) {
            $request->validate([
                'empid' => 'required|max:255',
            ]);
        } else {
            $request->validate([
                'empid' => 'required|max:255||unique:users',
            ]);
        }



        $user = User::where('id', $id)->findOrFail($id);

        if(Auth::user()->id == 1){
            $editallow = 'allowed';
        }elseif($user->id == Auth::user()->id || $user->getRoleNames()[0] != 'superadmin'){
            $editallow = 'allowed';
        }else{
            $editallow = 'notallowed';
        }

        if($editallow == 'allowed'){
            $user->firstname = Str::ucfirst($request->input('firstname'));
            $user->lastname = Str::ucfirst($request->input('lastname'));
            if ($request->email != $employee->email) {
                $user->email = $request->email;
            }
            if ($request->empid != $employee->empid) {

                $user->empid = Str::upper($request->empid);
            }
            $user->languagues = $request->languages;
            $user->skills = $request->skills;
            $user->phone = $request->phone;
            $user->country = $request->country;
            $user->timezone = $request->timezone;
            $user->departments = $request->department;
            $user->dashboard = $request->dashboard;

            $user->status = $request->input('status');

            $user->update();


            $users = User::find($user->id);

            $users->name = $user->firstname . ' ' . $user->lastname;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileArray = array('image' => $file);
                $rules = array(
                    'image' => 'mimes:jpeg,jpg,png|required|max:5120' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                if ($validator->fails()) {
                    return redirect()->back()->with('error', lang('Please check the format and size of the file.', 'alerts'));
                } else {
                    $image_name = time() . '.' . $file->getClientOriginalExtension();
                    $provider = storage()->provider;
                    $existprovider = existprovider($user->storage_disk);
                    if($existprovider)
                    $existprovider->provider::delete('/uploads/profile' . "/" . $user->image);
                    $upload =  $provider::uploadprofile($file,$image_name);
                    if($upload)
                    $users->update(['image' => $image_name,'storage_disk' => storage()->storage_disk]);
                    else
                    return redirect('admin/profile')->with('error', lang('Image upload failed please try again.', 'alerts'));

                }
            }

            $users->update();
        }else{
            return redirect()->back()->with('error', lang('You are not allowed to update this profile.', 'alerts'));
        }

        if($employee->id == Auth::user()->id && $request->role != $user->getRoleNames()[0]){
            return redirect()->back()->with('error', lang('You are not eligible to update your role.', 'alerts'));
        }else{
            if(!empty($user->getRoleNames()[0])){
                $user->removeRole( $user->getRoleNames()[0] );
            }
            $user->assignRole($request->role);
        }

        return redirect('admin/employee')->with('success', lang('The employee’s profile was successfully updated.', 'alerts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('Employee Delete');

        $id = decrypt($id);

        if ($id == 1) {
            return;
        }

        $user = User::where('id', $id)->findOrFail($id);

        if(Auth::user()->id == 1){
            $editallow = 'allowed';
        }elseif($user->id == Auth::user()->id || $user->getRoleNames()[0] != 'superadmin'){
            $editallow = 'allowed';
        }else{
            $editallow = 'notallowed';
        }

        if($editallow == 'allowed'){
            // to remove myassignid and selfassignid when destroying employee
            $assigneeid = ticketassignchild::where('toassignuser_id', $id)->get();
            if ($assigneeid->all() != null) {

                $ticketData = [
                    'myassignuser_id' => null,
                    'selfassignuser_id' => null,
                ];

                $ticketchildList = ticketassignchild::all();
                $groupedByValue = $ticketchildList->groupBy('ticket_id');
                $single = $groupedByValue->filter(function (Collection $groups) {
                    return $groups->count() == 1;
                });
                foreach ($single as $ind) {
                    foreach ($ind as $i) {
                        if ($i->ticket_id == $assigneeid[0]->ticket_id) {
                            Ticket::where('id', $i->ticket_id)->update($ticketData);
                        }
                    }
                }
            }

            $user->usetting()->delete();
            $custnotifications = senduserlist::where('touser_id',$user->id)->get();
            foreach($custnotifications as $custnotification){
                $custnotifycount = senduserlist::where('mail_id',$custnotification->mail_id)->count();
                if($custnotifycount == 1){
                    $custnotification->sendmaildata->delete();
                }
                $custnotification->delete();
            }
            // $user->usercustomsetting()->delete();

            $user->delete();

            return response()->json(['success' => lang('The employee was successfully deleted.', 'alerts')]);
        }else{
            return response()->json(['error' => lang('You are not allowed to delete this profile.', 'alerts')]);
        }

    }

    public function usermassdestroy(Request $request)
    {
        $this->authorize('Employee Delete');

        $student_id_arrays = $request->input('id');

        $student_id_array = array_map(function ($encryptedValue) {
            return decrypt($encryptedValue);
        }, $student_id_arrays);

        $customers = User::whereIn('id', $student_id_array)->where('id', '!=', 1)->get();

        foreach ($customers as $user) {
            // to remove myassignid and selfassignid when destroying employee
            $assigneeid = ticketassignchild::where('toassignuser_id', $user->id)->get();
            if ($assigneeid->all() != null) {

                $ticketData = [
                    'myassignuser_id' => null,
                    'selfassignuser_id' => null,
                ];

                $ticketchildList = ticketassignchild::all();
                $groupedByValue = $ticketchildList->groupBy('ticket_id');
                $single = $groupedByValue->filter(function (Collection $groups) {
                    return $groups->count() == 1;
                });
                foreach ($single as $ind) {
                    foreach ($ind as $i) {
                        if ($i->ticket_id == $assigneeid[0]->ticket_id) {
                            Ticket::where('id', $i->ticket_id)->update($ticketData);
                        }
                    }
                }
            }

            $user->usetting()->delete();
            $custnotifications = senduserlist::where('touser_id',$user->id)->get();
            foreach($custnotifications as $custnotification){
                $custnotifycount = senduserlist::where('mail_id',$custnotification->mail_id)->count();
                if($custnotifycount == 1){
                    $custnotification->sendmaildata->delete();
                }
                $custnotification->delete();
            }
            // $user->usercustomsetting()->delete();

            $user->delete();
        }
        return response()->json(['success' => lang('The employee was successfully deleted.', 'alerts')]);
    }

    public function status(Request $request, $id)
    {
        $this->authorize('Employee Edit');

        $id = decrypt($id);
        $calID = User::find($id);

        if(Auth::user()->id == 1){
            $editallow = 'allowed';
        }elseif($user->id == Auth::user()->id || $user->getRoleNames()[0] != 'superadmin'){
            $editallow = 'allowed';
        }else{
            $editallow = 'notallowed';
        }

        if($editallow == 'allowed'){
            $calID->status = $request->status;
            $calID->save();

            return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
        }else{
            return response()->json(['code' => 500, 'error' => lang('You are not allowed to update this profile.', 'alerts')], 500);
        }
    }

    public function userimportindex()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.agent.userimport')->with($data);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function usercsv(Request $req)
    {
        $this->authorize('Employee Importlist');
        if ($req->hasFile('file')) {
            $file = $req->file('file')->store('import');

            $import = Excel::import(new UserImport, $file);

            return redirect()->route('employee')->with('success', lang('The employee list was imported successfully.', 'alerts'));
        } else {
            return redirect()->back()->with('error', 'Please select file to import data of Employee.');
        }
    }

    public function employeepasswordreset(Request $req)
    {
        $this->authorize('Employee Edit');

        $id = decrypt($req->sprukopasswordreset_id);

        $passwordchanging = User::find($id);

        if(Auth::user()->id == 1){
            $editallow = 'allowed';
        }elseif($passwordchanging->id == Auth::user()->id || $passwordchanging->getRoleNames()[0] != 'superadmin'){
            $editallow = 'allowed';
        }else{
            $editallow = 'notallowed';
        }

        if($editallow == 'allowed'){
            $passwordchanging->password = Hash::make($req->resetpassword);
            $passwordchanging->update();

            return response()->json(['success' => lang('The password has been successfully changed!', 'alerts')], 200);
        }else{
            return response()->json(['code' => 500, 'error' => lang('You are not allowed to change password.', 'alerts')], 500);
        }
    }
}
