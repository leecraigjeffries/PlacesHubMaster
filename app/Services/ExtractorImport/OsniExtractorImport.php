<?php

    namespace App\Services\ExtractorImport;

    use App\Exceptions\Admin\ExtractorImport\Osni\InvalidFeatureException;
    use App\Models\Place;
    use App\Services\Extractors\OsniExtractor;
    use Exception;
    use Grimzy\LaravelMysqlSpatial\Types\Geometry;
    use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;
    use Grimzy\LaravelMysqlSpatial\Types\Polygon;

    class OsniExtractorImport
    {
        /**
         * @var OsniExtractor
         */
        private $extractor;

        /**
         * @var Place
         */
        private $place;

        public function __construct(OsniExtractor $extractor, Place $place)
        {
            $this->place = $place;
            $this->extractor = $extractor;
        }

        /**
         * Import.
         *
         * @return bool
         * @throws Exception
         */
        public function import(): bool
        {
            app('debugbar')->disable();

            return !(!$this->getDistrictBoundaries() || !$this->getCountryBoundary());
        }

        /**
         * @return bool
         * @throws InvalidFeatureException
         */
        public function getDistrictBoundaries(): bool
        {
            $response = $this->extractor->getDistrictBoundaries();

            if (!isset($response['features'])) {
                return false;
            }

            foreach ($response['features'] as $feature) {
                $boundary = Geometry::fromJson(json_encode($feature));

                if ($boundary instanceof Polygon) {
                    $col = 'polygon';
                } elseif ($boundary instanceof MultiPolygon) {
                    $col = 'multipolygon';
                }

                if (!isset($col)) {
                    throw new InvalidFeatureException('$col not set, feature is an instance of ' . get_class($boundary));
                }

                // TODO: this updates instead of just getting
                $this->place
                    ->where('ons_id', $feature['properties']['LGDCode'])
                    ->update([$col => $boundary]);
            }

            return true;
        }


        /**
         * @return bool
         * @throws InvalidFeatureException
         */
        public function getCountryBoundary(): bool
        {
            $response = $this->extractor->getCountryBoundary();

            if (!isset($response['features'])) {
                return false;
            }

            foreach ($response['features'] as $feature) {
                $boundary = Geometry::fromJson(json_encode($feature));

                if ($boundary instanceof Polygon) {
                    $col = 'polygon';
                } elseif ($boundary instanceof MultiPolygon) {
                    $col = 'multipolygon';
                }

                if (!isset($col)) {
                    throw new InvalidFeatureException('$col not set, feature is an instance of ' . get_class($boundary));
                }

                $this->place
                    ->where('ons_id', 'N92000002')
                    ->update([$col => $boundary]);
            }

            return true;
        }
    }