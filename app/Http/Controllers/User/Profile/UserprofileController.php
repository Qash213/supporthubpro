<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\Controller;
use App\Jobs\MailSend;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerSetting;
use App\Models\Countries;
use App\Models\Timezone;
use Auth;
use Hash;
use File;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use Illuminate\Support\Facades\Validator;
use Image;
use App\Models\TicketCustomfield;
use App\Models\VerifyOtp;
use App\Mail\mailmailablesend;
use Mail;
use App\Models\Announcement;
use PragmaRX\Google2FA\Google2FA;
use App\Models\Holiday;
use Twilio\Rest\Client;

class UserprofileController extends Controller
{
    public function sendOtp(Request $request)
    {
        if (setting('cust_mobile_update') == 'on') {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|numeric|not_in:null',
            ]);
            if ($validator->passes()) {
                $userExists = Customer::where('phone', "=", $request->phone)->exists();
                if ($userExists) {
                    return response()->json(['mainError' => 'Mobile number already in use.']);
                }

                $otpexist = VerifyOtp::where('cust_id', Auth::guard('customer')->user()->email)->get();
                if ($otpexist) {
                    foreach ($otpexist as $otpexists) {
                        $otpexists->delete();
                    }
                }

                $otpcreate = VerifyOtp::create([
                    'cust_id' => Auth::guard('customer')->user()->email,
                    'otp' => rand(100000, 999999),
                    'type' => 'phoneUpdate',
                ]);

                try {
                    $account_sid = setting('twilio_auth_id');
                    $auth_token = setting('twilio_auth_token');
                    $twilio_number = setting('twilio_auth_phone_number');

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($request->phone, [
                        'from' => $twilio_number,
                        'body' => "Your One Time Password is : $otpcreate->otp. Please do not share this with anyone."
                    ]);
                    return response()->json(['success' => lang('OTP sent successfully')]);
                } catch (\Twilio\Exceptions\RestException $e) {
                    return response()->json(['error' => $e->getMessage()]);
                }
            } else {
                return response()->json(['errors' => $validator->errors()]);
            }
        } else {
            return response()->json(['error' => 'reload']);
        }
    }

    public function verifyOTP(Request $request)
    {
        $verify = VerifyOtp::where('type', 'phoneUpdate')->where('otp', $request->otp)->first();
        if ($verify) {
            $customerfind = Customer::where('email', $verify->cust_id)->first();
            $customerfind->phone = $request->phone;
            $customerfind->phoneVerified = 1;
            $customerfind->save();

            $verify->delete();

            return response()->json(['success' => lang('Mobile number added successfully.')]);
        } else {
            return response()->json(['error' => lang('Invalid OTP')]);
        }
    }

    public function custtwiliosetting(Request $request)
    {
        $users = Customer::with('custsetting')->find($request->cust_id);

        $users->phonesmsenable = $request->custsmsenabledata;
        $users->update();

        return response()->json(['code' => 200, 'success' => lang('Updated Successfully', 'alerts')], 200);
    }

    public function customeremailchange(Request $request)
    {
        $customer = Customer::where('email', $request->email)->first();
        if(Hash::check($request->password, $customer->password)){
            $emaildata = [
                'username' => $customer->username,
                'useremail' => $customer->email,
                'ticket_customer_url' => route('customeremailupdate',$customer->email),
            ];

            try {

                dispatch((new MailSend($customer->email, 'Send_email_to_customer_when_change_email', $emaildata)));

            } catch (\Exception$e) {

            }
            return response()->json(['success' => lang('Please check your email to change email id, we send a mail to your email.', 'alerts'), 'message' => 'linksend']);
        }else{
            return response()->json(['error' => lang('You are entered invalid password.', 'alerts'), 'message' => 'wrongpassword']);
        }
    }

    public function customeremailupdate(Request $request, $id)
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

        return view('user.profile.updateemail')->with($data);
    }

    public function customernewemailstore(Request $request)
    {

        $customer = Customer::where('email', $request->oldemail)->first();
        if($customer->email == $request->email){
            return response()->json(['error' => lang('This email is already linked to your account.', 'alerts'), 'email' => 'already']);
        }

        $request->validate([
            'email' => 'required|email|max:255|indisposable|unique:customers',
        ]);

        $otpdata = VerifyOtp::where('type', 'emailupdate')->where('cust_id', $request->email)->first();

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
                'username' => $customer->username,
            ];

            try {

                dispatch((new MailSend($otpdata->cust_id, 'Send_email_to_customer_when_change_email_otp_verification', $custemailchange)));

            } catch (\Exception$e) {

                // return response()->json(['success' => lang('Please check your email to verify otp.', 'alerts'), 'otp' => 'exists']);
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
                'username' => $customer->username,
            ];

            try {

                dispatch((new MailSend($verifyOtp->cust_id, 'Send_email_to_customer_when_change_email_otp_verification', $custemailchange)));

            } catch (\Exception$e) {

                // return response()->json(['success' => lang('Please check your email to verify otp.', 'alerts'), 'otp' => 'newotp']);
            }

            return response()->json(['success' => lang('Please check your email to verify otp.', 'alerts'), 'otp' => 'exists']);
        }
    }

    public function emailchangeotpverify(Request $request, $oldemail)
    {
        if ($request->session()->has('emailupdate')) {
            $emailvalidate = $request->session()->get('emailupdate');
        }
        $verify = VerifyOtp::where('type', 'emailupdate')->where('otp', $request->otp)->first();
        if ($verify) {
            if ($emailvalidate == $verify->cust_id) {
                $customerfind = Customer::where('email', $oldemail)->first();
                $customerfind->email = $verify->cust_id;
                $customerfind->save();

                request()->session()->forget('customerticket');
                Auth::guard('customer')->logout();
                return redirect()->route('auth.login')->with('success', lang('Your email id updated successfully.', 'alerts'));
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


    public function profile()
    {


        $user = Customer::get();
        $data['users'] = $user;

        $customfield = TicketCustomfield::where('cust_id', Auth::user()->id)->get();
        $data['customfield'] = $customfield;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::Orderby('group')->get();
        $data['timezones'] = $timezones;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('user.profile.userprofile')->with($data);
    }

    public function profilesetup(Request $request)
    {

        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
        ]);

        if ($request->phone) {
            $this->validate($request, [
                'phone' => 'numeric|min:10',
            ]);
        }

        $user_id = Auth::guard('customer')->user()->id;

        $user = Customer::findOrFail($user_id);

        $user->country = $request->input('country');
        $user->timezone = $request->input('timezone');
        $user->phoneVerified = $user->phone != $request->input('phone') ? 0 : 1;
        $user->phone = $request->input('phone');

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
        return redirect()->back()->with('success', lang('Your profile has been successfully updated.', 'alerts'));
    }

    public function imageremove(Request $request, $id)
    {
        $user = Customer::findOrFail($id);
        $existprovider = existprovider($user->storage_disk);
        if($existprovider)
           $existprovider->provider::delete('/uploads/profile' . "/" . $user->image);
        $user->image = null;
        $user->update();

        return response()->json(['success' => lang('The profile image was successfully removed.', 'alerts')]);
    }


    public function profiledelete($id)
    {

        $user = Customer::findOrFail($id);

        Auth::guard('customer')->logout();

        $ticket = $user->tickets()->get();

        foreach ($ticket as $tickets) {
            foreach ($tickets->getMedia('ticket') as $media) {
                $media->delete();
            }
            foreach ($tickets->comments()->get() as $comment) {

                foreach ($comment->getMedia('comments') as $media) {
                    $media->delete();
                }

                $comment->delete();
            }

            $tickets->delete();
        }
        $user->custsetting()->delete();

        $user->delete();
        return response()->json(['success' => lang('Your account has been deleted!', 'alerts')]);
    }

    public function custsetting(Request $request)
    {
        $users = Customer::with('custsetting')->find($request->cust_id);

        if ($users->custsetting != null) {

            $users->custsetting->darkmode = $request->dark;
            $users->custsetting->update();
        } else {
            $custsettings = CustomerSetting::create([
                'custs_id' => $request->cust_id,
                'darkmode' => $request->dark

            ]);
        }

        return response()->json(['code' => 200, 'success' => lang('Updated Successfully', 'alerts')], 200);
    }

    public function google2faotpenter()
    {


        $user = Customer::get();
        $data['users'] = $user;

        $customfield = TicketCustomfield::where('cust_id', Auth::user()->id)->get();
        $data['customfield'] = $customfield;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::Orderby('group')->get();
        $data['timezones'] = $timezones;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $data['email'] = session()->get('google2faemail');


        return view('google2fa.index')->with($data);
    }

    public function google2faotpverify(Request $request)
    {

        $otp = $request->otp;
        $user = Customer::find($request->id);

        $google2fa = new Google2FA();
        $isValidOTP = $google2fa->verifyKey($request->secret_key_value, $otp);

        $custsetexists = CustomerSetting::where('custs_id', '=', $request->id)->first();
        if ($isValidOTP) {
            if ($custsetexists) {
                $custsetexists->twofactorauth = 'googletwofact';
                $custsetexists->update();
            } else {
                $customersetting = new CustomerSetting();
                $customersetting->custs_id = $user->id;
                $customersetting->twofactorauth = 'googletwofact';
                $customersetting->save();
            }
            $user->update(['google2fa_secret' => encrypt($request->secret_key_value)]);

            $request->session()->put('googleauthid', $user->email);
            return response()->json([[1]]);
        } else {
            return response()->json([[0]]);
        }
    }

    public function emailtwofactor(Request $request)
    {
        $user = Customer::find($request->cust_id);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'You are entered an invalid password.', 'message' => 'wrongpassword']);
        } else {
            $custsetexists = CustomerSetting::where('custs_id', '=', $request->cust_id)->first();

            if ($request->emailcheckstatus == 1) {
                $verifyuser = VerifyOtp::where('cust_id', $user->email);
                if ($verifyuser->exists()) {
                    $verifyuser->delete();
                }

                $request->session()->forget('twofactoremail');
                if ($custsetexists) {
                    $custsetexists->twofactorauth = 'emailtwofact';
                    $custsetexists->update();
                } else {

                    $customersetting = new CustomerSetting();
                    $customersetting->custs_id = $user->id;
                    $customersetting->twofactorauth = 'emailtwofact';
                    $customersetting->save();
                }

                $request->session()->put('twofactoremail', $user->email);


                return response()->json(['success' => lang('successfully Enabled twofactor authentication.', 'alerts')]);
            } else {
                $custsetexists->twofactorauth = null;
                $custsetexists->update();

                return response()->json(['success' => lang('successfully disabled twofactor authentication.', 'alerts'), "disabled" => true]);
            }
        }
    }
}
