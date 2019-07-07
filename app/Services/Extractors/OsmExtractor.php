<?php

    namespace App\Services\Extractors;

    class OsmExtractor extends ExtractorAbstract
    {
        protected $uris = [
            'relation' => 'https://www.openstreetmap.org/api/0.6/relation/{id}',
            'node' => 'https://www.openstreetmap.org/api/0.6/node/{id}',
            'way' => 'https://www.openstreetmap.org/api/0.6/way/{id}'
        ];

        public function getInfo(string $id, bool $all = false): array
        {
            $info = [
                'osm_type' => null,
                'osm_wikidata_id' => null,
                'osm_wiki_title' => null,
                'osm_ons_id' => null,
                'osm_iso3166_2' => null,
                'osm_ac_lat' => null,
                'osm_ac_lon' => null,
                'osm_lat' => null,
                'osm_lon' => null,
                'osm_place' => null,
                'osm_natural' => null
            ];

            if ($response = $this->tryGetXmlResponse('relation', $id)) {

                foreach ($response['relation']['tag'] as $attr) {
                    $attr = array_pop($attr);

                    $props = [
                        'type' => 'osm_type',
                        'wikidata' => 'osm_wikidata_id',
                        'wikipedia' => 'osm_wiki_title',
                        'ISO3166-2' => 'osm_iso3166_2',
                        'place' => 'osm_place',
                        'natural' => 'natural'
                    ];

                    if ($attr['k'] === 'ref:gss') {
                        $info['osm_ons_id'] = substr($attr['v'], 0, 9);
                    }

                    foreach ($props as $prop => $col) {
                        if ($attr['k'] === $prop) {
                            $info[$col] = $attr['v'];
                        }
                    }
                }

                foreach ($response['relation']['member'] as $member) {
                    $member = array_pop($member);

                    if (isset($member['role']) && $member['role'] === 'admin_centre') {
                        $node_response = $this->tryGetXmlResponse('node', $member['ref']);
                        $info['osm_ac_lat'] = $node_response['node']['@attributes']['lat'];
                        $info['osm_ac_lon'] = $node_response['node']['@attributes']['lon'];
                    }
                }
            }

            if ($response = $this->tryGetXmlResponse('node', $id)) {
                foreach ($response['node']['tag'] ?? [] as $attr) {
                    $attr = array_pop($attr);

                    if ($attr['k'] ?? null === 'wikidata') {
                        $info['osm_wikidata_id'] = $attr['v'];
                    }

                    if ($attr['k'] ?? null === 'place') {
                        $info['osm_place'] = $attr['v'];
                    }

                    if ($attr['k'] ?? null === 'natural') {
                        $info['osm_natural'] = $attr['v'];
                    }

                    if ($attr['k'] ?? null === 'type') {
                        $info['osm_type'] = $attr['v'];
                    }

                    if ($attr['k'] ?? null === 'wikipedia' and strpos($attr['v'], 'en:') !== false) {
                        preg_match('#^en:(.*)#is', $attr['v'], $wikiTitle);
                        $info['osm_wiki_title'] = (string)$wikiTitle[1];
                    }
                }

                $info['osm_lat'] = $response['node']['@attributes']['lat'] ?? null;
                $info['osm_lon'] = $response['node']['@attributes']['lon'] ?? null;
            }

            return $info ?? [];
        }
    }
