<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JavaController extends Controller
{
    function isiPulsa( Request $request )
    {
        $config = $request->header();
        $url = 'https://javah2h.com/api/connect/';
        $header = array(
            'h2h-userid: '. $config['h2h-userid'][0],
            'h2h-key: '. $config['h2h-key'][0], // lihat hasil autogenerate di member area
            'h2h-secret: '. $config['h2h-secret'][0], // lihat hasil autogenerate di member area
        );
        
        $json_data = json_decode($request->getContent());
        $data = array(
            'inquiry' => $json_data->inquiry, // konstan
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
        $result = curl_exec($ch);

        echo $result;

        // {
        //     "result": "success",
        //     "message": "S5 082228988857 Akan diproses"
        // }
    }
}
