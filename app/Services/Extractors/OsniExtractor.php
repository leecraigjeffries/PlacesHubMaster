<?php

    namespace App\Services\Extractors;

    class OsniExtractor extends ExtractorAbstract
    {
        protected $uris = [
            'district_boundaries' => 'http://osni-spatial-ni.opendata.arcgis.com/datasets/a55726475f1b460c927d1816ffde6c72_2.geojson',
            'country_boundary' => 'http://osni-spatial-ni.opendata.arcgis.com/datasets/d9dfdaf77847401e81efc9471dcd09e1_0.geojson'
        ];

        public function getDistrictBoundaries(): array
        {
            return $this->tryGetJsonResponse('district_boundaries');
        }

        public function getCountryBoundary(): array
        {
            return $this->tryGetJsonResponse('country_boundary');
        }
    }