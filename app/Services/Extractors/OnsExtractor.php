<?php

    namespace App\Services\Extractors;

    use App\Exceptions\Admin\ExtractorImport\Osni\InvalidFeatureException;
    use Exception;
    use Grimzy\LaravelMysqlSpatial\Types\Geometry;
    use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;
    use Grimzy\LaravelMysqlSpatial\Types\Polygon;
    use GuzzleHttp\Exception\GuzzleException;

    class OnsExtractor extends ExtractorAbstract
    {
        public $uris = [
            'info' => 'http://statistics.data.gov.uk/doc/statistical-geography/{id}.json',
            'boundary' => 'http://statistics.data.gov.uk/doc/statistical-geography/{id}/geometry.json',
            // TODO changed E04 to W04 for Wales, needs to change dynamically
            'local_admins' => 'http://statistics.data.gov.uk/area_collection.jsonld?in_collection=http://statistics.data.gov.uk/def/geography/collection/W04&within_area=http://statistics.data.gov.uk/id/statistical-geography/{id}&per_page=1000'
        ];

        /**
         * @param string $id
         * @return array
         */
        public function getInfo(string $id): array
        {
            $info = [
                'ons_success' => false,
                'ons_name' => null,
                'ons_official_name' => null,
                'ons_status' => null,
                'ons_id' => $id
            ];

            if ($response = $this->tryGetJsonResponse('info', $id)) {
                $info['ons_success'] = true;
                $info['ons_status'] = $response[0]['http://statistics.data.gov.uk/def/statistical-geography#status'][0]['@value'] ?? null;
                $info['ons_name'] = $response[0]['http://publishmydata.com/def/ontology/foi/displayName'][0]['@value'] ?? null;
                $info['ons_official_name'] = $response[0]['http://statistics.data.gov.uk/def/statistical-geography#officialname'][0]['@value'] ?? null;
            }

            return $info;
        }

        /**
         * @param string $id
         * @return array
         */
        public function getLocalAdmins(string $id): array
        {
            $response = $this->tryGetResponse('local_admins', $id);

            foreach ($response as $key => $place) {
                $places[$place['http://www.w3.org/2000/01/rdf-schema#label'][0]['@value']] = $place['http://statistics.data.gov.uk/def/statistical-geography#officialname'][0]['@value'];
            }

            return $places ?? [];
        }

        /**
         * @param string $id
         * @return Geometry|null
         */
        public function getBoundary(string $id): ?Geometry
        {
            $response = $this->tryGetJsonResponse('boundary', $id);

            if (!isset($response[0]['http://www.opengis.net/ont/geosparql#asWKT'][0]['@value'])) {
                return null;
            }

            if (strpos($response[0]['http://www.opengis.net/ont/geosparql#asWKT'][0]['@value'], 'POLYGON') === 0) {
                return Polygon::fromWKT($response[0]['http://www.opengis.net/ont/geosparql#asWKT'][0]['@value']);
            }

            if (strpos($response[0]['http://www.opengis.net/ont/geosparql#asWKT'][0]['@value'], 'MULTIPOLYGON') === 0) {
                return MultiPolygon::fromWKT($response[0]['http://www.opengis.net/ont/geosparql#asWKT'][0]['@value']);
            }

            return null;
        }
    }