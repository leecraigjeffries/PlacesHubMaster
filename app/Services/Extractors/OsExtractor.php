<?php

    namespace App\Services\Extractors;

    class OsExtractor extends ExtractorAbstract
    {
        protected $uris = [
            'info_coords' => 'http://data.ordnancesurvey.co.uk/doc/{id}.json',
        ];

        public function getLocalAdmins(string $id): array
        {
            $response = $this->tryGetResponse('info_coords', $id);

            foreach ($response["http://data.ordnancesurvey.co.uk/id/{$id}"]['http://data.ordnancesurvey.co.uk/ontology/admingeo/parish'] ?? [] as $localAdmin) {
                preg_match('#[^\/]*$#', $localAdmin['value'], $localAdminId, PREG_UNMATCHED_AS_NULL);

                $places[$localAdminId[0]] = $response["http://data.ordnancesurvey.co.uk/id/{$localAdminId[0]}"]['http://www.w3.org/2000/01/rdf-schema#label'][0]['value'];
            }

            return $places ?? [];
        }

        public function getInfo(string $id, bool $all = false): array
        {
            $data = $this->tryGetResponse('info_coords', $id);
            $data = $data["http://data.ordnancesurvey.co.uk/id/{$id}"] ?? [];

            $os_data['os_ons_id'] = $data['http://data.ordnancesurvey.co.uk/ontology/admingeo/gssCode'][0]['value'] ?? null;
            $os_data['os_lat'] = $data['http://www.w3.org/2003/01/geo/wgs84_pos#lat'][0]['value'] ?? null;
            $os_data['os_lon'] = $data['http://www.w3.org/2003/01/geo/wgs84_pos#long'][0]['value'] ?? null;

            if ($all) {
                $os_data['os_type'] = $data['http://data.ordnancesurvey.co.uk/ontology/admingeo/hasAreaCode'][0]['value'] ?? null;
                $os_data['os_name'] = $data['http://www.w3.org/2000/01/rdf-schema#label'][0]['value'] ?? null;
                $os_data['os_name_2'] = $data['http://www.w3.org/2004/02/skos/core#altLabel'][0]['value'] ?? null;
                preg_match(
                    '#[^\/]*$#',
                    $data['http://data.ordnancesurvey.co.uk/ontology/admingeo/inCounty'][0]['value'] ?? null,
                    $county_os_id, PREG_UNMATCHED_AS_NULL
                );
                $os_data['os_county_os_id'] = $county_os_id[0] ?: null;
                preg_match(
                    '#[^\/]*$#',
                    $data['http://data.ordnancesurvey.co.uk/ontology/admingeo/inDistrict'][0]['value'] ?? null,
                    $district_os_id, PREG_UNMATCHED_AS_NULL
                );

                $os_data['os_district_os_id'] = $district_os_id[0] ?: null;
            }

            return $os_data;
        }
    }
