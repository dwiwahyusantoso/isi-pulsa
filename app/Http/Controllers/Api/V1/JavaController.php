<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JavaController extends Controller
{
    function isiPulsa( Request $request )
    {
        // $url = 'https://javah2h.com/api/connect/';
        $user_id = config('app.java_config.user_id');
        $key = config('app.java_config.key');
        $secret = config('app.java_config.secret');

        $header = [
            "h2h-userid: $user_id",
            "h2h-key: $key", // lihat hasil autogenerate di member area
            "h2h-secret: $secret", // lihat hasil autogenerate di member area
        ];
        
        $json_data = json_decode($request->getContent());
        $data = array(
            'inquiry' => 'I', // konstan
            'code' => $json_data->code, // kode produk
            'phone' => $json_data->phone, // nohp pembeli
            'trxid_api' => $json_data->trxid_api, // Trxid / Reffid dari sisi client
            'no' => $json_data->no, // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

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
