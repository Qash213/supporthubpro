<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Setting;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\EmailTemplate;
use App\Models\SocialAuthSetting;
use App\Http\Requests\SocialAuthRequest;
use App\Jobs\MailSend;
use Mail;
use App\Mail\mailmailablesend;
use App\Models\Customer;
use App\Models\Imap_setting;
use App\Models\User;
use App\Notifications\InformationSendToUsers;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Auth;
use Swift_Mailer;
use Swift_SmtpTransport;
use \Webklex\IMAP\Facades\Client;


use Illuminate\Support\Facades\Validator;
use File;
use Image;

use App\Models\MessageTemplates;

class AdminSettingController extends Controller
{

    /**
     * Social Login Settings.
     *
     * @return \Illuminate\Http\Response
     */

    public function sociallogin()
    {
        $this->authorize('Social Logins Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $credentials = SocialAuthSetting::first();
        $data['credentials'] = $credentials;

        return view('admin.generalsetting.socialloginsetting')->with($data);
    }

    /**
     * Social Login Settings.
     *
     * @return \Illuminate\Http\Response
     */

    public function socialloginupdate(SocialAuthRequest $request)
    {

        $socialAuth = SocialAuthSetting::first();

        $socialAuth->google_client_id = $request->google_client_id;
        $socialAuth->google_secret_id = $request->google_secret_id;
        ($request->google_status)  ? $socialAuth->google_status = 'enable' : $socialAuth->google_status = 'disable';

        $socialAuth->envato_client_id = $request->envato_client_id;
        $socialAuth->envato_secret_id = $request->envato_secret_id;
        ($request->envato_status) ? $socialAuth->envato_status = 'enable' : $socialAuth->envato_status = 'disable';

        $socialAuth->microsoft_app_id = $request->microsoft_app_id;
        $socialAuth->microsoft_secret_id = $request->microsoft_secret_id;
        ($request->microsoft_status) ? $socialAuth->microsoft_status = 'enable' : $socialAuth->microsoft_status = 'disable';

        $socialAuth->save();

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function twiliosetting()
    {
        $this->authorize('Twilio Setting Access');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $smstemplates = MessageTemplates::get();
        $data['smstemplates'] = $smstemplates;

        return view('admin.generalsetting.twiliosetting')->with($data);
    }

    public function twiliosettingstore(Request $request)
    {
        $this->authorize('Twilio Setting Access');

        if($request->has('twilioenable')){
            $this->validate($request, [
                'twilio_auth_id' => 'required',
                'twilio_auth_token' => 'required',
                'twilio_auth_phone_number' => 'required',
            ]);

            try {
                $client = new \Twilio\Rest\Client($request->twilio_auth_id, $request->twilio_auth_token);
                $client->api->v2010->accounts->read();
            } catch (\Twilio\Exceptions\ConfigurationException $e) {
                return back()->with('error', lang('Twilio credentials are invalid', 'alerts'));
            } catch (\Twilio\Exceptions\TwilioException $e) {
                return back()->with('error', lang('Twilio credentials are invalid', 'alerts'));
            } catch (\Exception $e) {
                return back()->with('error', lang('Twilio credentials are invalid', 'alerts'));
            }
        }

        $data['twilioenable'] = $request->has('twilioenable') ? 'on' : 'off';;
        $data['twilio_auth_id'] = $request->twilio_auth_id;
        $data['twilio_auth_token'] = $request->twilio_auth_token;
        $data['twilio_auth_phone_number'] = $request->twilio_auth_phone_number;

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function smstemplateedit($id)
    {
        $this->authorize('Twilio Setting Access');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $template = MessageTemplates::find($id);
        $data['template'] = $template;

        return view('admin.generalsetting.messagetemplateedit')->with($data);
    }

    public function smstemplateUpdate(Request $request, $id)
    {
        $this->authorize('Twilio Setting Access');

        $request->validate([
            'body' => 'required'
        ]);
        if (strip_tags($request->body) == "") {
            return back()->with('bodyNull', lang('Message Body field is required.', 'alerts'));
        }

        $template = MessageTemplates::find($id)->update($request->only(['body']));

        return redirect()->route('admin.twiliosetting')->with('success', lang('Updated successfully', 'alerts'));
    }

    /**
     * Captcha Settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function captcha()
    {
        $this->authorize('Captcha Setting Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.generalsetting.captchasetting')->with($data);
    }

    /**
     * Captcha Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    public function captchastore(Request $request)
    {

        $this->validate($request, [
            'googlerecaptchakey' => 'required|max:10000',
            'googlerecaptchasecret' => 'required|max:10000',
        ]);
        $data['GOOGLE_RECAPTCHA_KEY'] = $request->googlerecaptchakey;
        $data['GOOGLE_RECAPTCHA_SECRET'] = $request->googlerecaptchasecret;

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function captchatypestore(Request $request)
    {

        $data['captchatype'] = $request->captchatype;
        $this->updateSettings($data);
        return response()->json(['success' => lang('Updated successfully', 'alerts')]);
    }
    /**
     * Email Settings.
     *
     * @return \Illuminate\Http\Response
     */

    public function email()
    {

        $this->authorize('Email Setting Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $imaps = Imap_setting::all();
        $imap_count = $imaps->count();


        $data['imaps'] = $imaps;
        $data['imap_count'] = $imap_count;

        return view('admin.email.email')->with($data);
    }
    /**
     * Ticket Settings.
     *
     * @return \Illuminate\Http\Response
     */

    public function ticketsetting()
    {
        $this->authorize('Ticket Setting Access');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.generalsetting.ticketsetting')->with($data);
    }

    /**
     * Ticket Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketsettingstore(Request $request)
    {
        $request->validate([
            'ticketid' => 'required',

        ]);
        if ($request->USER_REOPEN_ISSUE) {
            $request->validate([
                'userreopentime' => 'required|numeric|gte:0'
            ]);
        }
        if ($request->AUTO_CLOSE_TICKET) {
            $request->validate([
                'autoclosetickettime' => 'required|numeric|gte:0'
            ]);
        }
        if ($request->AUTO_OVERDUE_TICKET) {
            $request->validate([
                'autooverduetickettime' => 'required|numeric|gte:0'
            ]);
        }
        if ($request->AUTO_RESPONSETIME_TICKET) {
            $request->validate([
                'autoresponsetickettime' => 'required|numeric|gte:0'
            ]);
        }
        if ($request->AUTO_NOTIFICATION_DELETE_ENABLE) {
            $request->validate([
                'autonotificationdeletedays' => 'required|numeric|gte:0'
            ]);
        }

        if ($request->ticketcharacter) {
            $request->validate([
                'ticketcharacter' => 'required|integer|between:10,255'
            ]);
        }
        if ($request->customer_panel_employee_protect) {
            $request->validate([
                'employeeprotectname' => 'required|max:255'
            ]);
        }
        if ($request->RESTRICT_TO_CREATE_TICKET) {
            $request->validate([
                'MAXIMUM_ALLOW_TICKETS' => 'required|numeric|gt:0',
                'MAXIMUM_ALLOW_HOURS' => 'required|numeric|gt:0',
            ]);
        }
        if ($request->RESTRICT_TO_REPLY_TICKET) {
            $request->validate([
                'MAXIMUM_ALLOW_REPLIES' => 'required|numeric|gt:0',
                'REPLY_ALLOW_IN_HOURS' => 'required|numeric|gt:0',
            ]);
        }
        if ($request->CUSTOMER_RESTRICT_EDIT_REPLY) {
            $request->validate([
                'custreplyeditwithintime' => 'required|numeric|gt:0',
            ]);
        }
        if ($request->trashed_ticket_autodelete) {
            $request->validate([
                'trashed_ticket_delete_time' => 'required|numeric|gt:0',
            ]);
        }
        if ($request->DASHBOARD_TABLE_DATA_AUTO_REFRESH) {
            $request->validate([
                'TABLE_DATA_AUTO_REFRESH_TIME' => 'required|numeric|gt:0',
            ]);
        }

        // $request->validate([
        //     'ticketid' => 'required',
        //     'userreopentime' => 'required|numeric|gt:0',
        //     'autoclosetickettime' => 'required|numeric|gt:0',
        //     'autooverduetickettime' => 'required|numeric|gt:0',
        //     'autoresponsetickettime' => 'required|numeric|gt:0',
        //     'autonotificationdeletedays' => 'required|numeric|gt:0',
        //     'ticketcharacter' => 'required|integer|between:10,255',
        //     'employeeprotectname' => 'required|max:255',
        //     'MAXIMUM_ALLOW_TICKETS' => 'required|numeric|gt:0',
        //     'MAXIMUM_ALLOW_HOURS' => 'required|numeric|gt:0',
        //     'MAXIMUM_ALLOW_REPLIES' => 'required|numeric|gt:0',
        //     'REPLY_ALLOW_IN_HOURS' => 'required|numeric|gt:0',
        //     'custreplyeditwithintime' => 'required|numeric|gt:0',
        //     'trashed_ticket_delete_time' => 'required|numeric|gt:0',
        //     'TABLE_DATA_AUTO_REFRESH_TIME' => 'required|numeric|gt:0',
        // ]);

        $data['RESTRICT_TO_CREATE_TICKET']  =  $request->has('RESTRICT_TO_CREATE_TICKET') ? 'on' : 'off';
        $data['MAXIMUM_ALLOW_TICKETS']  =  $request->input('MAXIMUM_ALLOW_TICKETS');
        $data['MAXIMUM_ALLOW_HOURS']  =  $request->input('MAXIMUM_ALLOW_HOURS');
        $data['RESTRICT_TO_REPLY_TICKET']  =  $request->has('RESTRICT_TO_REPLY_TICKET') ? 'on' : 'off';
        $data['MAXIMUM_ALLOW_REPLIES']  =  $request->input('MAXIMUM_ALLOW_REPLIES');
        $data['REPLY_ALLOW_IN_HOURS']  =  $request->input('REPLY_ALLOW_IN_HOURS');
        $data['USER_REOPEN_ISSUE']  =  $request->has('USER_REOPEN_ISSUE') ? 'yes' : 'no';
        $data['USER_REOPEN_TIME']  =  $request->input('userreopentime');
        $data['cust_or_tick_violation']  =  $request->has('cust_or_tick_violation') ? 'yes' : 'no';
        $data['AUTO_CLOSE_TICKET']  =  $request->has('AUTO_CLOSE_TICKET') ? 'yes' : 'no';
        $data['AUTO_CLOSE_TICKET_TIME']  =  $request->input('autoclosetickettime');
        $data['AUTO_OVERDUE_TICKET']  =  $request->has('AUTO_OVERDUE_TICKET') ? 'yes' : 'no';
        $data['AUTO_OVERDUE_CUSTOMER']  =  $request->has('AUTO_OVERDUE_CUSTOMER') ? 'yes' : 'no';
        $data['AUTO_OVERDUE_TICKET_TIME']  =  $request->input('autooverduetickettime');
        $data['trashed_ticket_autodelete']  =  $request->has('trashed_ticket_autodelete') ? 'on' : 'off';
        $data['trashed_ticket_delete_time']  =  $request->input('trashed_ticket_delete_time');
        $data['DASHBOARD_TABLE_DATA_AUTO_REFRESH']  =  $request->has('DASHBOARD_TABLE_DATA_AUTO_REFRESH') ? 'yes' : 'no';
        $data['TABLE_DATA_AUTO_REFRESH_TIME']  =  $request->input('TABLE_DATA_AUTO_REFRESH_TIME');
        $data['AUTO_RESPONSETIME_TICKET']  =  $request->has('AUTO_RESPONSETIME_TICKET') ? 'yes' : 'no';
        $data['AUTO_RESPONSETIME_TICKET_TIME']  =  $request->input('autoresponsetickettime');
        $data['AUTO_NOTIFICATION_DELETE_ENABLE']  =  $request->has('AUTO_NOTIFICATION_DELETE_ENABLE') ? 'on' : 'off';
        $data['AUTO_NOTIFICATION_DELETE_DAYS']  =  $request->input('autonotificationdeletedays');
        $data['CUSTOMER_TICKETID']  =  $request->input('ticketid');
        $data['GUEST_TICKET']  =  $request->has('GUEST_TICKET') ? 'yes' : 'no';
        $data['NOTE_CREATE_MAILS']  =  $request->has('NOTE_CREATE_MAILS') ? 'on' : 'off';
        $data['PRIORITY_ENABLE']  =  $request->has('PRIORITY_ENABLE') ? 'yes' : 'no';
        $data['USER_FILE_UPLOAD_ENABLE']  =  $request->has('USER_FILE_UPLOAD_ENABLE') ? 'yes' : 'no';
        $data['GUEST_FILE_UPLOAD_ENABLE']  =  $request->has('GUEST_FILE_UPLOAD_ENABLE') ? 'yes' : 'no';
        $data['GUEST_TICKET_OTP']  =  $request->has('GUEST_TICKET_OTP') ? 'yes' : 'no';
        $data['CUSTOMER_TICKET']  =  $request->has('CUSTOMER_TICKET') ? 'yes' : 'no';
        $data['TICKET_CHARACTER']  =  $request->input('ticketcharacter');
        $data['customer_panel_employee_protect']  =  $request->has('customer_panel_employee_protect') ? 'on' : 'off';
        $data['employeeprotectname']  =  $request->input('employeeprotectname');
        $data['CUSTOMER_RESTRICT_EDIT_REPLY']  =  $request->has('CUSTOMER_RESTRICT_EDIT_REPLY') ? 'yes' : 'no';
        $data['custreplyeditwithintime']  =  $request->input('custreplyeditwithintime');
        $data['admin_reply_mail']  =  $request->has('admin_reply_mail') ? 'yes' : 'no';
        $data['ticketrating']  =  $request->has('ticket_rating') ? 'on' : 'off';
        $data['cc_email']  =  $request->has('cc_email') ? 'on' : 'off';

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }
    /**
     * Email Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */

    public function emailStore(Request $request)
    {
        if ($request->ajax()) {
            if ($request->mail_driver == 'sendmail') {
                $request->validate([
                    'mail_from_name' => 'required|max:10000',
                    'mail_from_address' => 'required|max:10000'
                ]);
            }
            if ($request->mail_driver == 'smtp') {
                $request->validate([
                    'mail_host' => 'required|max:10000',
                    'mail_port' => 'required|numeric',
                    'mail_encryption' => 'required|max:10000',
                    'mail_username' => 'required|max:10000',
                    'mail_password' => 'required|max:10000',
                    'mail_from_name' => 'required|max:10000',
                    'mail_from_address' => 'required|max:10000'
                ]);

                //validating smtp connection
                $transport = new Swift_SmtpTransport($request->mail_host, $request->mail_port);
                $transport->setUsername($request->mail_username);
                $transport->setPassword($request->mail_password);
                $transport->setEncryption($request->mail_encryption);

                try {
                    $mailer = new Swift_Mailer($transport);
                    $mailer->getTransport()->start();
                } catch (\Swift_TransportException $e) {
                    return response()->json(['code' => 500, 'imapconnectionError' => 'notconnected', 'error' => lang('Your smtp credentials are invalid, please verify your entered details.', 'alerts')], 500);
                }
            }


            $data = $request->only(['mail_driver', 'mail_host', 'mail_port', 'mail_from_address', 'mail_from_name', 'mail_encryption', 'mail_username', 'mail_password']);

            $this->updateSettings($data);
            return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
        }
    }



    /**
     * Email Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendTestMail(Request $request)
    {

        $email = $request->get('email');
        // mailsetup();

        try {


            // Mail::mailer('sales')->to($salesUser)->send(new SalesMail());
            Mail::send('admin.email.template', ['emailBody' => "This is a test email sent by system"], function ($message) use ($email) {
                $message->to($email)->subject('Test Email');
            });

            return back()->with('success', lang('A test email was sent successfully.', 'alerts'));
        } catch (\Exception $e) {

            return back()->with('error',  lang('The test email couldn’t be sent.', 'alerts'));
        }
    }


    /**
     * Email Settings.
     *
     * @return \Illuminate\Http\Response
     */

    public function emailtemplates()
    {
        $this->authorize('Email Template Access');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $emailtemplates = EmailTemplate::all();
        $data['emailtemplates'] = $emailtemplates;

        return view('admin.email.index')->with($data);
    }

    /**
     * Email Settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailtemplatesEdit($id)
    {
        $this->authorize('Email Template Edit');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $template = EmailTemplate::find($id);
        $data['template'] = $template;

        return view('admin.email.edit')->with($data);
    }

    public function emailtemplatesUpdate(Request $request, $id)
    {
        $this->authorize('Email Template Edit');

        $request->validate([
            'subject' => 'required|max:255',
            'body' => 'required'
        ]);
        if (strip_tags($request->body) == "") {
            return back()->with('bodyNull', lang('Email Body field is required.', 'alerts'));
        }

        $template = EmailTemplate::find($id)->update($request->only(['subject', 'body']));

        return redirect('/admin/emailtemplates')->with('success', lang('Updated successfully', 'alerts'));
    }

    public function announcementsetting(Request $request)
    {
        $data['ANNOUNCEMENT_USER']  =  $request->ANNOUNCEMENT_USER;

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function enableemailtoticket(Request $request)
    {
        if ($request->has('IMAP_EMAIL_PROCESS_LIMIT_SWITCH')) {
            $request->validate([
                'IMAP_EMAIL_TEMPLATE_LIMIT' => 'required|numeric|gt:0',
            ]);
        }

        $data['IMAP_STATUS']  =  $request->has('IMAP_STATUS') ? 'on' : 'off';
        $data['IMAP_EMAIL_AUTO_DELETE']  =  $request->has('IMAP_EMAIL_AUTO_DELETE') ? 'on' : 'off';
        $data['IMAP_EMAIL_PROCESS_LIMIT_SWITCH']  =  $request->has('IMAP_EMAIL_PROCESS_LIMIT_SWITCH') ? 'on' : 'off';
        $data['IMAP_EMAIL_TEMPLATE_LIMIT']  =  $request->IMAP_EMAIL_TEMPLATE_LIMIT;

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function registerpopup(Request $request)
    {


        $socialAuth = SocialAuthSetting::first();

        $data['only_social_logins'] = $request->defaultsocialloginon;
        if ($request->defaultsocialloginon == 'on') {
            if ($socialAuth->twitter_status == 'enable' || $socialAuth->facebook_status == 'enable' || $socialAuth->google_status == 'enable' || $socialAuth->envato_status == 'enable') {
                $data['REGISTER_DISABLE'] = 'off';
                $data['REGISTER_POPUP'] = 'no';
            } else {
                return response()->json(['code' => 500, 'error' => lang('Social logins are not enabled please enable it first', 'alerts')], 500);
            }
        }

        if($request->custmobileupdate == 'on' && setting('twilioenable') != 'on'){
            return response()->json(['code' => 500, 'twilioerror' => lang('If you want enable switch for cusotmer mobile number update, first you need to setup twilio settings.', 'alerts')], 500);
        }

        if($request->status == 'yes' && $request->defaultloginon == 'on'){
            return response()->json(['code' => 500, 'twilioerror' => lang('If default login is already enabled, at this moment you are not allowed to enable this switch.', 'alerts')], 500);
        }

        if($request->defaultloginon == 'on' && $request->status == 'yes'){
            return response()->json(['code' => 500, 'twilioerror' => lang('If Register or login popup is already enabled, at this moment you are not allowed to enable this switch.', 'alerts')], 500);
        }

        $data['REGISTER_POPUP'] = $request->status;
        $data['REGISTER_DISABLE'] = $request->registerdisable;
        $data['GOOGLEFONT_DISABLE'] = $request->googledisable;
        $data['FORCE_SSL'] = $request->forcessl;
        $data['DARK_MODE'] = $request->darkmode;
        $data['SPRUKOADMIN_P'] = $request->sprukoadminp;
        $data['SPRUKOADMIN_C'] = $request->sprukocustp;
        $data['ENVATO_ON'] = $request->envatoon;
        $data['purchasecode_on'] = $request->purchasecodeon;
        $data['defaultlogin_on'] = $request->defaultloginon;
        $data['article_count'] = $request->articlecount;
        $data['sidemenu_icon_style'] = $request->sidemenustyle;
        $data['login_disable'] = $request->logindisable;
        $data['cust_profile_delete_enable'] = $request->custdeleteprofile;
        $data['cust_email_update'] = $request->custemailupdate;
        $data['cust_mobile_update'] = $request->custmobileupdate;
        $data['MAINTENANCE_MODE'] = $request->maintanancemode;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }


    public function filesettingstore(Request $request)
    {
        $request->validate([
            'usermaxfileupload' => 'required|numeric|gt:0',
            'userfileuploadmaxsize' => 'required|numeric|gt:0',
            'userfileuploadtypes' => 'required',
            'maxfileupload' => 'required|numeric|gt:0',
            'fileuploadmax' => 'required|numeric|gt:0',
            'fileuploadtypes' => 'required',
        ]);

        $userfileuploadtypes = explode(',',$request->userfileuploadtypes);
        $fileuploadtypes = explode(',',$request->fileuploadtypes);
        $allowedFormats = ['.xlsx', '.csv', '.docx', '.pdf', '.jpg', '.jpeg', '.png', '.mp3', '.wav', '.mp4', '.zip', '.webp'];
        foreach($userfileuploadtypes as $userfileuploadtype){
            if(!in_array($userfileuploadtype, $allowedFormats)){
                return back()->with('error', lang('You are enter wrong file formats please enter correct format.', 'alerts'));
            }
        }
        foreach($fileuploadtypes as $fileuploadtype){
            if(!in_array($fileuploadtype, $allowedFormats)){
                return back()->with('error', lang('You are enter wrong file formats please enter correct format.', 'alerts'));
            }
        }

        $data['USER_MAX_FILE_UPLOAD']  =  $request->input('usermaxfileupload');
        $data['USER_FILE_UPLOAD_MAX_SIZE']  =  $request->input('userfileuploadmaxsize');
        $data['USER_FILE_UPLOAD_TYPES']  =  $request->input('userfileuploadtypes');
        $data['MAX_FILE_UPLOAD']  =  $request->input('maxfileupload');
        $data['FILE_UPLOAD_MAX']  =  $request->input('fileuploadmax');
        $data['FILE_UPLOAD_TYPES']  =  $request->input('fileuploadtypes');

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function botresponsettingcreate(Request $request)
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.generalsetting.botresponsesetting')->with($data);
    }

    public function botsettingstore(Request $request)
    {
        if($request->has('botresponseenable')){
            $request->validate([
                'bot_name' => 'required',
                'botsresponse_time' => 'required|numeric|gt:0',
                'response_description' => 'required',
                'response_description_exclude_business_hours' => 'required',
            ]);
        }

        $data['botresponseenable']  =  $request->has('botresponseenable') ? 'on' : 'off';
        $data['bot_name']  =  $request->input('bot_name');
        $data['botsresponse_time']  =  $request->input('botsresponse_time');
        $data['time_detection']  =  $request->input('time_detection');
        $data['response_description']  =  $request->input('response_description');
        $data['response_description_exclude_business_hours']  =  $request->input('response_description_exclude_business_hours');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileArray = array('image' => $file);
            $rules = array(
                'image' => 'mimes:jpeg,jpg,png|required|max:5120'
              );

              $validator = Validator::make($fileArray, $rules);

              if ($validator->fails())
                {
                    return redirect()->back()->with('error', lang('Please check the format and size of the file.', 'alerts'));
                }else
                {

                    $destination = public_path() . "" . '/uploads/profile/botprofile';
                    $image_name = time() . '.' . $file->getClientOriginalExtension();
                    $resize_image = Image::make($file->getRealPath());

                    $resize_image->resize(80, 80, function($constraint){
                    $constraint->aspectRatio();
                    })->save($destination . '/' . $image_name);

                    $destinations = public_path() . "" . '/uploads/profile/botprofile'.setting('bot_image');
                    if(File::exists($destinations)){
                        File::delete($destinations);
                    }
                    $file = $request->file('image');
                    $data['bot_image']  =  $image_name;
                }


        }

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function botimagedelete(Request $request)
    {
        $destinations = public_path() . "" . '/uploads/profile/botprofile/'.setting('bot_image');

        if(File::exists($destinations)){
            File::delete($destinations);

            $data['bot_image']  =  null;

            $this->updateSettings($data);


            return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
        }else{
            return response()->json(['code' => 500, 'error' => lang('The image path is not exists.', 'alerts')], 500);
        }

    }

    public function knowledge(Request $request)
    {

        $data['KNOWLEDGE_ENABLE']  =  $request->KNOWLEDGE_ENABLE;
        $data['FAQ_ENABLE']  =  $request->FAQ_ENABLE;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }

    public function profileuser(Request $request)
    {

        $data['PROFILE_USER_ENABLE']  =  $request->PROFILE_USER_ENABLE;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }
    public function profileagent(Request $request)
    {

        $data['PROFILE_AGENT_ENABLE']  =  $request->PROFILE_AGENT_ENABLE;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }

    public function captchacontact(Request $request)
    {

        $data['RECAPTCH_ENABLE_CONTACT']  =  $request->RECAPTCH_ENABLE_CONTACT;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }

    public function captcharegister(Request $request)
    {

        $data['RECAPTCH_ENABLE_REGISTER']  =  $request->RECAPTCH_ENABLE_REGISTER;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }
    public function captchalogin(Request $request)
    {

        $data['RECAPTCH_ENABLE_LOGIN']  =  $request->RECAPTCH_ENABLE_LOGIN;;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }
    public function captchaadminlogin(Request $request)
    {

        $data['RECAPTCH_ENABLE_ADMIN_LOGIN']  =  $request->RECAPTCH_ENABLE_ADMIN_LOGIN;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }

    public function captchaguest(Request $request)
    {

        $data['RECAPTCH_ENABLE_GUEST']  =  $request->RECAPTCH_ENABLE_GUEST;

        $this->updateSettings($data);


        return response()->json(['code' => 200, 'success' => lang('Updated successfully', 'alerts')], 200);
    }


    /**
     * Frontend Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    public function frontendStore(Request $request)
    {
        $request->validate([
            'theme_color' => 'required',
            'theme_color_dark' => 'required',
        ]);

        $data = $request->only(['theme_color', 'theme_color_dark']);

        $this->updateSettings($data);

        return back()->with('success',  lang('Updated successfully', 'alerts'));
    }


    public function googleanalytics()
    {
        $this->authorize('Google Analytics Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.generalsetting.googleanalytics')->with($data);
    }

    /**
     * Googleanalytics Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleanalyticsStore(Request $request)
    {

        $request->validate([
            'GOOGLE_ANALYTICS' => 'required',
        ]);
        $data['GOOGLE_ANALYTICS_ENABLE']  =  $request->has('GOOGLE_ANALYTICS_ENABLE') ? 'yes' : 'no';
        $data['GOOGLE_ANALYTICS'] = $request->input(['GOOGLE_ANALYTICS']);

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function languagesettingstore(Request $request)
    {

        $data = $request->only(['default_lang']);

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function seturl(Request $request)
    {

        $request->validate([
            'terms_url' => 'required',
        ]);

        $data = $request->only(['terms_url']);

        $this->updateSettings($data);

        return back()->with('success',  lang('Updated successfully', 'alerts'));
    }

    public function envatosetting()
    {
        $this->authorize('Envato Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        return view('admin.envato.envatosetting')->with($data);
    }

    public function expiredsupport(Request $request)
    {
        $request->validate([
            'SUPPORT_POLICY_URL' => 'required|url',
        ]);

        $data['purchasecode_on']  =  $request->has('purchasecode_on') ? 'on' : 'off';
        $data['ENVATO_EXPIRED_BLOCK']  =  $request->has('ENVATO_EXPIRED_BLOCK') ? 'on' : 'off';
        $data['SUPPORT_POLICY_URL']  =  $request->input(['SUPPORT_POLICY_URL']);
        $this->updateSettings($data);

        return back()->with('success',  lang('Updated successfully', 'alerts'));
    }

    public function datetimeformatstore(Request $request)
    {

        $data['date_format'] = $request->date_format;
        $data['time_format'] = $request->time_format;

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function startweekstore(Request $request)
    {

        $data['start_week'] = $request->start_week;

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }


    public function timezoneupdate(Request $request)
    {
        $data['default_timezone'] = $request->timezones;

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function bussinesshourtitle(Request $request)
    {
        $request->validate([
            'businesshourstitle' => 'required|max:255',
        ]);
        if ($request->businesshourssubtitle) {
            $request->validate([
                'businesshourssubtitle' => 'max:255',
            ]);
        }
        $data['businesshourstitle'] = $request->businesshourstitle;
        $data['businesshourssubtitle'] = $request->businesshourssubtitle;
        $data['businesshoursswitch'] = $request->businesshoursswitch ? 'on' : 'off';

        if ($request->file('supporticon')) {
            $supportimage = $request->file('supporticon');
            $request->validate([
                'supporticon' => 'required|mimes:jpg,jpeg,png,svg|max:512',
            ]);
            //delete old file
            $supporticon = setting('supporticonimage');
            $imagepath = public_path() . "" . '/uploads/support/' . $supporticon;
            if (\File::exists($imagepath)) {
                \File::delete($imagepath);
            }
            //insert new file
            $destinationPath = public_path() . "" . '/uploads/support/'; // upload path
            $profileImage = date('YmdHis') . "." . $supportimage->getClientOriginalExtension();
            $supportimage->move($destinationPath, $profileImage);
            $data['supporticonimage'] = "$profileImage";
        }
        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function contactemail(Request $request)
    {
        if($request->has('CONTACT_ENABLE')){
            $request->validate([
                'contact_form_mail' => 'required|email',
            ]);
        }

        $data['CONTACT_ENABLE']  =  $request->CONTACT_ENABLE != null ? 'yes' : 'no';
        $data['contact_form_mail'] = $request->contact_form_mail;
        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function logindisable(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $data['login_disable_statement'] = $request->name;
        $this->updateSettings($data);

        return response()->json(['success' =>  lang('Updated successfully', 'alerts')], 200);
    }

    public function customerprofiledelete(Request $request)
    {
        // return redirect()->back()->with('error', 'this is an error to allowed minimum value is 1');
        $request->validate([
            'customer_inactive_notify_date' => 'required|numeric|gt:0',
            'customer_inactive_week_date' => 'required|numeric|gt:0',
            'guest_inactive_notify_date' => 'required|numeric|gt:0',
            'guest_inactive_week_date' => 'required|numeric|gt:0',
        ]);

        $data['customer_inactive_notify']  =  $request->has('customer_inactive_notify') ? 'on' : 'off';
        $data['customer_inactive_notify_date']  =  $request->input('customer_inactive_notify_date');
        $data['customer_inactive_week_date']  =  $request->input('customer_inactive_week_date');
        $data['guest_inactive_notify']  =  $request->has('guest_inactive_notify') ? 'on' : 'off';
        $data['guest_inactive_notify_date']  =  $request->input('guest_inactive_notify_date');
        $data['guest_inactive_week_date']  =  $request->input('guest_inactive_week_date');

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function customerautologout(Request $request)
    {
        if($request->has('customer_inactive_auto_logout')){
            $request->validate([
                'customer_inactive_auto_logout_time' => 'required|numeric|gt:0',
            ]);
        }

        if($request->has('admin_users_inactive_auto_logout')){
            $request->validate([
                'admin_users_inactive_auto_logout_time' => 'required|numeric|gt:0',
            ]);
        }

        $data['customer_inactive_auto_logout']  =  $request->has('customer_inactive_auto_logout') ? 'on' : 'off';
        $data['customer_inactive_auto_logout_time']  =  $request->input('customer_inactive_auto_logout_time');
        $data['admin_users_inactive_auto_logout']  =  $request->has('admin_users_inactive_auto_logout') ? 'on' : 'off';
        $data['admin_users_inactive_auto_logout_time']  =  $request->input('admin_users_inactive_auto_logout_time');

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function twofactauthsetting(Request $request)
    {
        $data['Employe_google_two_fact']  =  $request->has('Employe_google_two_fact') ? 'on' : 'off';
        if ($request->has('Employe_google_two_fact') == false) {
            $Users = User::where('twofactorauth', 'googletwofact')->get();
            foreach ($Users as $user) {
                $user->google2fa_secret = null;
                $user->twofactorauth = null;
                $user->update();

                $user->notify(new InformationSendToUsers($user));

                $ticketData = [
                    'username' => $user->username,
                ];

                if($user->usetting->emailnotifyon == 1){
                    dispatch((new MailSend($user->email, 'send_mail_to_users_when_two_factor_authentication_disabled', $ticketData)));
                }

            }
        }
        $data['Employe_email_two_fact']  =  $request->has('Employe_email_two_fact') ? 'on' : 'off';
        $data['Customer_google_two_fact']  =  $request->has('Customer_google_two_fact') ? 'on' : 'off';
        if ($request->has('Customer_google_two_fact') == false) {
            $customers = Customer::with('custsetting')->where('google2fa_secret', '!=', 'null')->get();
            foreach ($customers as $user) {
                $user['google2fa_secret'] = null;
                $user->save();
                $user->custsetting->twofactorauth = null;
                $user->custsetting->save();

                $user->notify(new InformationSendToUsers($user));

                $ticketData = [
                    'username' => $user->username,
                ];

                dispatch((new MailSend($user->email, 'send_mail_to_users_when_two_factor_authentication_disabled', $ticketData)));
            }
        }
        $data['Customer_email_two_fact']  =  $request->has('Customer_email_two_fact') ? 'on' : 'off';

        $this->updateSettings($data);

        return back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function bussinesslogodelete(Request $request)
    {
        $data['supporticonimage']  =  null;

        $this->updateSettings($data);
        return response()->json(['success' =>  lang('Updated successfully', 'alerts')], 200);
    }



    public function imapstore(Request $request)
    {
        $imap_id = $request->imap_id;

        if ($imap_id) {
            $request->validate([
                'imap_host' => 'required',
                'imap_port' => 'required',
                'imap_protocol' => 'required',
                'imap_encryption' => 'required',
                'imap_username' => 'required',
                'imap_password' => 'required',

            ]);
        } else {

            $request->validate([
                'imap_host' => 'required',
                'imap_port' => 'required',
                'imap_protocol' => 'required',
                'imap_encryption' => 'required',
                'imap_username' => 'required|unique:imap_settings',
                'imap_password' => 'required',

            ]);

            if ($request->category) {
                $imaps = Imap_setting::where('category_id', $request->category)->first();
                if ($imaps)
                    return response()->json(['code' => 500, 'category' => 'notunique', 'error' => lang('The imap category has already been taken.', 'alerts')], 500);
            }
        }


        $client = Client::make([
            'host'          => $request->imap_host,
            'port'          => $request->imap_port,
            'encryption'    => $request->imap_encryption,
            'validate_cert' => true,
            'username'      => $request->imap_username,
            'password'      => $request->imap_password,
            'protocol'      => $request->imap_protocol
        ]);
        try {
            $client->connect();
        } catch (\Exception $e) {
            return response()->json(['code'=>500, 'imapconnectionError'=>'notconnected', 'error'=> lang('Your imap credentials are invalid, please verify your entered details.', 'alerts')], 500);
        }


        $imapdata =  [
            'imap_host' => $request->imap_host,
            'imap_port' => $request->imap_port,
            'imap_protocol' => $request->imap_protocol,
            'imap_encryption' => $request->imap_encryption,
            'imap_username' => $request->imap_username,
            'imap_password' => $request->imap_password,
            'category_id' => $request->category,
            'status' => $request->status ?? 0,

        ];



        $ipdtaa = Imap_setting::updateOrCreate(['id' => $imap_id], $imapdata);
        return response()->json(['success' =>  lang('Updated successfully', 'alerts')], 200);
    }

    public function statuschange(Request $request, $id)
    {

        $this->authorize('Holidays Edit');

        $imap = Imap_setting::find($id);
        $imap->status = $request->status;
        $imap->save();

        return response()->json(['code' => 200, 'success' => lang('Status Updated successfully', 'alerts')], 200);
    }

    public function edit($id)
    {
        $this->authorize('Holidays Edit');
        $data = Imap_setting::find($id);
        return response()->json($data);
    }

    public function delete($id)
    {
        $this->authorize('Holidays Delete');

        $data = Imap_setting::find($id);
        $data->delete();
    }


    public function massdelete(Request $request)
    {
        $this->authorize('Holidays Delete');

        $holiday_array = $request->input('id');


        $holidays = Imap_setting::whereIn('id', $holiday_array)->get();

        foreach ($holidays as $holiday) {
            $holiday->delete();
        }
        return response()->json(['success' => lang('The imap settings was deleted successfully.', 'alerts')]);
    }

    public function smtpcheck()
    {
        $transport = new Swift_SmtpTransport(setting('mail_host'), setting('mail_port'));
        $transport->setUsername(setting('MAIL_USERNAME'));
        $transport->setPassword(setting('MAIL_PASSWORD'));
        $transport->setEncryption(setting('mail_encryption'));

        try {
            $mailer = new Swift_Mailer($transport);
            $mailer->getTransport()->start();
            return response()->json(1);

        } catch (\Swift_TransportException $e) {
            return response()->json(['code' => 500, 'imapconnectionError' => 'notconnected', 'error' => lang('Your smtp credentials are invalid, please setup correctly to add imap settings.', 'alerts')], 500);
        }
    }

    /**
     *  Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    private function updateSettings($data)
    {

        foreach ($data as $key => $val) {
            $setting = Setting::where('key', $key);
            if ($setting->exists())
                $setting->first()->update(['value' => $val]);
        }
    }
}
