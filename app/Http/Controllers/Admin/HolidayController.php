<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Holiday;
use App\Models\Announcement;
use App\Models\Pages;
use App\Imports\HolidaysImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {

        $this->authorize('Holidays Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;
        $data['holidays'] = Holiday::all();

        return view('admin.holidays.holidays')->with($data);
    }

    public function saveholidays(Request $request)
    {
        $this->authorize('Holidays Create');

        $request->validate([
            'occasion' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
            'holidaydescription' => 'required',
            'primary_color' => 'required',
            'secondary_color' => 'required',
        ]);

        $hodidayid = $request->holiday_id;
        $holidaydata =  [
            'occasion' => $request->occasion,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
            'holidaydescription' => $request->holidaydescription,
            'primaray_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'status' => $request->status,

        ];

        $ipdtaa = Holiday::updateOrCreate(['id' => $hodidayid], $holidaydata);

        return response()->json(['success' => lang('Holidays updated successfully.', 'alerts')]);
    }
    public function edit($id)
    {
        $this->authorize('Holidays Edit');

        $data = Holiday::find($id);
        return response()->json($data);
    }

    public function delete($id)
    {
        $this->authorize('Holidays Delete');

        $data = Holiday::find($id);
        $data->delete();
    }

    public function statuschange(Request $request, $id)
    {
        $this->authorize('Holidays Edit');

        $custstatus = Holiday::find($id);
        $custstatus->status = $request->status;
        $custstatus->save();

        return response()->json(['code' => 200, 'success' => lang('Status Updated successfully', 'alerts')], 200);
    }

    public function massdelete(Request $request)
    {
        $this->authorize('Holidays Delete');

        $holiday_array = $request->input('id');


        $holidays = Holiday::whereIn('id', $holiday_array)->get();

        foreach ($holidays as $holiday) {
            $holiday->delete();
        }
        return response()->json(['success' => lang('The holidays was deleted successfully.', 'alerts')]);
    }

    public function holidaysimport()
    {
        $this->authorize('Holidays Import Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.holidays.holidaysimport')->with($data);
    }

    public function holidayscsv(Request $request)
    {
        $this->authorize('Holidays Import Access');

        $request->validate(['file' => 'required',]);

        $file = $request->file('file')->store('import');
        $import = Excel::import(new HolidaysImport, $file);

        return redirect()->route('admin.holidays')->with('success', lang('The Holidays list was imported successfully.', 'alerts'));
    }
}
