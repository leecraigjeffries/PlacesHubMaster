<?php

    namespace App\Services\Extractors;

    use Carbon\Carbon;
    use GuzzleHttp\Client;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;

    class WikiExtractor extends ExtractorAbstract
    {
        protected $uris = [
            'coords' => 'https://en.wikipedia.org/w/api.php?action=query&formatversion=2&format=json&prop=coordinates&titles={id}',
            'intro' => 'https://en.wikipedia.org/w/api.php?action=query&formatversion=2&format=json&prop=extracts&exintro=1&exlimit=5&explaintext=1&titles={id}',
            'unparsed' => 'https://en.wikipedia.org/w/api.php?action=query&formatversion=2&format=json&export&titles={id}',
            'parsed' => 'https://en.wikipedia.org/w/api.php?action=parse&formatversion=2&format=json&section=0&uselang=en&prop=text&page={id}',
            'info' => 'https://en.wikipedia.org/w/api.php?action=query&formatversion=2&format=json&prop=pageprops&ppprop=wikibase_item&titles={id}',
            'info_coords' => 'https://en.wikipedia.org/w/api.php?action=query&formatversion=2&format=json&prop=pageprops|coordinates&ppprop=wikibase_item&titles={id}',
            'category' => 'https://en.wikipedia.org/w/api.php?action=query&formatversion=2&format=json&list=categorymembers&cmlimit=500&cmtype=page&cmtitle={id}',
        ];

        public function __construct(Client $client)
        {
            $this->client = $client;
        }

        /**
         * @param string $title
         * @return array
         */
        public function getInfo(string $title): array
        {
            $infoArray = [
                'wiki_title' => $title,
                'wiki_wikidata_id' => null,
                'wiki_redirect' => false,
                'wiki_redirect_title' => null,
                'wiki_missing' => false,
                'wiki_lat' => null,
                'wiki_lon' => null,
                'wiki_retrieved_at' => Carbon::now()->toDateTimeString()
            ];


            if ($response = $this->tryGetJsonResponse('info_coords', $title)) {

                if (isset($response['query']['redirects'])) {
                    $infoArray['wiki_redirect'] = true;
                    $infoArray['wiki_redirect_title'] = $response['query']['redirects'][0]['to'];


                } elseif (isset($response['query']['pages'][0]['missing'])) {
                    $infoArray['wiki_missing'] = true;

                } else {
                    $page = $response['query']['pages'][0];

                    if (isset($page['pageid'])) {
                        $infoArray['wiki_title'] = $page['title'];
                    }

                    if (isset($page['pageprops']['wikibase_item'])) {
                        $infoArray['wiki_wikidata_id'] = $page['pageprops']['wikibase_item'];
                    }

                    if (isset($page['coordinates'])) {
                        $infoArray['wiki_lat'] = $page['coordinates'][0]['lat'];
                        $infoArray['wiki_lon'] = $page['coordinates'][0]['lon'];
                    }
                }
            }

            return $infoArray;
        }

        public function getInfoboxArrayFromWikiResponse(
            string $title,
            array $wantedKeys = [],
            bool $starSplit = false
        ): array {
            $page = $this->tryGetJsonResponse('unparsed', $title);
            $page = $this->xmlToArray($page['query']['export']);

            if (isset($page['page']['revision']['text'])) {
                return $this->getInfoboxArrayFromString($page['page']['revision']['text'], $wantedKeys, $starSplit);
            }

            return [];
        }

        /**
         * @param string $title
         * @param string $continue
         * @param array $infoArray
         * @return array
         */
        public function getCategoryList(
            string $title,
            string $continue = '',
            array $infoArray = []
        ): array {
            $title = str_replace('_', ' ', $title);
            $response = $this->tryGetResponse('category', Str::start($title, 'Category:') . $continue);

            foreach ($response['query']['categorymembers'] as $place) {
                if ($place['title'] !== $title && 0 !== stripos($place['title'], 'User')) {
                    $infoArray[] = $place['title'];
                }
            }

            if (isset($response['continue'])) {
                return $this->getCategoryList($title, '&cmcontinue=' . $response['continue']['cmcontinue'], $infoArray);
            }

            return $infoArray ?? [];
        }


        /**
         * @param array $array
         * @param array $wantedKeys
         *
         * @return array
         */
        public function split(array $array, array $wantedKeys = []): array
        {
            $to_return = [];

            foreach ($array as $chunk) {
                $chunk = trim($chunk);
                $chunk = preg_replace('/\s*\|?$/', '', $chunk);
                $chunk = preg_replace('/<[^>]*>/', '', $chunk);
                $chunk = preg_split('/\s*=\s*/', $chunk, 2);

                $to_return[$chunk[0]] = $chunk[1] ?? null;
            }

            if ($wantedKeys) {
                $to_return = Arr::only($to_return, $wantedKeys);
            }

            return $to_return;
        }


        /**
         * Generate an array with an asterisk delimiter
         *
         * @param string $string String to explode
         * @return array
         */
        public function starSplit($string): array
        {
            $string = preg_replace('/^\s*\*\s*/', '', $string);

            return preg_split('/\s*\*\s*/', $string);
        }


        /**
         * Recursive function to generate array from infobox/geobox or template
         *
         * @param $string
         * @param array $chunks
         * @param int $move_forward_by
         * @param int $count
         * @param int $loop
         * @param int $prev_mfb
         * @param int $loop_type
         * @return array
         */
        public function getInfoBoxChunks(
            $string,
            $chunks = [],
            $move_forward_by = 2,
            $count = 1,
            $loop = 0,
            $prev_mfb = 1,
            $loop_type = 0
        ): array {
            $loop++;
            $string = substr($string, $move_forward_by);

            $open_curly = strpos($string, '{{');
            $open_square = strpos($string, '[[');
            $close_curly = strpos($string, '}}');
            $close_square = strpos($string, ']]');
            $pipe = strpos($string, '|');
            $open_curly = $open_curly !== false ? $open_curly : strlen($string);
            $open_square = $open_square !== false ? $open_square : strlen($string);
            $close_curly = $close_curly !== false ? $close_curly : strlen($string);
            $close_square = $close_square !== false ? $close_square : strlen($string);
            $pipe = $pipe !== false ? $pipe : strlen($string);
            $open = min($open_curly, $open_square);
            $close = min($close_curly, $close_square);
            $min_open_close = min($open, $close);
            $move_forward_by = $pipe < $min_open_close ? 1 : 2;

            if ($loop > 700) // abort(400, "Loop freaked out: count: $count open: $open close: $close pipe: $pipe looptype: $loop_type string:\n\n$string");
            {
                return [];
            }

            if ($count === 1 and $prev_mfb === 1) {
                if ($min_open_close < $pipe) {
                    $count += $close <=> $open;
                }

                if ($count > 0) {
                    $add = $move_forward_by === 1 ? 0 : 2;
                    $chunks[] = substr($string, 0, min($min_open_close, $pipe) + $add);

                    return $this->getInfoBoxChunks(substr($string, min($min_open_close, $pipe)), $chunks,
                        $move_forward_by, $count, $loop, $move_forward_by, 1);
                }

                $chunks[] = substr($string, 0, $close);

            } elseif ($count > 1) {
                $chunks[count($chunks) - 1] .= substr($string, 0, $min_open_close + 2);

                $count += $close <=> $open;

                return $this->getInfoBoxChunks(substr($string, $min_open_close), $chunks, 2, $count, $loop, 2, 2);
            } elseif ($count > 0) {
                $chunks[count($chunks) - 1] .= substr($string, 0, min($pipe, $min_open_close) + $move_forward_by);

                if ($min_open_close < $pipe) {
                    $count += $close <=> $open;
                }

                return $this->getInfoBoxChunks(substr($string, min($pipe, $min_open_close)), $chunks, $move_forward_by,
                    $count, $loop, $move_forward_by, 3);
            }

            return $chunks;
        }

        /**
         * Convert Wikipedia link to Titles
         *
         * @param array $links e.g. [[England|England]]
         *
         * @return array
         */
        public function linksArrayToTitles(array $links): array
        {
            foreach ($links as $key => $value) {
                $to_return[$key] = $this->stringToLinkAndTitle($value);
            }

            return $to_return ?? [];
        }

        public function stringToLinkAndTitle(string $link): string
        {
            $link = preg_replace('/^[^\[]*\[\[\s*/Us', '', $link);
            $link = preg_replace('/\s*\]\][^\]]*$/Us', '', $link);
            $link = str_replace(['{flag|', '{', '}'], '', $link);
            $link = str_replace('_', ' ', $link);
            $link = explode('|', $link);

            if (count($link) === 1) {
                return $this->implodeLinkAndTitle($link[0], $link[0]);
            } elseif (count($link) === 2) {
                return $this->implodeLinkAndTitle($link[0], $link[1]);
            } elseif (count($link) === 3) {
                return $this->implodeLinkAndTitle($link[1], $link[2]);
            }

            return '';
        }

        public function implodeLinkAndTitle(string $link, string $title): string
        {
            $link = trim($link);
            $title = trim($title);

            if ($link === $title) {
                return $link;
            }

            return $link . '|' . $title;
        }

        /**
         * Return an array of key => value pairs from a string
         *
         * @param string $string [description]
         * @param array $wantedKeys [description]
         * @param boolean $starSplit [description]
         * @param string $type [description]
         * @return [type]               [description]
         */
        public function getInfoboxArrayFromString(
            string $string,
            ? array $wantedKeys = [],
            $starSplit = false,
            $type = 'infobox'
        ): array {
            $string = preg_replace('/<ref[^>]+\/>/Ui', '', $string);
            $string = preg_replace('/<ref.*<\/ref>/Uis', '', $string);
            $string = preg_replace('/<sup.*<\/sup>/Uis', '', $string);
            $string = preg_replace('/<sub.*<\/sub>/Uis', '', $string);
            $string = preg_replace('/<noinclude.*<\/noinclude>/Uis', '', $string);
            $string = preg_replace('/<includeonly.*<\/includeonly>/Uis', '', $string);
            $string = preg_replace('/\{\{\{[a-z\-]+\|\}\}\}/Ui', '', $string);
            $string = preg_replace('/\<\!\-\-(.*)\-\-\>/Us', '', $string);
            $string = preg_replace('/\{\{\s*ref\s*label[^\}]*\}\}/Ui', '', $string);
            $string = str_replace(['<br>', '<br />', '<br/>'], '', $string);
            $string = preg_replace('/\{\{(decrease|increase|UN_Population\|ref|#if:)\}\}/i', '', $string);

            $temp = $string;
            // TODO: this type might need changing, need to find a different type of box
            $string = preg_replace("/\{\{\s*($type)/is", '{{$1', $string);

            $infobox = $this->getInfoBoxChunks(substr($string, stripos($temp, '{{' . $type)));

            $infobox = $this->split($infobox, $wantedKeys);

            ksort($infobox);

            if ($starSplit) {
                foreach ($infobox as $key => $value) {
                    $infobox[$key] = $this->linksArrayToTitles($this->starSplit($value));
                }
            }

            return $infobox;
        }
    }
