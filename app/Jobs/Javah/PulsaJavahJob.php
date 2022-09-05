<?php

namespace App\Jobs\Javah;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\Callback\Javah\CPulsaJavahJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PulsaJavahJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $payload )
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "\n" . "trx running";

        $url = 'https://javah2h.com/api/connect/';
        $user_id = config('app.java_config.user_id');
        $key = config('app.java_config.key');
        $secret = config('app.java_config.secret');

        $header = [
            "h2h-userid: $user_id",
            "h2h-key: $key", // lihat hasil autogenerate di member area
            "h2h-secret: $secret", // lihat hasil autogenerate di member area
        ];
        
        // $data = array(
        //     'inquiry' => 'I', // konstan
        //     'code' => $json_data->code, // kode produk
        //     'phone' => $json_data->phone, // nohp pembeli
        //     'trxid_api' => $json_data->trxid_api, // Trxid / Reffid dari sisi client
        //     'no' => $json_data->no, // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
        // );
        $data = ['inquiry' => 'I'];
        $data = array_merge($data,$this->payload);
        echo "\n" . json_encode($data);

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
            $data_callback = [
                'status' => 'failed',
                'note' => 'Failed connect api javah',
                'trx_number' => $data['trxid_api'],
                'response' => $response
            ];
        } else {
            $result = json_decode($response);
            if ($result->result == 'failed') {
                $data_callback = [
                    'status' => 'failed',
                    'note' => $result->message,
                    'trx_number' => $data['trxid_api'],
                    'response' => $response
                ];
            } else {
                $data_callback = [
                    'status' => 'success',
                    'trx_number' => $data['trxid_api'],
                    'response' => $response
                ];
            }
        }

        CPulsaJavahJob::dispatch($data_callback);
    }
}
