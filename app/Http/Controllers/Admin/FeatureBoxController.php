<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FeatureBox;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use DataTables;
use Auth;
use Illuminate\Support\Facades\Validator;
use Response;
use File;
use Illuminate\Support\Str;

class FeatureBoxController extends Controller
{
  public function index()
  {

    $this->authorize('Feature Box Access');
    $feature = FeatureBox::get();
    $basic = Apptitle::first();

    $title = Apptitle::first();
    $data['title'] = $title;

    $footertext = Footertext::first();
    $data['footertext'] = $footertext;

    $seopage = Seosetting::first();
    $data['seopage'] = $seopage;

    $post = Pages::all();
    $data['page'] = $post;

    $featureboxes = FeatureBox::latest()->get();
    $data['featureboxes'] = $featureboxes;

    return view('admin.featurebox.index', compact('feature', 'basic', 'title', 'footertext'))->with($data);
  }

  public function show($id)
  {
    $this->authorize('Feature Box Edit');
    $post = FeatureBox::find($id);
    if($post->image != null){
        $provider = storage()->provider;
        $post['imageurl'] = route('getImage.url', ['imagePath' =>'uploads*featurebox*'.$post->image,'storage_disk'=>$post->storage_disk ?? 'public']);
    }else{
        $post['imageurl'] = null;
    }

    return response()->json($post);
  }

  public function store(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'title' => 'required|string|max:255',
      'subtitle' => 'required|string|max:255',
    ]);


    if ($files = $request->file('image')) {

      $validator = Validator::make($request->all(), [
        'image' => 'required|mimes:jpg,jpeg,png,svg|max:10240',
      ]);
    }


    if ($validator->passes()) {


      $boxID = ['id' => $request->featurebox_id];
      $boxdetails = [
        'title' => $request->title,
        'subtitle' => $request->subtitle,
        'featureboxurl' => $request->featureboxurl,
        'url_checkbox' => $request->url_checkbox,
      ];

      if ($files = $request->file('image')) {
        $files = $request->file('image');

        if ($request->featurebox_id) {
          $testiimage = FeatureBox::find($request->featurebox_id);
          $imagepath =   public_path() . "" . '/uploads/featurebox/' . $testiimage->image;
          if (\File::exists($imagepath)) {
            \File::delete($imagepath);
          }
        }

          $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
          $provider = storage()->provider;
          $upload =  $provider::uploadImage($files,'/uploads/featurebox/',$profileImage);
          if($upload){
            $boxdetails['image'] = $profileImage;
            $boxdetails['storage_disk'] = storage()->storage_disk;
          }

      }

      $feature = FeatureBox::updateOrCreate(['id' => $boxID], $boxdetails);

      return response()->json(['code' => 200, 'success' => lang('Featurebox Updated successfully', 'alerts'), 'data' => $feature], 200);
    } else {
      return Response::json(['errors' => $validator->errors()]);
    }
  }

  public function imagedelete($id)
  {
    $this->authorize('Feature Box Edit');

    $featurebox = FeatureBox::find($id);

    $provider  = existprovider($featurebox->storage_disk ?? 'public')->provider;
    $provider::delete('/uploads/featurebox/' . $featurebox->image);

    $featurebox->image = null;
    $featurebox->save();

    return response()->json(['success' => lang('The featurebox image was successfully deleted.', 'alerts')]);
  }

  public function destroy($id)
  {
    $this->authorize('Feature Box Delete');
    $data = FeatureBox::where('id', $id)->first(['image','storage_disk']);
    $provider  = existprovider($data->storage_disk ?? 'public')->provider;
    $provider::delete('/uploads/featurebox/' . $data->image);

    $testimonial = FeatureBox::find($id);
    $testimonial->delete();

    return response()->json(['success' => lang('The featurebox was successfully deleted.', 'alerts')]);
  }

  public function allfeaturedelete(Request $request)
  {
    $id_array = $request->input('id');

    $sendmails = FeatureBox::whereIn('id', $id_array)->get();
    foreach ($sendmails as $sendmail) {
      $provider  = existprovider($sendmail->storage_disk ?? 'public')->provider;
      $provider::delete('/uploads/featurebox/' . $sendmail->image);
      $sendmail->delete();
    }
    return response()->json(['success' => lang('The featurebox was successfully deleted.', 'alerts')]);
  }

  public function feature(Request $request)
  {

    $request->validate([
      'featuretitle' => 'required|string|max:255',
    ]);

    if ($request->featuresub) {
      $request->validate([
        'featuresub' => 'string|max:255',
      ]);
    }

    $calID = ['id' => $request->id];
    $calldetails = [
      'featuretitle' => $request->featuretitle,
      'featuresub' => $request->featuresub,
      'featurecheck' => $request->has('featurecheck') ? 'on' : 'off',

    ];
    $callaction = Apptitle::updateOrCreate(
      ['id' => $calID],
      $calldetails
    );


    return redirect()->back()->with('success', lang('Updated Successfully', 'alerts'));
  }
}
