<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle()
    {
        $url = "https://donnees.roulez-eco.fr/opendata/instantane";
        $zip_file = "folder/downloadfile.zip";

        $zip_resource = fopen($zip_file, "w");

        $ch_start = curl_init();
        curl_setopt($ch_start, CURLOPT_URL, $url);
        curl_setopt($ch_start, CURLOPT_FAILONERROR, true);
        curl_setopt($ch_start, CURLOPT_HEADER, 0);
        curl_setopt($ch_start, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch_start, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch_start, CURLOPT_BINARYTRANSFER,true);
        curl_setopt($ch_start, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch_start, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch_start, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch_start, CURLOPT_FILE, $zip_resource);
        $page = curl_exec($ch_start);
        if(!$page)
        {
            echo "Error :- ".curl_error($ch_start);
        }
        curl_close($ch_start);

        $zip = new ZipArchive;
        $extractPath = "Download File Path";
        if($zip->open($zip_file) != "true")
        {
            echo "Error :- Unable to open the Zip File";
        }

        $zip->extractTo($extractPath);
        $zip->close();
    }
}
