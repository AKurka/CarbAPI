<?php

namespace App\Jobs;

use App\Models\Hours;
use App\Models\Price;
use App\Models\Station;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZipArchive;

class FetchDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle()
    {
        file_put_contents('tmp.zip', file_get_contents('https://donnees.roulez-eco.fr/opendata/instantane'));

        $zip = new ZipArchive;

        $res = $zip->open('tmp.zip');

        if ($res === TRUE) {
            $xmlFile = $zip->getFromIndex(0);
            $zip->close();
            $xml = simplexml_load_string($xmlFile);
            $json = json_encode($xml);
            $array = json_decode($json, true);
            foreach ($array['pdv'] as $item) {
                $new_station = Station::updateOrCreate(['station_id' => $item['@attributes']['id']],
                    [
                        ['station_id' => $item['@attributes']['id']],
                        ['latitude' => $item['@attributes']['latitude']],
                        ['longitude' => $item['@attributes']['longitude']],
                        ['pc' => $item['@attributes']['cp']],
                        ['address' => $item['adresse']],
                        ['city' => $item['ville']],
                        ['services' => $item['services']['service']],
                    ]
                );

                $new_hours = Hours::updateOrCreate(['id_station' => $new_station->id],
                    [
                        ['id_station' => $new_station->id],
                        ['auto' => $item['horaires']['@attributes']['automate-24-24']],
                        ['monday_open' => $item['horaires']['jour'][0]['horaire']['@attributes']['ouverture']],
                        ['monday_close' => $item['horaires']['jour'][0]['horaire']['@attributes']['fermeture']],
                        ['tuesday_open' => $item['horaires']['jour'][1]['horaire']['@attributes']['ouverture']],
                        ['tuesday_close' => $item['horaires']['jour'][1]['horaire']['@attributes']['fermeture']],
                        ['wednesday_open' => $item['horaires']['jour'][2]['horaire']['@attributes']['ouverture']],
                        ['wednesday_close' => $item['horaires']['jour'][2]['horaire']['@attributes']['fermeture']],
                        ['thursday_open' => $item['horaires']['jour'][3]['horaire']['@attributes']['ouverture']],
                        ['thursday_close' => $item['horaires']['jour'][3]['horaire']['@attributes']['fermeture']],
                        ['friday_open' => $item['horaires']['jour'][4]['horaire']['@attributes']['ouverture']],
                        ['friday_close' => $item['horaires']['jour'][4]['horaire']['@attributes']['fermeture']],
                        ['saturday_open' => $item['horaires']['jour'][5]['horaire']['@attributes']['ouverture']],
                        ['saturday_close' => $item['horaires']['jour'][5]['horaire']['@attributes']['fermeture']],
                        ['sunday_open' => $item['horaires']['jour'][6]['horaire']['@attributes']['ouverture']],
                        ['sunday_close' => $item['horaires']['jour'][6]['horaire']['@attributes']['fermeture']]
                    ]
                );

                $new_prices = Price::updateOrCreate(['id_station' => $new_station->id],
                    [
                        ['id_station' => $new_station->id],
                    ]
                );
            }
        } else {
            logger('Erreur de zip');
        }
    }
}
