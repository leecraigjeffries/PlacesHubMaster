<?php

    namespace App\Services\Extractors;

    use Carbon\Carbon;
    use GuzzleHttp\Client;

    class WikidataExtractor extends ExtractorAbstract
    {
        protected $uris = [
            'info' => 'https://www.wikidata.org/wiki/Special:EntityData/{id}.json'
        ];

        public function getInfo(string $id): array
        {
            $info = [
                'wikidata_id' => $id,
                'wikidata_lat' => null,
                'wikidata_lon' => null,
                'wikidata_geo_id' => null,
                'wikidata_osm_id' => null,
                'wikidata_iso3166_2' => null,
                'wikidata_type_1' => null,
                'wikidata_type_2' => null,
                'wikidata_type_3' => null,
                'wikidata_type_4' => null,
                'wikidata_type_5' => null,
                'wikidata_type_6' => null,
                'wikidata_type_7' => null,
                'wikidata_type_8' => null,
                'wikidata_type_9' => null,
                'wikidata_type_10' => null,
                'wikidata_os_id' => null,
                'wikidata_ons_id' => null,
                'wikidata_wiki_title' => null,
                'wikidata_success' => false,
                'wikidata_retrieved_at' => Carbon::now()->toDateTimeString()
            ];

            if ($response = $this->tryGetJsonResponse('info', $id)) {

                $info['wikidata_success'] = true;

                $claims = $response['entities'][$id]['claims'] ?? null;

                if (isset($claims['P625'][0]['mainsnak']['datavalue']['value'])) {
                    $lat_lon = $claims['P625'][0]['mainsnak']['datavalue']['value'];

                    $info['wikidata_lat'] = number_format($lat_lon['latitude'], 6);
                    $info['wikidata_lon'] = number_format($lat_lon['longitude'], 6);
                }

                foreach($claims['P31'] ?? [] as $key => $type){
                    $type_id = $type['mainsnak']['datavalue']['value']['id'];
                    if($type_response = $this->tryGetJsonResponse('info', $type_id)){
                        $key++;
                        $info["wikidata_type_{$key}"] = $type_response['entities'][$type_id]['labels']['en']['value'] ?? null;
                    }
                }

                $info['wikidata_geo_id'] = $claims['P1566'][0]['mainsnak']['datavalue']['value'] ?? null;
                $info['wikidata_osm_id'] = $claims['P402'][0]['mainsnak']['datavalue']['value'] ?? null;
                $info['wikidata_iso3166_2'] = $claims['P300'][0]['mainsnak']['datavalue']['value'] ?? null;
                $info['wikidata_os_id'] = $claims['P3120'][0]['mainsnak']['datavalue']['value'] ?? null;
                $info['wikidata_ons_id'] = $claims['P836'][0]['mainsnak']['datavalue']['value'] ?? null;
                $info['wikidata_wiki_title'] = $response['entities'][$id]['labels']['en']['value'] ?? null;
            }

            return $info;
        }
    }