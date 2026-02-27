<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Unit\IndexRequest;
use App\Http\Requests\Api\Unit\StoreRequest;
use App\Http\Requests\Api\Unit\UpdateRequest;
use App\Http\Requests\Api\Unit\DeleteRequest;
use App\Models\Product;
use App\Models\Unit;
use Examyou\RestAPI\Exceptions\ApiException;
use Illuminate\Http\Request;
class GstController extends ApiBaseController
{


    public function getGstDetails(Request $request)
        {
            $sample ='{
                "data": {
                    "stjCd": "KA139",
                    "stj": "LGSTO 223- Shivamogga",
                    "lgnm": "DigitalCloudies MANAGEMENT SOLUTIONS (OPC) PRIVATE LIMITED",
                    "dty": "Regular",
                    "adadr": [],
                    "cxdt": "",
                    "gstin": "29ABDCS6580Q1ZD",
                    "nba": [
                        "Supplier of Services"
                    ],
                    "lstupdt": "25/03/2021",
                    "rgdt": "04/06/2020",
                    "ctb": "Private Limited Company",
                    "pradr": {
                        "addr": {
                            "bnm": "SWAMYVIVEKANANDA LAYOUT",
                            "st": "3RD MAIN ROAD PRAKRUTHI NILAYA,",
                            "loc": "SHIVAMOGGA",
                            "bno": "A78,A BLOCK",
                            "dst": "Shivamogga",
                            "lt": "",
                            "locality": "",
                            "pncd": "577205",
                            "landMark": "",
                            "stcd": "Karnataka",
                            "geocodelvl": "NA",
                            "flno": ",",
                            "lg": ""
                        },
                        "ntr": "Supplier of Services"
                    },
                    "tradeNam": "DigitalCloudies MANAGEMENT SOLUTIONS (OPC) PRIVATE LIMITED",
                    "sts": "Active",
                    "ctjCd": "YY0701",
                    "ctj": "SHIVAMOGGA NORTH RANGE",
                    "einvoiceStatus": "No"
                },
                "status_cd": "1",
                "status_desc": "Public API Search Taxpayer Success"
            }';
            $url = env('GST_API_URL') ."email=".env('GST_CLIENT_EMAIL')."&gstin=".$request->gst;
    
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $clientId       = env('GST_CLIENT_ID');
            $clientSecret   = env('GST_CLIENT_SECRET');
            $headers = array( "accept: */*", "client_id: $clientId", "client_secret: $clientSecret" );
       
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            
            $resp = curl_exec($curl);
            if( env('GST_SAND_BOX_MODE')==true){
                $resp =$sample;
            }
            $repsonse = json_decode($resp, true); 
          /*echo '<pre>';
          print_r($repsonse);*/
          echo json_encode($repsonse );
    
      }
	
        
}
