<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Javah\PulsaJavahJob;

class TesCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trx:pulsa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $payload = [
        //     "code" => "67JHJ",
        //     "phone" => "90785745645",
        //     "trxid_api" => "0897jh",
        //     "no" => "1"
        // ];
        // PulsaJavahJob::dispatch($payload);
        
    }
}
