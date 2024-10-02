<?php

namespace Uhelp\Addons\App\Http\Controllers\Storage;

use App\Models\Addon;
use App\Models\Storage_disk;
use Uhelp\Addons\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use Image;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class S3Controller extends Controller
{
  public static function uploadprofile($file, $image_name)
  {
    $resize_image = Image::make($file->getRealPath());
    try {
      $resize_image->resize(80, 80, function ($constraint) {
        $constraint->aspectRatio();
      });

      $path = '/uploads/profile/' . $image_name;
      $tempPath = public_path() . "/temp";

      if (!file_exists($tempPath)) {
        mkdir($tempPath, 0755, true);
      }

      $tempImagePath = $tempPath . '/' . $image_name;
      $resize_image->save($tempImagePath);

      Storage::disk('S3')->put($path, file_get_contents($tempImagePath));

      if (file_exists($tempImagePath)) {
        File::delete($tempImagePath);
      }

      return $path;
    } catch (\Exception $e) {
      $errorMessage = 'Failed to upload image: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function delete($path)
  {
    try {
      if (Storage::disk('S3')->exists($path)) {
        Storage::disk('S3')->delete($path);
        return 'File deleted successfully';
      }
    } catch (\Exception $e) {

      return 'An error occurred';
    }
    return true;
  }

  public static function getprofile($imagePath)
  {
    $path = 'uploads/profile/' . $imagePath;

    try {
      if (Storage::disk('S3')->exists($path)) {
        $temp = Storage::disk('S3')->temporaryUrl($path, now()->addMinutes(1));
        return redirect($temp);
      }
    } catch (\Exception $e) {
      $errorMessage = 'Failed togetprofile image: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function mediaupload($media, $path, $collection)
  {
    return $media->addMedia(public_path($path))->toMediaCollection($collection, 'S3');
  }

  public static function getImage($path)
  {
    if (Str::startsWith($path, 'media/')) {
      $newPath = Str::replaceFirst('media/', '', $path);
    } else {
      $newPath = $path;
    }

    try {
      if (Storage::disk('S3')->exists($newPath)) {
        $temp = Storage::disk('S3')->temporaryUrl($newPath, now()->addMinutes(1));
        return redirect($temp);
      } else {
        abort(404);
      }
    } catch (\Exception $e) {
      $errorMessage = 'Failed to getpimage: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function getImageDownload($path)
  {
    if (Str::startsWith($path, 'media/')) {
      $newPath = Str::replaceFirst('media/', '', $path);
    } else {
      $newPath = $path;
    }
    try {
      if (Storage::disk('S3')->exists($newPath)) {
        $temp = Storage::disk('S3')->download($newPath);
        return  $temp;
      } else {
        abort(404);
      }
    } catch (\Exception $e) {
      $errorMessage = 'Failed to image download: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function emailtoticket($path, $oAttachment)
  {
    try {
      file_put_contents(public_path($path), $oAttachment->content);
      $image_path = public_path() . "/" . $path;
      $store = Storage::disk('S3')->put($path, file_get_contents($image_path));
      return $store;
    } catch (\Exception $e) {
      $errorMessage = 'Failed to upload image: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function uploadImage($files, $path, $image)
  {
    try {
      return Storage::disk('S3')->put($path . '' . $image, file_get_contents($files));
    } catch (\Exception $e) {
      $errorMessage = 'Failed to upload image: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function getImageUrl($path)
  {
    try {
      if (Storage::disk('S3')->exists($path)) {
        $temp = Storage::disk('S3')->temporaryUrl($path, now()->addMinutes(1));
        return redirect($temp);
      }
    } catch (\Exception $e) {
      $errorMessage = 'Failed togetprofile image: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function edit($handler)
  {
    $credentials = Storage_disk::where('provider', $handler)->first();
    $credentialsData = json_decode($credentials->credentials_data);
    $credentials = [
      'S3 Access key',
      'S3 Secrete key',
      'S3 Default Region',
      'S3 Bucket',
      'S3 End point',
    ];
    return [$credentials, $credentialsData];
  }

  public static function updateEnv($data)
  {
    $addon = Addon::find($data->addonid);
    $credstore = Storage_disk::where('provider', $addon->handler)->first();

    $credentials = [
      'access_key_id' => $data->S3_Access_key,
      'secret_access_key' => $data->S3_Secrete_key,
      'default_region' => $data->S3_Default_Region,
      'bucket' => $data->S3_Bucket,
      'endpoint' => $data->S3_End_point,
    ];

    $credstore->credentials_data = json_encode($credentials);
    $credstore->save();
    updateEnv('AWS_ACCESS_KEY_ID', $data->S3_Access_key);
    updateEnv('AWS_SECRET_ACCESS_KEY', $data->S3_Secrete_key);
    updateEnv('AWS_DEFAULT_REGION', $data->S3_Default_Region);
    updateEnv('AWS_BUCKET', $data->S3_Bucket);
    updateEnv('AWS_ENDPOINT', $data->S3_End_point);
    return true;
  }

  public static function statuschange($request, $id)
  {
    $addon = Addon::find($id);
    $status = Storage_disk::where('provider', $addon->handler)->first();
    $credentials = json_decode($status->credentials_data);
    $statusList = Storage_disk::all();

    if ($request->status) {
      if (
        empty($credentials->access_key_id) ||
        empty($credentials->secret_access_key) ||
        empty($credentials->default_region) ||
        empty($credentials->bucket) ||
        empty($credentials->endpoint)
      ) {
        return false;
      } else {
        foreach ($statusList as $stat) {
          if ($stat->provider == $addon->handler) {
            $stat->update(['status' => 1]);
          } else {
            $stat->update(['status' => null]);
          }
        }
        return true;
      }
    } else {
      foreach ($statusList as $stat) {
        $addon->update(['status' => 0]);
        if ($stat->provider == "App\Http\Controllers\Storage\LocalStorageController") {
          $stat->update(['status' => 1]);
        } else {
          $stat->update(['status' => null]);
        }
      }
      return true;
    }
  }

  public static function getStatus($handler)
  {
    if (Storage_disk::where('provider', $handler)->first())
      return Storage_disk::where('provider', $handler)->first()->status;
    else
      return false;
  }

  public static function getdraft($ticketdr)
  {
    $storjContent = Storage::disk('S3')->get($ticketdr->getPath());
    return $storjContent;
  }

  public static function draftupload($comment, $localTempFilePath)
  {
    $media = $comment->addMedia($localTempFilePath)->toMediaCollection('comments', 'S3');
    return $media;
  }

  public static function tempImage($commentss,$localTempFilePath)
  {
    $content = Storage::disk('S3')->get($commentss->getPath());
    file_put_contents($localTempFilePath, $content);
    return $localTempFilePath;
  }

}
