<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DigiflazzController extends Controller
{
    function isiPulsa(Request $request) {
        $json_data = json_decode($request->getContent());
        $username = config('app.digiflazz_config.username');
        $api_key = config('app.digiflazz_config.apikey');
        $sign = md5($username.$api_key.$json_data->ref_id);
        $data = [
            "username" => $username,
            "commands" => "topup",
            "ref_id" => $json_data->ref_id,
            "hp" => $json_data->hp,
            "pulsa_code" => $json_data->pulsa_code,
            "sign" => $sign
        ];
        $curl = curl_init();

        curl_setopt_array($curl, array(
        // CURLOPT_URL => 'https://api.digiflazz.com/v1/transaction',
        CURLOPT_URL => 'https://silvanix.free.beeceptor.com/digiflazz/isi-pulsa',
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
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code != 200) {
            return response()->json([
                'message' => 'failed',
                'response' => $response,
            ], $http_code);
        } else {
            return response()->json([
                'message' => 'success',
                'response' => $response,
            ], $http_code);
        }
    }
}
