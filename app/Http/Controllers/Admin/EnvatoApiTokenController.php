<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\APIData;


class EnvatoApiTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $this->authorize('Envato API Token Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $apidata = APIData::first();
        $data['apidata'] = $apidata;

        return view('admin.envatoapitoken.index')->with($data);
    }

    // Its Store a Data into database of API token
    public function storeupdate(Request $request)
    {
        $envatoapi_id = $request->enavto_id;
        $enavto =  [
            'envatoapitoken' => $request->envatoapi,
            'envatoapitokensecond' => $request->envatoapisecond,
            'envatoapitokenthird' => $request->envatoapithird,
        ];
        APIData::updateOrCreate(['id' => $envatoapi_id], $enavto);
        return redirect()->back()->with('success', lang('Updated Successfully'));
    }

    public function licensesearch()
    {
        $this->authorize('Envato License Details Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $apidata = APIData::first();
        $data['apidata'] = $apidata;

        return view('admin.envatoapitoken.licensesearch')->with($data);
    }

    public function licensesearchget(Request $request)
    {
        $apidatatoken = APIData::first();

        $envato_license = $request->envato_search;

        $url = "https://api.envato.com/v3/market/author/sale?code=".$envato_license;
        $curl = curl_init($url);

        $personal_api_token = $apidatatoken != null ? $apidatatoken->envatoapitoken : '';

        /*Correct header for the curl extension*/
        $header = array();
        $header[] = 'Authorization: Bearer '.$personal_api_token;
        $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
        $header[] = 'timeout: 20';
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

        /*Connect to the API, and get values from there*/
        $envatoCheck = curl_exec($curl);
        curl_close($curl);

        $envatoCheck = json_decode($envatoCheck);

        $firstone = isset($envatoCheck->error);

        $secondone = null;
        if($firstone == true){
            $personal_api_token = $apidatatoken != null ? $apidatatoken->envatoapitokensecond : '';

            /*Correct header for the curl extension*/
            $header = array();
            $header[] = 'Authorization: Bearer '.$personal_api_token;
            $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
            $header[] = 'timeout: 100';
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

            /*Connect to the API, and get values from there*/
            $envatoCheck = curl_exec($curl);
            curl_close($curl);

            $envatoCheck = json_decode($envatoCheck);

            if(isset($envatoCheck->error)){
                $secondone = true;
            }
        }

        if($secondone == true){
            $personal_api_token = $apidatatoken != null ? $apidatatoken->envatoapitokenthird : '';

            /*Correct header for the curl extension*/
            $header = array();
            $header[] = 'Authorization: Bearer '.$personal_api_token;
            $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
            $header[] = 'timeout: 100';
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

            /*Connect to the API, and get values from there*/
            $envatoCheck = curl_exec($curl);
            curl_close($curl);

            $envatoCheck = json_decode($envatoCheck);
        }

        /*Variable request from the API*/
        $date = new \DateTime(isset($envatoCheck->supported_until) ? $envatoCheck->supported_until : false);
        $support_date = $date->format('Y-m-d H:i:s');
        $sold = new \DateTime(isset($envatoCheck->sold_at) ? $envatoCheck->sold_at : false);
        $sold_at = $sold->format('Y-m-d H:i:s');
        $buyer = (isset( $envatoCheck->buyer) ? $envatoCheck->buyer : false);
        $license = (isset( $envatoCheck->license) ? $envatoCheck->license : false);
        $count = (isset( $envatoCheck->purchase_count) ? $envatoCheck->purchase_count : false);
        $support_amount = (isset( $envatoCheck->support_amount) ? $envatoCheck->support_amount : false);
        $item  = (isset( $envatoCheck->item->previews->icon_preview->icon_url ) ? $envatoCheck->item->previews->icon_preview->icon_url  : false);
        $amount = (isset( $envatoCheck->amount) ? $envatoCheck->amount : false);

        $output = "";
        /*If Purchase code exists, But Purchase ended*/
        if (isset($envatoCheck->item->name) && (date('Y-m-d H:i:s') >= $support_date)){

            $output .=  "
                <hr>
                <div class='card-body pt-2'>
                        <div class='row'>
                            <div class='col-md-6 mb-5'>
                                <table class='table table-striped table-bordered'>
                                    <tbody>
                                            <tr>
                                                <th colspan='2' class='fs-16 fw-semibold'>Client Details</th>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>ITEM_ICON :</b></td>
                                                <td> <img src='{$item}' class='img-responsive br-7' /></td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>CLIENT:</b></td>
                                                <td>{$buyer}</td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>SOLD AT:</b></td>
                                                <td> {$sold_at}</td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>SUPPORT UNTIL:</b></td>
                                                <td> {$support_date} <span class='badge badge-danger-dark ms-1'>Support Expired</span></td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>LICENSE:</b></td>
                                                <td> {$license}</td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>COUNT:</b></td>
                                                <td> {$count}</td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>PRICE:</b></td>
                                                <td> {$amount}</td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-6 mb-5'>
                            <table class='table table-striped table-bordered'>
                                <tbody>
                                    <tr>
                                            <th colspan='2' class='fs-16 fw-semibold'>Author Details</th>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>ITEM_ID:</b></td>
                                            <td>{$envatoCheck->item->id}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Author Name:</b></td>
                                            <td>{$envatoCheck->item->author_username}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Item Name:</b></td>
                                            <td>{$envatoCheck->item->name}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Published:</b></td>
                                            <td> {$envatoCheck->item->published_at}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Updated:</b></td>
                                            <td>{$envatoCheck->item->updated_at}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Rating:</b></td>
                                            <td> {$envatoCheck->item->rating}</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                </div>

            ";

        }

        /*If Purchase code exists, display client information*/
        if (isset($envatoCheck->item->name) && (date('Y-m-d H:i:s') < $support_date)){

            $output .=  "
                <hr>
                <div class='card-body pt-2'>
                        <div class='row'>
                            <div class='col-md-6 mb-5'>
                                <table class='table table-striped table-bordered'>
                                    <tbody>
                                            <tr>
                                                <th colspan='2' class='fs-16 fw-semibold'>Client Details</th>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>ITEM_ICON :</b></td>
                                                <td> <img src='{$item}' class='img-responsive br-7' /></td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>CLIENT:</b></td>
                                                <td>{$buyer}</td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>SOLD AT:</b></td>
                                                <td> {$sold_at}</td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>SUPPORT UNTIL:</b></td>
                                                <td> {$support_date} <span class='badge badge-success ms-1'>Supported</span></td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>LICENSE:</b></td>
                                                <td> {$license}</td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>COUNT:</b></td>
                                                <td> {$count}</td>
                                            </tr>
                                            <tr>
                                                <td class='w-30'><b>PRICE:</b></td>
                                                <td> {$amount}</td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-6 mb-5'>
                            <table class='table table-striped table-bordered'>
                                <tbody>
                                    <tr>
                                            <th colspan='2' class='fs-16 fw-semibold'>Author Details</th>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>ITEM_ID:</b></td>
                                            <td>{$envatoCheck->item->id}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Author Name:</b></td>
                                            <td>{$envatoCheck->item->author_username}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Item Name:</b></td>
                                            <td>{$envatoCheck->item->name}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Published:</b></td>
                                            <td> {$envatoCheck->item->published_at}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Updated:</b></td>
                                            <td>{$envatoCheck->item->updated_at}</td>
                                    </tr>
                                    <tr>
                                            <td class='w-30'><b>Rating:</b></td>
                                            <td> {$envatoCheck->item->rating}</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                </div>

            ";

        }

        /*If Purchase Code doesn't exist, no information will be displayed*/
        if (!isset($envatoCheck->item->name)){

            $output .=  "

                <script type='text/javascript'>
                        toastr.error('INVALID PURCHASE CODE');
                </script>
            ";

        }

        return  response()->json([$output]);

    }


    public function ticketlicenseverify(Request $request)
    {
        $apidatatoken = APIData::first();

        $envato_license = decrypt($request->envatopurchase_id);

        $url = "https://api.envato.com/v3/market/author/sale?code=".$envato_license;
        $curl = curl_init($url);

        $personal_api_token = $apidatatoken != null ? $apidatatoken->envatoapitoken : '';

        /*Correct header for the curl extension*/
        $header = array();
        $header[] = 'Authorization: Bearer '.$personal_api_token;
        $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
        $header[] = 'timeout: 20';
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

        /*Connect to the API, and get values from there*/
        $envatoCheck = curl_exec($curl);
        curl_close($curl);

        $envatoCheck = json_decode($envatoCheck);

        $firstone = isset($envatoCheck->error);

        $secondone = null;
        if($firstone == true){
            $personal_api_token = $apidatatoken != null ? $apidatatoken->envatoapitokensecond : '';

            /*Correct header for the curl extension*/
            $header = array();
            $header[] = 'Authorization: Bearer '.$personal_api_token;
            $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
            $header[] = 'timeout: 100';
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

            /*Connect to the API, and get values from there*/
            $envatoCheck = curl_exec($curl);
            curl_close($curl);

            $envatoCheck = json_decode($envatoCheck);

            if(isset($envatoCheck->error)){
                $secondone = true;
            }
        }

        if($secondone == true){
            $personal_api_token = $apidatatoken != null ? $apidatatoken->envatoapitokenthird : '';

            /*Correct header for the curl extension*/
            $header = array();
            $header[] = 'Authorization: Bearer '.$personal_api_token;
            $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
            $header[] = 'timeout: 100';
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

            /*Connect to the API, and get values from there*/
            $envatoCheck = curl_exec($curl);
            curl_close($curl);

            $envatoCheck = json_decode($envatoCheck);
        }

        /*Variable request from the API*/
        $date = new \DateTime(isset($envatoCheck->supported_until) ? $envatoCheck->supported_until : false);
        $support_date = $date->format('Y-m-d H:i:s');
        $sold = new \DateTime(isset($envatoCheck->sold_at) ? $envatoCheck->sold_at : false);
        $sold_at = $sold->format('Y-m-d H:i:s');
        $amount = (isset( $envatoCheck->amount) ? $envatoCheck->amount : false);
        $buyer = (isset( $envatoCheck->buyer) ? $envatoCheck->buyer : false);
        $license = (isset( $envatoCheck->license) ? $envatoCheck->license : false);
        $count = (isset( $envatoCheck->purchase_count) ? $envatoCheck->purchase_count : false);
        $support_amount = (isset( $envatoCheck->support_amount) ? $envatoCheck->support_amount : false);
        $item  = (isset( $envatoCheck->item->previews->icon_preview->icon_url ) ? $envatoCheck->item->previews->icon_preview->icon_url  : false);


        $output = "";
        /*If Purchase code exists, But Purchase ended*/
        if (isset($envatoCheck->item->name) && (date('Y-m-d H:i:s') >= $support_date)){

            $output .=  "
                <table class='table table-striped table-bordered'>
                        <tbody>
                            <tr>
                                <th colspan='2' class='fs-16 fw-semibold'>Client Details</th>
                            </tr>
                            <tr>
                                <td class='w-30'><b>ITEM_ICON :</b></td>
                                <td> <img src='{$item}' class='img-responsive br-7' /></td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>Item Name:</b></td>
                                <td>{$envatoCheck->item->name}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>CLIENT:</b></td>
                                <td>{$buyer}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>AMOUNT:</b></td>
                                <td>{$amount}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>SOLD AT:</b></td>
                                <td> {$sold_at}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>SUPPORT UNTIL:</b></td>
                                <td> {$support_date} <span class='badge badge-danger-dark ms-1'>Support Expired</span></td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>LICENSE:</b></td>
                                <td> {$license}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>COUNT:</b></td>
                                <td> {$count}</td>
                            </tr>

                        </tbody>
                </table>

            ";

        }

        /*If Purchase code exists, display client information*/
        if (isset($envatoCheck->item->name)  && (date('Y-m-d H:i:s') < $support_date)){

            $output .=  "
                <table class='table table-striped table-bordered'>
                        <tbody>
                            <tr>
                                <th colspan='2' class='fs-16 fw-semibold'>Client Details</th>
                            </tr>
                            <tr>
                                <td class='w-30'><b>ITEM_ICON :</b></td>
                                <td> <img src='{$item}' class='img-responsive br-7' /></td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>Item Name:</b></td>
                                <td>{$envatoCheck->item->name}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>CLIENT:</b></td>
                                <td>{$buyer}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>AMOUNT:</b></td>
                                <td>{$amount}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>SOLD AT:</b></td>
                                <td> {$sold_at}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>SUPPORT UNTIL:</b></td>
                                <td> {$support_date}  <span class='badge badge-success ms-1'>Supported</span></td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>LICENSE:</b></td>
                                <td> {$license}</td>
                            </tr>
                            <tr>
                                <td class='w-30'><b>COUNT:</b></td>
                                <td> {$count}</td>
                            </tr>
                        </tbody>
                </table>

            ";

        }

        /*If Purchase Code doesn't exist, no information will be displayed*/
        if (!isset($envatoCheck->item->name)){

            $output .=  "

                <h4>Invalid Purchase Code</h4>
            ";

        }

        return  response()->json(['output' => $output, 'client' => $buyer]);

    }
}
