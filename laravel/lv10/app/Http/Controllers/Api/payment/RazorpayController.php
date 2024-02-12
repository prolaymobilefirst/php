<?php

namespace App\Http\Controllers\Api\payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RazorpayController extends Controller
{

    private $auth_key='';

    function __construct(){
        $this->auth_key=base64_encode(env('RAZORPAY_KEY') . ':' . env('RAZORPAY_SECRET'));
    }
    /*
    *
    *   {
          "amount": 1000000,
          "currency": "INR",
          "receipt": "Receipt no. 1",
          "notes": {
            "notes_key_1": "Tea, Earl Grey, Hot",
            "notes_key_2": "Tea, Earl Grey… decaf."
          }
        }
    *
    *
    *
    *
    *
    */
    
    public function onCreateOrder(Request $request){
        $data=[];
        $error=false;
        $code=Response::HTTP_OK;
        $status_code=Response::HTTP_OK;
        $msgs='';

        try{
            $url=env('RAZORPAY_URL').'/orders';
            $amount=$request->input('amount');
            $currency=$request->input('currency');
            $reciept_no=$request->input('reciept_no');
            $notes=$request->input('notes');

            //$payload = json_encode(['amount' => $amount,'currency'=>$currency,'reciept_no'=>$reciept_no,'notes'=>$notes]);

            $payload='{
              "amount": "$amount",
              "currency": "{$currency}",
              "receipt": "{$reciept_no}",
              "notes": {
                "notes_key_1": "Tea, Earl Grey, Hot",
                "notes_key_2": "Tea, Earl Grey… decaf."
              }
            }';

            //print_obj($data);die;

            // $headers = [
            //     'Content-Type' => 'application/json',
            //     'Authorization' => "Basic ".$this->auth_key
            // ];


            $params=array(
                "url"=>$url,
                "headers"=>array("Content-Type:application/json;"),
                "username"=>env('RAZORPAY_KEY'),
                "password"=>env('RAZORPAY_SECRET'),
                "data"=>$payload
            );

            //print_obj($headers);die;

            $response = curl_auth_post($params);

            print_obj($response);


        } catch (\Exception $e) {
            $error=true;
            $code=Response::HTTP_INTERNAL_SERVER_ERROR;
            $msgs=$e->getMessage();
            $status_code=Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'error' => $error,
            'code' => $code,
            'message' => $msgs,
            'data' => $data
        ], $status_code);
    }


}
