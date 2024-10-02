<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Image;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocalStorageController extends Controller
{
    public static function uploadprofile($file, $image_name)
    {
        $destination = public_path() . "" . '/uploads/profile';
        $resize_image = Image::make($file->getRealPath());
        $resize_image->resize(80, 80, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destination . '/' . $image_name);

        return $resize_image;
    }

    public static function delete($path)
    {

        $destinations = public_path() . "" . $path;
        if (File::exists($destinations)) {
            File::delete($destinations);
        }
        return true;
    }

    public static function getprofile($imagePath)
    {
        $path = public_path('uploads/profile/' . $imagePath);
        if (file_exists($path)) {
            $fileContents = file_get_contents($path);
            $response = response($fileContents)->header('Content-Type', 'image/jpeg'); // Adjust the Content-Type as needed
            return $response;
        }
    }

    public static function mediaupload($media, $path, $collection)
    {
        return $media->addMedia(public_path($path))->toMediaCollection($collection);
    }

    public static function getImage($path)  //to display ticket and comment images
    {
        $profile_path = public_path($path);
        if (File::exists($profile_path)) {

            return response()->file($profile_path);
        } else {
            abort(404);
        }
    }

    public static function getImageDownload($path)
    {
        $profile_path = public_path($path);
        return response()->download($profile_path);
    }

    public static function emailtoticket($path, $oAttachment)
    {

        return file_put_contents(public_path($path), $oAttachment->content);
    }

    public static function uploadImage($files, $path, $image)
    {
        $image_path = public_path() . "" . $path;
        if (!file_exists($image_path)) {
                mkdir($image_path, 0777, true);
            }
        $image = $files->move($image_path, $image);
        return $image;
    }

    public static function getImageUrl($path)
    {
        $pathmedia = public_path('media/'.$path);
        $path = public_path($path);
        if (file_exists($path)) {
            $fileContents = file_get_contents($path);
            $response = response($fileContents)->header('Content-Type', 'image/jpeg'); // Adjust the Content-Type as needed
            return $response;
        }
       //this is for draft images
        if (file_exists($pathmedia)) {
            $fileContents = file_get_contents($pathmedia);
            $response = response($fileContents)->header('Content-Type', 'image/jpeg'); // Adjust the Content-Type as needed
            return $response;
        }
    }

    public static function getdraft($ticketdr)
  {
    $storjContent = file_get_contents($ticketdr->getPath());
    return $storjContent;
  }

  public static function draftupload($comment, $localTempFilePath)
  {
    $media = $comment->addMedia($localTempFilePath)->toMediaCollection('comments');
    return $media;
  }

  public static function tempImage($commentss,$localTempFilePath)
  {
     $contentPath = $commentss->getPath();
     return $contentPath;
  }

}
