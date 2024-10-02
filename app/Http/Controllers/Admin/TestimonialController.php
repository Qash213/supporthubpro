<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Testimonial;
use DataTables;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use Auth;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    public function index()
    {
        $this->authorize('Testimonial Access');

        $testimonials = Testimonial::latest()->get();
        $data['testimonials'] = $testimonials;

        $basic = Apptitle::first();

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.testimonial.index', compact('basic', 'title', 'footertext'))->with($data)->with('i', (request()->input('page', 1) - 1) * 5);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'description' => 'required',

        ]);
        if ($files = $request->file('image')) {

            $this->validate($request, [
                'image' => 'required|mimes:jpg,jpeg,png,svg|max:10240',
            ]);
        }

        $testiId = $request->testimonial_id;
        $testi =  [
            'name' => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
        ];
        if ($files = $request->file('image')) {

            if ($request->testimonial_id) {
                //delete old file
                $testiimage = Testimonial::find($request->testimonial_id);
                $provider  = existprovider($testiimage->storage_disk ?? 'public')->provider;
                $provider::delete('/uploads/testimonial/' . $testiimage->image);
            }

                $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
                $provider = storage()->provider;
                $provider::uploadImage($files,'/uploads/testimonial/',$profileImage);
                $testi['image'] = $profileImage;
                $testi['storage_disk'] = storage()->storage_disk;
        }

        $testimonial = Testimonial::updateOrCreate(['id' => $testiId], $testi);
        return response()->json(['code' => 200, 'success' => lang('The testimonial has been successfully created.', 'alerts'), 'data' => $testimonial], 200);
    }

    public function show($id)
    {
        $this->authorize('Testimonial Edit');
        $post = Testimonial::find($id);

        if($post->image != null){
            $provider = storage()->provider;
            $post['imageurl'] = route('getImage.url', ['imagePath' =>'uploads*testimonial*'.$post->image,'storage_disk'=>$post->storage_disk ?? 'public']);
        }else{
            $post['imageurl'] = null;
        }

        return response()->json($post);
    }

    public function destroy($id)
    {
        $this->authorize('Testimonial Delete');
        $data = Testimonial::where('id', $id)->first(['image','storage_disk']);
        $provider  = existprovider($data->storage_disk ?? 'public')->provider;
        $provider::delete('/uploads/testimonial/' . $data->image);
        $testimonial = Testimonial::find($id);
        $testimonial->delete();

        return response()->json(['success' => lang('The testimonial to was successfully deleted.', 'alerts')]);
    }

    public function imagedelete($id)
    {
        $this->authorize('Testimonial Edit');

        $testimoni = Testimonial::find($id);

        $provider  = existprovider($testimoni->storage_disk ?? 'public')->provider;
        $provider::delete('/uploads/testimonial/' . $testimoni->image);

        $testimoni->image = null;
        $testimoni->save();

        return response()->json(['success' => lang('The testimonial image was successfully deleted.', 'alerts')]);
    }

    public function alltestimonialdelete(Request $request)
    {
        $id_array = $request->input('id');

        $sendmails = Testimonial::whereIn('id', $id_array)->get();

        foreach ($sendmails as $sendmail) {
            $provider  = existprovider($sendmail->storage_disk ?? 'public')->provider;
            $provider::delete('/uploads/testimonial/' . $sendmail->image);
            $sendmail->delete();
        }
        return response()->json(['success' => lang('The testimonial to was successfully deleted.', 'alerts')]);
    }




    public function testi(Request $request)
    {
        $request->validate([
            'testimonialtitle' => 'required|max:255',

        ]);
        if ($request->testimonialsub) {
            $request->validate([
                'testimonialsub' => 'max:255',

            ]);
        }
        $calID = ['id' => $request->id];
        $calldetails = [
            'testimonialtitle' => $request->testimonialtitle,
            'testimonialsub' => $request->testimonialsub,
            'testimonialcheck'  => $request->has('testimonialcheck') ? 'on' : 'off',

        ];

        $callaction = Apptitle::updateOrCreate(
            ['id' => $calID],
            $calldetails
        );



        return redirect()->back()->with('success', lang('Updated Successfully', 'alerts'));
    }
}
