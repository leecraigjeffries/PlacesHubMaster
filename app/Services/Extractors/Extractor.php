<?php /** @noinspection ALL */

    namespace App\Services\Extractors;

    class Extractor
    {
        /**
         * @var WikiExtractor
         */
        protected $wiki;

        /**
         * @var WikidataExtractor
         */
        protected $wikidata;

        /**
         * @var OsExtractor
         */
        protected $os;

        /**
         * @var OnsExtractor
         */
        protected $ons;

        /**
         * @var OsmExtractor
         */
        protected $osm;

        /**
         * @var OsniExtractor
         */
        protected $osni;

        public function __construct(
            WikiExtractor $wiki,
            WikidataExtractor $wikidata,
            OsExtractor $os,
            OnsExtractor $ons,
            OsmExtractor $osm,
            OsniExtractor $osni
        ) {
            $this->wiki = $wiki;
            $this->wikidata = $wikidata;
            $this->os = $os;
            $this->ons = $ons;
            $this->osm = $osm;
            $this->osni = $osni;
        }


        /**
         * @param $request
         *
         * @return array
         */
        public function getInfoFromRequest($request): array
        {
            $info['title'] = null;
            $info['wikidata_id'] = null;
            $info['ons_id'] = null;
            $info['os_id'] = null;
            $info['osm_id'] = null;
            $info['geonames_id'] = null;
            $info['geonames_id_2'] = null;
            $info['geonames_id_3'] = null;
            $info['geonames_id_4'] = null;

            /**
             * Wikipedia
             */
            if ($request->input('wiki_title')) {
                $info = $this->wiki()->getInfo($request->input('wiki_title')) + $info;
            }

            /**
             * Wikidata
             */
            if ($request->input('wikidata_id') || isset($info['wiki_wikidata_id'])) {
                $info = $this->wikidata()->getInfo($request->input('wikidata_id') ?: $info['wiki_wikidata_id']) + $info;
            }

            /**
             * Office National Statistics from Ordnance Survey
             */
            if ($request->input('os_id') && !$request->input('ons_id')) {
                $info = $this->os()->getInfo($request->input('os_id')) + $info;
            }

            $info['lat'] = $info['wikidata_lat'] ?? $info['wiki_lat'] ?? null;
            $info['lon'] = $info['wikidata_lon'] ?? $info['wiki_lon'] ?? null;
            $info['wikidata_id'] = $info['wikidata_id'] ?? $info['wiki_wikidata_id'] ?? null;

            foreach ([
                         'lat',
                         'lon',
                         'wikidata_id',
                         'os_id',
                         'osm_id',
                         'geo_id',
                         'geo_id_2',
                         'geo_id_3',
                         'geo_id_4',
                         'ons_id',
                         'wiki_title',
                         'ipn_id',
                         'osm_network_type'
                     ] as $input) {
                if ($request->input("{$input}_null")) {
                    $info[$input] = null;
                } else {
                    if ($request->input($input)) {
                        $info[$input] = $request->input($input);
                    }
                }
            }

            return $info;
        }

        /**
         * @return WikiExtractor
         */
        public function wiki(): WikiExtractor
        {
            return $this->wiki;
        }

        /**
         * @param WikiExtractor $wiki
         * @return Extractor
         */
        public function setWiki(WikiExtractor $wiki): self
        {
            $this->wiki = $wiki;

            return $this;
        }

        /**
         * @return WikidataExtractor
         */
        public function wikidata(): WikidataExtractor
        {
            return $this->wikidata;
        }

        /**
         * @param WikidataExtractor $wikidata
         * @return Extractor
         */
        public function setWikidata(WikidataExtractor $wikidata): self
        {
            $this->wikidata = $wikidata;

            return $this;
        }

        /**
         * @return OsExtractor
         */
        public function os(): OsExtractor
        {
            return $this->os;
        }

        /**
         * @param OsExtractor $os
         * @return Extractor
         */
        public function setOs(OsExtractor $os): self
        {
            $this->os = $os;

            return $this;
        }

        /**
         * @return OnsExtractor
         */
        public function ons(): OnsExtractor
        {
            return $this->ons;
        }

        /**
         * @param OnsExtractor $ons
         * @return Extractor
         */
        public function setOns(OnsExtractor $ons): self
        {
            $this->ons = $ons;

            return $this;
        }

        /**
         * @return OsmExtractor
         */
        public function osm(): OsmExtractor
        {
            return $this->osm;
        }

        /**
         * @param OsmExtractor $osm
         * @return Extractor
         */
        public function setOsm(OsmExtractor $osm): self
        {
            $this->osm = $osm;

            return $this;
        }

        /**
         * @return OsniExtractor
         */
        public function osni(): OsniExtractor
        {
            return $this->osni;
        }

        /**
         * @param OsniExtractor $osni
         * @return Extractor
         */
        public function setOsni(OsniExtractor $osni): self
        {
            $this->osni = $osni;

            return $this;
        }


    }