<?php

    namespace App\Services\ExtractorImport;

    use App\Exceptions\Admin\ExtractorImport\Osni\InvalidFeatureException;
    use App\Models\Place;
    use App\Services\Extractors\OnsExtractor;
    use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;
    use Grimzy\LaravelMysqlSpatial\Types\Polygon;

    class OnsExtractorImport
    {
        protected $limit = 0;

        protected $count = 0;

        /**
         * @var OnsExtractor
         */
        private $extractor;

        /**
         * @var Place
         */
        private $place;

        public function __construct(OnsExtractor $extractor, Place $place)
        {
            $this->place = $place;
            $this->extractor = $extractor;
        }

        /**
         * @param bool $withNulls
         * @param int $limit
         * @return bool
         * @throws InvalidFeatureException
         */
        public function updateBoundaries(bool $withNulls = true): bool
        {
            app('debugbar')->disable();

            $places = $this->place
                ->whereNotNull('ons_id');

            // TODO use when()
            if ($withNulls === true) {
                $places = $places->whereNull('polygon')
                    ->whereNull('multipolygon');
            }

            foreach ($places->get() as $place) {
                if ($this->limit === 0 || $this->count < $this->limit) {

                    $info = $this->extractor->getInfo($place->ons_id);

                    if ($info['ons_status'] === 'live' && $boundary = $this->extractor->getBoundary($place->ons_id)) {

                        $col = null;
                        if ($boundary instanceof Polygon) {
                            $col = 'polygon';
                        } elseif ($boundary instanceof MultiPolygon) {
                            $col = 'multipolygon';
                        }

                        if ($col === null) {
                            // TODO make an exception
                            throw new InvalidFeatureException($place->ons_id . ' has an invalid feature');
                        }

                        $place->update([$col => $boundary]);
                        $this->count++;
                    }
                }
            }

            return true;
        }
    }