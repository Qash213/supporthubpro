<?php

namespace App\Http\Controllers;

use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use Illuminate\Http\Request;
use Auth;
use File;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageController extends Controller
{
    public function index($id, $image)
    {

        if (Auth::check() && Auth::user() || Auth::guard('customer')->check() && Auth::guard('customer')->user() || session()->has('guestimageaccess')) {

            $media = Media::where('id', $id)->first()->disk;
            $provider =  existprovider($media)->provider;
            $imagepath = $provider::getImage('media/' . $id . '/' . $image);
            return $imagepath;
        } else {
            abort(404);
        }
    }

    public function imagedownload($id, $image)
    {

        if (Auth::check() && Auth::user() || Auth::guard('customer')->check() && Auth::guard('customer')->user() || session()->has('guestimageaccess')) {
            $media = Media::where('id', $id)->first()->disk;
            $provider =  existprovider($media)->provider;
            $imagepath = $provider::getImageDownload('media/' . $id . '/' . $image);
            return $imagepath;
        } else {
            abort(404);
        }
    }


    //pending

    public function emailtoticketshow($id, $image)
    {

        if (Auth::check() && Auth::user() || Auth::guard('customer')->check() && Auth::guard('customer')->user()) {
            $ticket = Ticket::find($id);
            $provider =  existprovider($ticket->storage_disk)->provider;
            $imagepath = $provider::getImage('uploads/emailtoticket/' . $image);
            return $imagepath;
        } else {
            abort(404);
        }

    }

    public function emailtoticketdownload($id, $image)
    {
        if (Auth::check() && Auth::user() || Auth::guard('customer')->check() && Auth::guard('customer')->user()) {
            $ticket = Ticket::find($id);
            $provider =  existprovider($ticket->storage_disk)->provider;
            $imagepath = $provider::getImageDownload('uploads/emailtoticket/' . $image);
            return $imagepath;
        } else {
            abort(404);
        }

    }

    public function emtcimageurlshow($id, $image)
    {

        if (Auth::check() && Auth::user() || Auth::guard('customer')->check() && Auth::guard('customer')->user()) {
            $comment = Comment::find($id);
            $provider =  existprovider($comment->storage_disk)->provider;
            $imagepath = $provider::getImage('uploads/emailtoticketcomment/' . $image);
            return $imagepath;
        } else {
            abort(404);
        }

    }

    public function emtcimagedownload($id, $image)
    {

        if (Auth::check() && Auth::user() || Auth::guard('customer')->check() && Auth::guard('customer')->user()) {

            $comment = Comment::find($id);
            $provider =  existprovider($comment->storage_disk)->provider;
            $imagepath = $provider::getImageDownload('uploads/emailtoticketcomment/' . $image);
            return $imagepath;
        } else {
            abort(404);
        }
    }

    public function guestimage($id, $image)
    {

        $profile_path = public_path('media/' . $id . '/' . $image);
        if (File::exists($profile_path)) {
            if (session()->has('guestimageaccess')) {

                return response()->file($profile_path);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }



    public function getProfileUrl(Request $request, $storage_disk, $imagePath)
    {
        if (Auth::check() && Auth::user() || Auth::guard('customer')->check() && Auth::guard('customer')->user() || session()->get('guestdetailssession') == $request->ticket_id) {
            $provider  = existprovider($storage_disk)->provider;
            $image = $provider::getprofile($imagePath);

            if($image)
                return $image;
            else
            abort(404);
        } else {
            abort(404);
        }
    }

    public function getImage($storage_disk, $imagePath)
    {

            $path = str_replace('*', '/', ($imagePath));

            $provider  = existprovider($storage_disk)->provider;
            $image = $provider::getImageUrl($path);
            if($image)
              return $image;
            else
              abort(404);

    }
}
