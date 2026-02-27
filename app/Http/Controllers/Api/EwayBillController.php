<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiBaseController;

use Illuminate\Http\Request;

class EwayBillController extends ApiBaseController
{
    public function generateEWayBill(Request $request)
    {
                    

            $curl = curl_init();
            $clientId       = env('EWAYBILL_CLIENT_ID');
                        $clientSecret   = env('EWAYBILL_CLIENT_SECRET');
                        $gstin          = env('CLIENT_GSTIN');  
                        $ip             = env('CLIENT_IP');   
            $url = env('EWAY_BILL_AUTH_URL') ."email=".env('GST_CLIENT_EMAIL')."&username=".env('GST_EAY_BILL_USER_NAME')."&password=".env('GST_EAY_BILL_PASSWORD');
            $header = array( "accept: application/json", "client_id: $clientId", "client_secret: $clientSecret","gstin: $gstin" ,"ip_address: $ip");
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $header
            ));

            $response = json_decode(curl_exec($curl), true);
           
            curl_close($curl);
            if( $response && isset($response['status_cd']) && $response['status_cd'] == 1){
                $this->getEwaybillDetails($request, $header);
            }
           


    }
    public function getEwaybillDetails(Request $request, $header)
        {
           //print_r($request->all());
           echo $url = env('EWAYBILL_API_URL') ."?email=".env('GST_CLIENT_EMAIL');
            array_push($header,"content-type: application/json");
            $curl = curl_init();
            echo json_encode($request->all());
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,//'https://api.mastergst.com/ewaybillapi/v1.03/ewayapi/genewaybill?email=namadevaparivara%40gmail.com',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($request->all()),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'ip_address: 103.181.148.69',
                'client_id: 4e04c0b5-4ec3-4d7f-a1e5-40af065af051',
                'client_secret: 4279ec32-e3f9-489e-ad6e-96aea98df511',
                'gstin: 05AAACH6188F1ZM'
            ),
            ));

            $response = curl_exec($curl);
echo $response;
            curl_close($curl);
            

    
      }
}
