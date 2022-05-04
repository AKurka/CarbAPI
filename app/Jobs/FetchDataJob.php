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
use Imtigger\LaravelJobStatus\Trackable;
use ZipArchive;

class FetchDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    public function __construct()
    {
        //$this->prepareStatus();
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

            logger($array);

            $i = 0;
            //$this->setProgressMax(); Récupérer la taille du tableau
            foreach ($array['pdv'] as $item) {
                $new_station = Station::updateOrCreate(['station_id' => $item['@attributes']['id']],
                    [
                        'station_id' => $item['@attributes']['id'],
                        'latitude' => $item['@attributes']['latitude'],
                        'longitude' => $item['@attributes']['longitude'],
                        'pc' => $item['@attributes']['cp'],
                        'address' => $item['adresse'],
                        'city' => $item['ville'],
                        'services' => $item['services']['service'] ?? null
                    ]
                );

                Hours::updateOrCreate(['id_station' => $new_station->id],
                    [
                        'id_station' => $new_station->id,
                        'auto' => $item['horaires']['@attributes']['automate-24-24'] ?? null,
                        'monday_open' => $item['horaires']['jour'][0]['horaire']['@attributes']['ouverture'] ?? null,
                        'monday_close' => $item['horaires']['jour'][0]['horaire']['@attributes']['fermeture'] ?? null,
                        'tuesday_open' => $item['horaires']['jour'][1]['horaire']['@attributes']['ouverture'] ?? null,
                        'tuesday_close' => $item['horaires']['jour'][1]['horaire']['@attributes']['fermeture'] ?? null,
                        'wednesday_open' => $item['horaires']['jour'][2]['horaire']['@attributes']['ouverture'] ?? null,
                        'wednesday_close' => $item['horaires']['jour'][2]['horaire']['@attributes']['fermeture'] ?? null,
                        'thursday_open' => $item['horaires']['jour'][3]['horaire']['@attributes']['ouverture'] ?? null,
                        'thursday_close' => $item['horaires']['jour'][3]['horaire']['@attributes']['fermeture'] ?? null,
                        'friday_open' => $item['horaires']['jour'][4]['horaire']['@attributes']['ouverture'] ?? null,
                        'friday_close' => $item['horaires']['jour'][4]['horaire']['@attributes']['fermeture'] ?? null,
                        'saturday_open' => $item['horaires']['jour'][5]['horaire']['@attributes']['ouverture'] ?? null,
                        'saturday_close' => $item['horaires']['jour'][5]['horaire']['@attributes']['fermeture'] ?? null,
                        'sunday_open' => $item['horaires']['jour'][6]['horaire']['@attributes']['ouverture'] ?? null,
                        'sunday_close' => $item['horaires']['jour'][6]['horaire']['@attributes']['fermeture'] ?? null
                    ]
                );



                if(array_key_exists('prix', $item)){
                    foreach ($item['prix'] as $price){
                        if(array_key_exists('@attributes', $price)){
                        Price::updateOrCreate(['id_station' => $new_station->id, 'id_carb' => $price['@attributes']['id']],
                            [
                                'id_station' => $new_station->id,
                                'id_carb' => $price['@attributes']['id'] ?? null,
                                'price' => $price['@attributes']['valeur'] ?? null,
                                'name' => $price['@attributes']['nom'] ?? null,
                            ]);
                        };
                    }
                }


                //$this->setProgressNow($i);
                $i++;
            }
        } else {
            logger('Erreur de chargement du fichier .zip lors de la mise à jour des données');
        }

        //$this->setOutput(['total' => taille du tableau 'other' => 'parameter']);
    }
}
