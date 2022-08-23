<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DigiflazzController extends Controller
{
    function isiPulsa(Request $request) {
        $json_data = json_decode($request->getContent());
        $data = [
            "username" => $json_data->username,
            "commands" => "topup",
            "ref_id" => $json_data->ref_id,
            "hp" => $json_data->hp,
            "pulsa_code" => $json_data->pulsa_code,
            "sign" => $json_data->sign
        ];
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.digiflazz.com/v1/transaction',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
