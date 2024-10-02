<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\usersettings;
use App\Models\Employeerating;
use App\Models\Customer;
use App\Models\Countries;
use App\Models\Timezone;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use Illuminate\Support\Facades\Validator;
use Hash;
use File;
use Image;
use Illuminate\Support\Str;
use Mail;
use App\Mail\mailmailablesend;
use App\Imports\CustomerImport;
use App\Jobs\MailSend;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use DataTables;
use Session;
use App\Models\VerifyUser;
use App\Models\TicketCustomfield;
use App\Models\VerifyOtp;
use App\Models\Announcement;
use App\Models\CustomerSetting;
use App\Models\Customfield;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Models\Ticket\Ticket;
use App\Models\tickethistory;
use PragmaRX\Google2FA\Google2FA;
use App\Models\SocialAuthSetting;
use App\Models\Holiday;
use App\Models\Sendmail;
use App\Models\senduserlist;
use App\Models\Ticketviolation;
use Illuminate\Support\Facades\Storage;

class AdminprofileController extends Controller
{
    use ThrottlesLogins,AuthenticatesUsers {
        logout as performLogout;
    }
    public function index()
    {


        $user = User::get();
        $data['users'] = $user;

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

        if (Auth::check() && Auth::user()->id) {
            $avgrating1 = Employeerating::where('user_id', Auth::id())->where('rating', '1')->count();
            $avgrating2 = Employeerating::where('user_id', Auth::id())->where('rating', '2')->count();
            $avgrating3 = Employeerating::where('user_id', Auth::id())->where('rating', '3')->count();
            $avgrating4 = Employeerating::where('user_id', Auth::id())->where('rating', '4')->count();
            $avgrating5 = Employeerating::where('user_id', Auth::id())->where('rating', '5')->count();

            $avgr = ((5 * $avgrating5) + (4 * $avgrating4) + (3 * $avgrating3) + (2 * $avgrating2) + (1 * $avgrating1));
            $avggr = ($avgrating1 + $avgrating2 + $avgrating3 + $avgrating4 + $avgrating5);

            if ($avggr == 0) {
                $avggr = 1;
                $avg = $avgr / $avggr;
            } else {
                $avg = $avgr / $avggr;
            }
        }

        return view('admin.profile.adminprofile', compact('avg'))->with($data);
    }

    public function adminemailchange(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(Hash::check($request->password, $user->password)){
            $emaildata = [
                'username' => $user->name,
                'useremail' => $user->email,
                'ticket_admin_url' => route('adminemailupdate',$user->email),
            ];

            try {

                dispatch((new MailSend($user->email, 'Send_email_to_admin_users_when_change_email', $emaildata)));

            } catch (\Exception$e) {

            }
            return response()->json(['success' => lang('Please check your email to change email id, we send a mail to your email.', 'alerts'), 'message' => 'linksend']);
        }else{
            return response()->json(['error' => lang('You are entered invalid password.', 'alerts'), 'message' => 'wrongpassword']);
        }
    }

    public function userpasswordverify(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(Hash::check($request->password, $user->password)){
            return response()->json(['success' => lang('Correct password.', 'alerts'), 'message' => $request->twofactorname == 'emailtwofact' ? 'email2famatched' : 'google2famatched']);
        }else{
            return response()->json(['error' => lang('You are entered invalid password.', 'alerts'), 'message' => 'wrongpassword']);
        }
    }

    public function adminemailupdate(Request $request, $id)
    {

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

        $holidays = Holiday::whereDate('startdate', '<=', $now->toDateString())->whereDate('enddate', '>=', $now->toDateString())->where('status', '1')->get();
        $data['holidays'] =  $holidays;

        $data['oldemail'] = $id;

        return view('admin.profile.updateemail')->with($data);
    }

    public function adminnewemailstore(Request $request)
    {
        $user = User::where('email', $request->oldemail)->first();
        if($user->email == $request->email){
            return response()->json(['error' => lang('This email is already linked to your account.', 'alerts'), 'email' => 'already']);
        }

        $request->validate([
            'email' => 'required|email|max:255|indisposable|unique:users',
        ]);

        $otpdata = VerifyOtp::where('type', 'emailupdate')->where('cust_id', $request->email)->first();
        $user = User::where('email', $request->oldemail)->first();

        if ($otpdata) {
            $otpdata->otp = rand(100000, 999999);
            $otpdata->update();
            if ($request->session()->has('emailupdate')) {
                $request->session()->forget('emailupdate');
            }
            $request->session()->put('emailupdate', $otpdata->cust_id);
            $custemailchange = [
                'otp' => $otpdata->otp,
                'useremail' => $otpdata->cust_id,
                'username' => $user->name,
            ];

            try {

                dispatch((new MailSend($otpdata->cust_id, 'Send_email_to_customer_when_change_email_otp_verification', $custemailchange)));

            } catch (\Exception$e) {
                return response()->json(['success' => lang('Please check your email to verify otp.', 'alerts'), 'otp' => 'exists']);
            }
            return response()->json(['success' => lang('Please check your email to verify otp.', 'alerts'), 'otp' => 'exists']);
        }
        if (!$otpdata) {
            $verifyOtp = VerifyOtp::create([
                'cust_id' => $request->email,
                'otp' => rand(100000, 999999),
                'type' => 'emailupdate',
            ]);

            if ($request->session()->has('emailupdate')) {
                $request->session()->forget('emailupdate');
            }
            $request->session()->put('emailupdate', $verifyOtp->cust_id);

            $custemailchange = [
                'otp' => $verifyOtp->otp,
                'useremail' => $verifyOtp->cust_id,
                'username' => $user->name,
            ];

            try {

                dispatch((new MailSend($verifyOtp->cust_id, 'Send_email_to_customer_when_change_email_otp_verification', $custemailchange)));

            } catch (\Exception$e) {

                // return response()->json(['success' => lang('Please check your email to verify otp.', 'alerts'), 'otp' => 'newotp']);
            }

            return response()->json(['success' => lang('Please check your email to verify otp.', 'alerts'), 'otp' => 'exists']);
        }
    }

    public function adminemailupdateotpverify(Request $request, $oldemail)
    {

        if ($request->session()->has('emailupdate')) {
            $emailvalidate = $request->session()->get('emailupdate');
        }
        $verify = VerifyOtp::where('type', 'emailupdate')->where('otp', $request->otp)->first();
        if ($verify) {
            if ($emailvalidate == $verify->cust_id) {
                $userfind = User::where('email', $oldemail)->first();
                $userfind->email = $verify->cust_id;
                $userfind->save();

                $this->performLogout($request);
                return redirect()->route('login')->with('success', lang('Your email id updated successfully.', 'alerts'));
            }else{
                return redirect()->back()->with('error', lang('Invalid OTP', 'alerts'));
            }
        } else {
            return redirect()->back()->with('error', lang('Invalid OTP', 'alerts'));
        }

        if (!$verify) {
            return redirect()->back()->with('error', lang('Invalid OTP', 'alerts'));
        }
    }


    public function profileedit()
    {
        $this->authorize('Profile Edit');
        $user = User::get();
        $data['users'] = $user;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::get();
        $data['timezones'] = $timezones;

        return view('admin.profile.adminprofileupdate')->with($data);
    }

    public function profilesetup(Request $request)
    {

        $this->authorize('Profile Edit');

        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        $user_id = Auth::user()->id;

        $user = User::findOrFail($user_id);
        $user->firstname = ucfirst($request->input('firstname'));

        $user->lastname = ucfirst($request->input('lastname'));
        $user->name = ucfirst($request->input('firstname')) . ' ' . ucfirst($request->input('lastname'));
        $user->gender = $request->input('gender');
        $user->languagues = $request->input('languages');
        $user->skills = $request->input('skills');
        $user->phone = $request->input('phone');
        $user->country = $request->input('country');
        $user->timezone = $request->input('timezone');


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
                $user->update(['image' => $image_name,'storage_disk' => storage()->storage_disk]);
                else
                return redirect('admin/profile')->with('error', lang('Image upload failed please try again.', 'alerts'));
            }
        }



        $user->update();
        return redirect('admin/profile')->with('success', lang('Your profile has been successfully updated.', 'alerts'));
    }

    public function imageremove(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $existprovider = existprovider($user->storage_disk);
        if($existprovider)
        $existprovider->provider::delete('/uploads/profile' . "/" . $user->image);

        $user->image = null;
        $user->update();

        return response()->json(['success' => lang('The profile image was successfully removed.', 'alerts')]);
    }


    // Customer function

    public function customers()
    {
        $this->authorize('Customers Access');
        $customer = Customer::get();
        $data['customers'] = $customer;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;



        return view('admin.customers.index')->with($data)->with('i', (request()->input('page', 1) - 1) * 5);
    }


    public function resendverification($email)
    {
        $user = Customer::where('email', '=', $email)->first();

        $existVerifyUser = VerifyUser::where('cust_id', $user->id)->get();
        if ($existVerifyUser != null) {
            foreach ($existVerifyUser as $existVerifyUsers) {
                $existVerifyUsers->delete();
            }
        }

        $verifyUser = VerifyUser::create([
            'cust_id' => $user->id,
            'token' => sha1(time())
        ]);

        $verifyData = [
            'username' => $user->username,
            'email' => $user->email,
            'email_verify_url' => route('verify.email', $verifyUser->token),
        ];

        try {

            dispatch((new MailSend($user->email, 'customer_sendmail_verification', $verifyData)));

        } catch (\Exception $e) {
            return response()->json(['success' => lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')]);
        }

        return response()->json(['success' => lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')]);
    }


    public function customerscreate()
    {
        $this->authorize('Customers Create');
        $user = Customer::get();
        $data['users'] = $user;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::get();
        $data['timezones'] = $timezones;

        $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->where('status',1)->get();
        $data['customfields'] = $customfields;

        return view('admin.customers.create')->with($data)->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function customersstore(Request $request)
    {
        $this->authorize('Customers Create');
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
        ]);

        if ($request->phone) {
            $request->validate([
                'phone' => 'numeric',
            ]);
        }


        // $custfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->where('status',1)->where('fieldrequired',1)->get();

        $customer = Customer::create([
            'firstname' => Str::ucfirst($request->input('firstname')),
            'lastname' => Str::ucfirst($request->input('lastname')),
            'email' => $request->email,
            'status' => '1',
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'image' => null,
            'verified' => '1',
            'userType' => 'Customer',

        ]);

        $customers = Customer::find($customer->id);
        $customers->username = $customer->firstname . ' ' . $customer->lastname;
        $customers->update();

        $customersetting = new CustomerSetting();
        $customersetting->custs_id = $customers->id;
        $customersetting->darkmode = null;
        $customersetting->save();

        $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->where('status',1)->get();

        foreach($customfields as $customfield)
        {
            $ticketcustomfield = new TicketCustomfield();
            $ticketcustomfield->cust_id = $customer->id;
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

        $customerData = [
            'userpassword' => $request->password,
            'username' => $customer->firstname . ' ' . $customer->lastname,
            'useremail' => $customer->email,
            'url' => url('/'),
        ];

        try {

            dispatch((new MailSend($customer->email, 'customer_send_registration_details', $customerData)));

        } catch (\Exception $e) {
            return redirect('admin/customer')->with('success', lang('A new customer was successfully added.', 'alerts'));
        }
        return redirect('admin/customer')->with('success', lang('A new customer was successfully added.', 'alerts'));
    }

    public function customersshow($id)
    {
        $this->authorize('Customers Edit');

        $id = decrypt($id);

        $user = Customer::where('id', $id)->first();
        if(!$user)
          abort(404);
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

        $customfield = TicketCustomfield::where('cust_id', $id)->get();
        $data['customfield'] = $customfield;

        return view('admin.customers.show')->with($data);
    }

    public function voilating(Request $request)
    {
        $this->authorize('Customers Edit');

        $request->validate([
            'ticketviolation' => 'required',
        ]);

        $ticket = Ticket::find($request->ticket_id);

        if($request->ticket_violation_id != null){
            $ticketnote = Ticketviolation::find($request->ticket_violation_id);
            $ticketnote->ticketviolation = $request->ticketviolation;
            $ticketnote->save();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;
            $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
            $tickethistory->ticketviolation = $ticket->ticketviolation;
            $tickethistory->overduestatus = $ticket->overduestatus;
            $tickethistory->status = $ticket->status;
            $tickethistory->currentAction = 'Ticket Violation Modified';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];
            $tickethistory->assignUser = null;


            $tickethistory->save();

            return response()->json(['success'=> lang('The violatied note is modified successfully.', 'alerts')]);
        }else{

            $custdetails = Customer::find($ticket->cust_id);

            $ticketnote = Ticketviolation::create([
                'ticket_id' => $request->input('ticket_id'),
                'user_id' => Auth::user()->id,
                'ticketviolation' => $request->input('ticketviolation')
            ]);

            $ticket = Ticket::where('id', $request->input('ticket_id'))->firstOrFail();
            $custdetails->voilated = 'on';
            $custdetails->update();

            $ticket->ticketviolation = 'on';
            $ticket->update();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;
            $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
            $tickethistory->ticketviolation = $ticket->ticketviolation;
            $tickethistory->overduestatus = $ticket->overduestatus;
            $tickethistory->status = $ticket->status;
            $tickethistory->currentAction = 'Added Ticket Violation';
            $tickethistory->username = Auth::user()->name;
            $tickethistory->type = Auth::user()->getRoleNames()[0];
            $tickethistory->assignUser = null;


            $tickethistory->save();

            return response()->json(['success'=> lang('The ticket is added as a violated ticket.', 'alerts')]);
        }

    }

    public function voilationnotedelete(Request $request, $id)
    {
        $this->authorize('Customers Edit');

        $ticketviolation = Ticketviolation::find($id);
        $ticketviolation->delete();

        $ticket = Ticket::where('id', $ticketviolation->ticket_id)->firstOrFail();
        $ticket->ticketviolation = null;
        $ticket->update();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $tickethistory->ticketnote = $ticket->ticketnote->isNotEmpty();
        $tickethistory->overduestatus = $ticket->overduestatus;
        $tickethistory->status = $ticket->status;
        $tickethistory->ticketviolation = $ticket->ticketviolation;
        $tickethistory->currentAction = 'Violation Deleted';
        $tickethistory->username = Auth::user()->name;
        $tickethistory->type = Auth::user()->getRoleNames()[0];
        $tickethistory->assignUser = null;

        $tickethistory->save();

        return response()->json(['success'=> lang('Customer violation note deleted successfully..', 'alerts')]);
    }

    public function voilationedit(Request $request, $id)
    {
        $this->authorize('Customers Edit');

        $ticketviolation = Ticketviolation::find($id);

        return response()->json(['violation' => $ticketviolation, 'success' => lang('Customer violation note details fetched successfully..', 'alerts')]);
    }

    public function unvoilating(Request $request, $id)
    {
        $this->authorize('Customers Edit');

        $cust = Customer::find($id);
        $cust->voilated = null;
        $cust->update();

        return redirect()->back()->with('success', lang('Customer removed from violated customer.', 'alerts'));
    }

    public function customersupdate(Request $request, $id)
    {
        $this->authorize('Customers Edit');

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        if ($request->phone) {
            $request->validate([
                'phone' => 'numeric',
            ]);
        }
        $id = decrypt($id);

        $user = Customer::where('id', $id)->findOrFail($id);

        $userexist = Customer::get();
        $userarray = [];
        foreach ($userexist as $userexists) {
            array_push($userarray, $userexists->email);
        }

        if ($user->email != $request->email && in_array($request->email, $userarray)) {
            return redirect()->back()->with('error', lang('You are given email is already existing please provide correct email.', 'alerts'));
        }

        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->username = $request->input('firstname') . ' ' . $request->input('lastname');
        $user->email = $request->input('email');
        $user->country = $request->input('country');
        $user->timezone = $request->input('timezone');
        $user->status = $request->input('status');
        $user->voilated = $request->input('voilated');
        $user->update();

        $formInputs = $request->all();

        foreach ($formInputs as $inputName => $inputValue) {
            if (strpos($inputName, 'customfield_') === 0) {
                $fieldName = explode('_', $inputName, 2)[1];
                $fieldName = str_replace('_', ' ', $fieldName);

                $customfields = TicketCustomfield::where('cust_id', $id)->get();
                foreach($customfields as $customfield){
                    if($fieldName == $customfield->fieldnames){
                        if($customfield->fieldtypes == 'checkbox'){
                            $inputValue = implode(',', $inputValue);
                        }
                        if($customfield->privacymode == '1'){
                            $customfield->values = encrypt($inputValue);
                        }else{
                            $customfield->values = $inputValue;
                        }
                        $customfield->save();
                    }
                }
            }
        }

        $request->session()->forget('email', $user->email);

        return redirect('/admin/customer')->with('success', lang('The customer profile was successfully updated.', 'alerts'));
    }

    public function customerimportindex()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.customers.customerimport')->with($data);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function customercsv(Request $req)
    {
        if ($req->hasFile('file')) {
            $file = $req->file('file')->store('import');

            $import = Excel::import(new CustomerImport, $file);

            return redirect()->route('admin.customer')->with('success', lang('The Customer list was imported successfully.', 'alerts'));
        } else {
            return redirect()->back()->with('error', 'Please select file to import data of Customer.');
        }
    }

    public function adminLogin(Request $request, $id)
    {
        if ($request->session()->get('customerlogin')) {
            request()->session()->forget('password_hash_customer');
            request()->session()->forget('customerlogin');

            if ($request->session()->get('twofactoremail')) {
                request()->session()->forget('twofactoremail');
            }

            if ($request->session()->get('googleauthid')) {
                request()->session()->forget('googleauthid');
            }

            Auth::guard('customer')->logout();
        }

        $id = decrypt($id);

        $customerExist = Customer::where(['id' => $id, 'status' => 0])->exists();
        if ($customerExist) {
            return redirect()->back()->with('success', lang('The account has been deactivated.', 'alerts'));
        }

        Auth::guard('customer')->loginUsingId($id, true);
        $request->session()->put('customerlogin', $id);
        $cust = Customer::find($id);
        if($cust->custsetting->twofactorauth == 'emailtwofact'){
            $request->session()->put('twofactoremail', $cust->email);
        }
        if($cust->custsetting->twofactorauth == 'googletwofact'){
            $request->session()->put('googleauthid', $cust->email);
        }


        return redirect()->intended('customer/');
    }

    public function customersdelete($id)
    {
        $this->authorize('Customers Delete');

        $id = decrypt($id);

        $user = Customer::findOrFail($id);

        $ticket = $user->tickets()->get();

        foreach ($ticket as $tickets) {
            foreach ($tickets->getMedia('ticket') as $media) {
                $media->delete();
            }
            foreach ($tickets->comments as $comment) {
                foreach ($comment->getMedia('comments') as $media) {
                    $media->delete();
                }
                $comment->delete();
            }
            $tickets->delete();
        }
        $user->custsetting()->delete();


        $customfields = TicketCustomfield::where('cust_id', $id)->get();
        foreach ($customfields as $customfield) {
            $customfield->delete();
        }

        $custnotifications = senduserlist::where('tocust_id',$user->id)->get();
        foreach($custnotifications as $custnotification){
            $custnotifycount = senduserlist::where('mail_id',$custnotification->mail_id)->count();
            if($custnotifycount == 1){
                $custnotification->sendmaildata->delete();
            }
            $custnotification->delete();
        }
        // $user->customercustomsetting()->delete();

        $user->delete();

        return response()->json(['success' => lang('The customer was deleted successfully.', 'alerts')]);
    }


    public function customermassdestroy(Request $request)
    {
        $student_id_arrays = $request->input('id');

        $student_id_array = array_map(function ($encryptedValue) {
            return decrypt($encryptedValue);
        }, $student_id_arrays);

        $customers = Customer::whereIn('id', $student_id_array)->get();

        foreach ($customers as $customer) {

            foreach ($customer->tickets()->get() as $tickets) {
                foreach ($tickets->getMedia('ticket') as $media) {
                    $media->delete();
                }
                foreach ($tickets->comments as $comment) {
                    foreach ($comment->getMedia('comments') as $media) {
                        $media->delete();
                    }
                    $comment->delete();
                }
                $tickets->delete();
            }
            $customer->custsetting()->delete();

            $custnotifications = senduserlist::where('tocust_id',$customer->id)->get();
            foreach($custnotifications as $custnotification){
                $custnotifycount = senduserlist::where('mail_id',$custnotification->mail_id)->count();
                if($custnotifycount == 1){
                    $custnotification->sendmaildata->delete();
                }
                $custnotification->delete();
            }
            // $customer->customercustomsetting()->delete();
            $customer->delete();
        }
        return response()->json(['success' => lang('The customer was deleted successfully.', 'alerts')]);
    }

    public function usersetting(Request $request)
    {
        $users = User::find($request->user_id);
        $users->darkmode = $request->dark;
        $users->update();
        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }

    public function emailonoff(Request $request)
    {
        $useting = usersettings::where('users_id', $request->userid)->first();

        if ($useting == null) {
            $usettingcreate = new usersettings();
            $usettingcreate->users_id  = $request->userid;
            $usettingcreate->emailnotifyon = $request->emailvalue;
            $usettingcreate->save();
        } else {
            $useting->emailnotifyon = $request->emailvalue;
            $useting->update();
        }

        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }

    public function emptwofactqr(Request $request)
    {
        $user = User::find($request->cust_id);
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'You are entered an invalid password.', 'message' => 'wrongpassword']);
        } else {
            if ($request->emailcheckstatus == 1) {

                $google2fa = app('pragmarx.google2fa');
                $google2fa_secret = $google2fa->generateSecretKey();
                $email = $user->email;
                $domainname = parse_url(url('/'));
                $request->session()->put('google2faemail', $email);
                $QR_Image = $google2fa->getQRCodeInline(
                    $domainname['host'],
                    config('app.name'),
                    $google2fa_secret
                );
                return response()->json(['success' => lang('Please check your Email', 'alerts'), 'QR_Image' => $QR_Image, 'secret' => $google2fa_secret, 'workprogress' => 'workingmode']);
            } else {

                $user->update(['google2fa_secret' => null,]);

                $user->update(['twofactorauth' => null,]);

                return response()->json(['success' => lang('successfully disabled your two factor authentication.', 'alerts'), 'workprogress' =>
                'notworkingmode']);
            }
        }
    }

    public function empgoogle2faotp(Request $request)
    {

        $otp = $request->otp;

        $user = User::find($request->id);

        $google2fa = new Google2FA();
        $isValidOTP = $google2fa->verifyKey($request->secret_key_value, $otp);
        if ($isValidOTP) {

            $user->update(['twofactorauth' => 'googletwofact', 'google2fa_secret' => encrypt($request->secret_key_value)]);

            $request->session()->put('admingoogleauthid', $user->email);
            return response()->json([[1]]);
        } else {
            return response()->json([[0]]);
        }
    }

    public function empemail2fa(Request $request)
    {

        $user = User::find($request->cust_id);
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'You entered an invalid password.', 'message' => 'wrongpassword']);
        } else {
            if ($request->emailcheckstatus == 1) {
                $user->update(['twofactorauth' => 'emailtwofact',]);
                $request->session()->forget('admintwofactoremail');
                $verifyuser = VerifyOtp::where('cust_id', $user->email);
                if ($verifyuser->exists()) {
                    $verifyuser->delete();
                }
                $request->session()->put('admintwofactoremail', $user->email);
            } else {
                $user->update(['twofactorauth' => null,]);
                return response()->json(['success' => lang('successfully disabled your two factor authentication.', 'alerts'), 'disabled' => true]);
            }
            return response()->json(['success' => lang('Email two factor authentication is enabled.', 'alerts'), 'message' => 'emailtwofact']);
        }
    }

    public function emp2faotpverify(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        $verifyUser = VerifyOtp::where('cust_id',$request->email)->where('otp', $request->otp)->first();

        if(User::where(['id' =>Auth::id(), 'twofactorauth' => 'emailtwofact'])->exists()){
            if (Auth::user() && session()->get('admintwofactoremail') == Auth::user()->email) {
                return redirect()->route('admin.dashboard');
            }
        }
        if ($verifyUser) {
            $verifyUser->delete();
            if(User::where(['id' =>Auth::id(), 'twofactorauth' => 'emailtwofact'])->exists()){
                session()->put('admintwofactoremail',$request->email);
                return redirect()->route('admin.dashboard');
            }

        }else{
            return redirect()->back()->with(['error' => lang('Invalid otp.', 'alerts')]);
        }
    }


    public function google2faadminlogin($email)
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        $now = now();
        $announcement = announcement::whereDate('enddate', '>=', $now->toDateString())->whereDate('startdate', '<=', $now->toDateString())->get();
        $data['announcement'] = $announcement;

        $announcements = Announcement::whereNotNull('announcementday')->get();
        $data['announcements'] = $announcements;

        $holidays = Holiday::whereDate('startdate', '<=', $now->toDateString())->whereDate('enddate', '>=', $now->toDateString())->where('status', '1')->get();
        $data['holidays'] =  $holidays;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;


        if (Auth::user() && session()->get('admingoogleauthid') == Auth::user()->email) {
            return redirect()->route('admin.dashboard');
        }

        if (!session()->has('google2faemail')) {
            session()->put('google2faemail', $email);
        }

        $data['email'] = session()->get('google2faemail');

        return view('google2fa.adminindex')->with($data);
    }

    public function admingoogle2faotpverify(Request $request)
    {
        $otp = $request->one_time_password;

        $user = User::where('email', $request->email)->first();

        $google = decrypt($user->google2fa_secret);

        $google2fa = new Google2FA();
        $isValidOTP = $google2fa->verifyKey($google, $otp);


        if ($isValidOTP) {
            $request->session()->put('admingoogleauthid', $user->email);

            return redirect()->route('admin.dashboard');
        } else {

            return redirect()->back()->with('error', 'Invalid otp.');
        }
    }

    public function emailtwofactorlogin($email)
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $now = now();
        $announcement = announcement::whereDate('enddate', '>=', $now->toDateString())->whereDate('startdate', '<=', $now->toDateString())->get();
        $data['announcement'] = $announcement;

        $announcements = Announcement::whereNotNull('announcementday')->get();
        $data['announcements'] = $announcements;

        $holidays = Holiday::whereDate('startdate', '<=', $now->toDateString())->whereDate('enddate', '>=', $now->toDateString())->where('status', '1')->get();
        $data['holidays'] =  $holidays;

        $data['email'] = $email;

        if (Auth::user() && session()->get('admintwofactoremail') == Auth::user()->email) {
            return redirect()->route('admin.dashboard');
        }

        $verifyotp = VerifyOtp::where('cust_id', $email)->first();

        if (!$verifyotp) {
            $verifyOtp = VerifyOtp::create([
                'cust_id' => $email,
                'otp' => rand(100000, 999999),
                'type' => 'twofactorotp',
            ]);

            $guestticket = [

                'otp' => $verifyOtp->otp,
                'guestemail' => $verifyOtp->cust_id,
                'guestname' => 'adminuser',
            ];
            try {

                dispatch((new MailSend($verifyOtp->cust_id, 'two_factor_authentication_otp_send', $guestticket)));

            } catch (\Exception $e) {
            }
        }

        return view('admin.auth.passwords.admintwofactor')->with($data);
    }

    public function resendotp(Request $request)
    {
        if (Auth::user() && session()->get('admintwofactoremail') == Auth::user()->email) {
            return redirect()->route('admin.dashboard');
        }
        $verifyUser = VerifyOtp::where('cust_id', $request->email)->first();
        if ($verifyUser) {
            $verifyUser->otp = rand(100000, 999999);
            $verifyUser->update();
        }

        $guestticket = [
            'otp' => $verifyUser->otp,
            'guestemail' => $verifyUser->cust_id,
            'guestname' => 'adminuser',
        ];
        try {

            dispatch((new MailSend($verifyUser->cust_id, 'two_factor_authentication_otp_send', $guestticket)));

        } catch (\Exception $e) {

            // return response()->json(['success' => lang('Please check your Email', 'alerts'), 'email' => 'exists']);
        }
        return redirect()->route('admin.emailtwofactorlogin', ['email' => $verifyUser->cust_id]);
    }

    public function sessionLogout(Request $request){
       $user = Auth::user();
       if($request->stayin){
            $user->last_activity = now();
            $user->save();
            return response(1);
       }else{
           $user->last_activity = null;
           $user->save();
           Auth::logout();
           return response(1);
       }
    }

    public function domaintransfer()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.appinfo.domaintransfer')->with($data);
    }

}
