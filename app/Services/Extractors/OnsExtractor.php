<?php

    namespace App\Services\Extractors;

    class OnsExtractor extends ExtractorAbstract
    {
        public $uris = [
            'statistics' => 'http://statistics.data.gov.uk/doc/statistical-geography/',
            'boundaries' => 'http://statistics.data.gov.uk/boundaries/',
            // TODO changed E04 to W04 for Wales, needs to change dynamically
            'local_admins' => 'http://statistics.data.gov.uk/area_collection.jsonld?in_collection=http://statistics.data.gov.uk/def/geography/collection/W04&within_area=http://statistics.data.gov.uk/id/statistical-geography/{id}&per_page=1000'
        ];

        public function getLocalAdmins(string $id): array
        {
            $response = $this->tryGetResponse('local_admins', $id);

            foreach($response as $key => $place){
                $places[$place['http://www.w3.org/2000/01/rdf-schema#label'][0]['@value']] = $place['http://statistics.data.gov.uk/def/statistical-geography#officialname'][0]['@value'];
            }

            return $places ?? [];
        }
    }