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
            DB::connection('mysql')->table('transaction')
            ->where('ref_id', $json_data->ref_id)
            ->update([
                'status' => 'cancel',
                'response_server' => $response
            ]);
        } else {
            $result = json_decode($response);
            if ($result->data->status == 0) {
                DB::connection('mysql')->table('transaction')
                ->insert([
                    'user_id' => '3',
                    'phone' => $result->data->hp,
                    'ref_id' => $json_data->ref_id,
                    'product_code' => $json_data->pulsa_code,
                    'tipe' => 'pulsa',
                    'price' => $result->data->price,
                    'status' => 'pending',
                    'supplier_id' => '2',
                    'response_server' => $response,
                    'server' => '2',
                ]);
            } else if ( $result->data->status == 1 ) {
                DB::connection('mysql')->table('transaction')
                ->where('ref_id', $json_data->ref_id)
                ->update([
                    'status' => 'paid',
                    'response_server' => $response
                ]);
            } else {
                DB::connection('mysql')->table('transaction')
                ->where('ref_id', $json_data->ref_id)
                ->update([
                    'status' => 'cancel',
                    'response_server' => $response
                ]);
            }
        }
    }
}
