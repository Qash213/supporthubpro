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

class StorjController extends Controller
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

      Storage::disk('storj')->put($path, file_get_contents($tempImagePath));

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
      if (Storage::disk('storj')->exists($path)) {
        Storage::disk('storj')->delete($path);
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
      if (Storage::disk('storj')->exists($path)) {
        $temp = Storage::disk('storj')->temporaryUrl($path, now()->addMinutes(1));
        return redirect($temp);
      }
    } catch (\Exception $e) {
      $errorMessage = 'Failed togetprofile image: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function mediaupload($media, $path, $collection)
  {
    return $media->addMedia(public_path($path))->toMediaCollection($collection, 'storj');
  }

  public static function getImage($path)
  {
    if (Str::startsWith($path, 'media/')) {
      $newPath = Str::replaceFirst('media/', '', $path);
    } else {
      $newPath = $path;
    }

    try {
      if (Storage::disk('storj')->exists($newPath)) {
        $temp = Storage::disk('storj')->temporaryUrl($newPath, now()->addMinutes(1));
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
      if (Storage::disk('storj')->exists($newPath)) {
        $temp = Storage::disk('storj')->download($newPath);
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
      $store = Storage::disk('storj')->put($path, file_get_contents($image_path));
      return $store;
    } catch (\Exception $e) {
      $errorMessage = 'Failed to upload image: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function uploadImage($files, $path, $image)
  {
    try {
      return Storage::disk('storj')->put($path . '' . $image, file_get_contents($files));
    } catch (\Exception $e) {
      $errorMessage = 'Failed to upload image: ' . $e->getMessage();
      return back()->with('error', $errorMessage);
    }
  }

  public static function getImageUrl($path)
  {
    try {
      if (Storage::disk('storj')->exists($path)) {
        $temp = Storage::disk('storj')->temporaryUrl($path, now()->addMinutes(1));
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
      'Storj Access key',
      'Storj Secrete key',
      'Storj Default Region',
      'Storj Bucket',
      'Storj End point',
    ];
    return [$credentials, $credentialsData];
  }

  public static function updateEnv($data)
  {
    $addon = Addon::find($data->addonid);
    $credstore = Storage_disk::where('provider', $addon->handler)->first();

    $credentials = [
      'access_key_id' => $data->Storj_Access_key,
      'secret_access_key' => $data->Storj_Secrete_key,
      'default_region' => $data->Storj_Default_Region,
      'bucket' => $data->Storj_Bucket,
      'endpoint' => $data->Storj_End_point,
    ];

    $credstore->credentials_data = json_encode($credentials);
    $credstore->save();
    updateEnv('STORJ_ACCESS_KEY_ID', $data->Storj_Access_key);
    updateEnv('STORJ_SECRET_ACCESS_KEY', $data->Storj_Secrete_key);
    updateEnv('STORJ_DEFAULT_REGION', $data->Storj_Default_Region);
    updateEnv('STORJ_BUCKET', $data->Storj_Bucket);
    updateEnv('STORJ_ENDPOINT', $data->Storj_End_point);
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
    $storjContent = Storage::disk('storj')->get($ticketdr->getPath());
    return $storjContent;
  }

  public static function draftupload($comment, $localTempFilePath)
  {
    $media = $comment->addMedia($localTempFilePath)->toMediaCollection('comments', 'storj');
    return $media;
  }

  public static function tempImage($commentss,$localTempFilePath)
  {
    $content = Storage::disk('storj')->get($commentss->getPath());
    file_put_contents($localTempFilePath, $content);
    return $localTempFilePath;
  }
  
}
