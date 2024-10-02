<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seosetting;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Setting;
use App\Models\Countries;
use GeoIP;
use \Webklex\IMAP\Facades\Client;

class SecuritySettingController extends Controller
{
    public function index()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $country = Countries::all();
        $data['countries'] = $country;

        return view('admin.securitysetting.securitysetting')->with($data);
    }

    public function store()
    {



        $data['COUNTRY_BLOCKTYPE'] = request()->countryblock;
        if(request()->countrylist){
            $countrycode = implode(',',request()->countrylist);
            $data['COUNTRY_LIST'] = $countrycode;
        }else{
            $data['COUNTRY_LIST'] = request()->countrylist;
        }

        $this->updateSettings($data);
        return redirect()->back()->with('success', lang('Updated successfully', 'alerts'));
    }

    public function adminstore()
    {


        $admincountry =GeoIP::getLocation(request()->getClientIp());

        if(request()->admincountryblock == 'block'){
            if(request()->admincountrylist){
                if(in_array($admincountry->iso_code,request()->admincountrylist)){
                    return redirect()->back()->with('error', lang('You are not supposed to block your own country.', 'alerts'));
                }else{
                    $data['ADMIN_COUNTRY_BLOCKTYPE'] = request()->admincountryblock;
                    if(request()->admincountrylist){
                        $admincountrycode = implode(',',request()->admincountrylist);
                        $data['ADMIN_COUNTRY_LIST'] = $admincountrycode;
                    }else{
                        $data['ADMIN_COUNTRY_LIST'] = request()->admincountrylist;
                    }

                    $this->updateSettings($data);
                    return redirect()->back()->with('success', lang('Updated successfully', 'alerts'));
                }
            }else{
                $data['ADMIN_COUNTRY_BLOCKTYPE'] = request()->admincountryblock;
                if(request()->admincountrylist){
                    $admincountrycode = implode(',',request()->admincountrylist);
                    $data['ADMIN_COUNTRY_LIST'] = $admincountrycode;
                }else{
                    $data['ADMIN_COUNTRY_LIST'] = request()->admincountrylist;
                }
                $this->updateSettings($data);
                return redirect()->back()->with('success', lang('Updated successfully', 'alerts'));
            }

        }
        if(request()->admincountryblock == 'allow'){
            if(request()->admincountrylist){
                if(in_array($admincountry->iso_code,request()->admincountrylist)){
                    $data['ADMIN_COUNTRY_BLOCKTYPE'] = request()->admincountryblock;
                    if(request()->admincountrylist){
                        $admincountrycode = implode(',',request()->admincountrylist);
                        $data['ADMIN_COUNTRY_LIST'] = $admincountrycode;
                    }else{
                        $data['ADMIN_COUNTRY_LIST'] = request()->admincountrylist;
                    }
                    $this->updateSettings($data);
                    return redirect()->back()->with('success', lang('Updated successfully', 'alerts'));
                }else{
                    return redirect()->back()->with('error', lang('You are not supposed to block your own country.', 'alerts'));
                }
            }else {
                $data['ADMIN_COUNTRY_BLOCKTYPE'] = request()->admincountryblock;
                    if(request()->admincountrylist){
                        $admincountrycode = implode(',',request()->admincountrylist);
                        $data['ADMIN_COUNTRY_LIST'] = $admincountrycode;
                    }else{
                        $data['ADMIN_COUNTRY_LIST'] = request()->admincountrylist;
                    }
                    $this->updateSettings($data);
                    return redirect()->back()->with('success', lang('Updated successfully', 'alerts'));
            }
        }


    }

    public function dosstore()
    {
        $this->validate(request(), [
            'ip_max_attempt' => ['required', 'numeric', 'digits_between:1,10000'],
            'ip_seconds' => ['required', 'numeric', 'digits_between:1,10000'],

        ]);

        if(request()->ipblocktype == 'captcha' && request()->has('dosswitch')){
            if(setting('CAPTCHATYPE') == 'off'){
                return redirect()->back()->with('error', lang('First you need to enable captcha in the captcha setting.', 'alerts'));
            }
        }

        $data['IPBLOCKTYPE'] = request()->ipblocktype;
        $data['IPMAXATTEMPT'] = request()->ip_max_attempt;
        $data['IPSECONDS'] = request()->ip_seconds;
        $data['DOS_Enable'] =  request()->has('dosswitch') ? 'on' : 'off';

        $this->updateSettings($data);
        return redirect()->back()->with('success', lang('Updated successfully', 'alerts'));

    }


    public function emaildomainlist(Request $request)
    {
        $data['EMAILDOMAIN_BLOCKTYPE'] = $request->emaildomainblock;
        $data['EMAILDOMAIN_LIST'] = $request->emaildomain;
        $this->updateSettings($data);
        return redirect()->back()->with('success', lang('Updated successfully', 'alerts'));
    }


    /**
     *  Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    private function updateSettings($data)
    {

        foreach($data as $key => $val){
        	$setting = Setting::where('key', $key);
        	if( $setting->exists() )
        		$setting->first()->update(['value' => $val]);
        }

    }

    public function setLanguage($locale)
    {

        \App::setlocale($locale);
        session()->put('locale', $locale);
        return redirect()->back()->with('success', lang('The language has been successfully updated', 'alerts'));
    }
}
