<?php /** @noinspection AdditionOperationOnArraysInspection */

    namespace App\Services\Places;

    use App\Http\Requests\Places\StoreRequest;
    use App\Http\Requests\Places\UpdateRequest;
    use App\Models\Place;
    use App\Services\Extractors\Extractor;
    use Arr;
    use stdClass;

    /**
     * Class PlaceService
     *
     * @package App\Services\Places
     */
    class PlaceService
    {
        /**
         * @var Extractor
         */
        private $extractor;

        /**
         * @var Place
         */
        private $place;

        /**
         * @var array
         */
        private $fails = [];

        /**
         * @var array
         */
        private $duplicates = [];

        /**
         * @var array
         */
        private $successes = [];

        /**
         * PlaceService constructor.
         * @param Extractor $extractor
         * @param Place $place
         */
        public function __construct(Extractor $extractor, Place $place)
        {
            $this->extractor = $extractor;
            $this->place = $place;
        }

        /**
         * @param Place $place
         * @param string $type
         * @param StoreRequest $request
         *
         * @return stdClass|null
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function store(Place $place, string $type, StoreRequest $request): ?stdClass
        {
            foreach ($request->name as $key => $name) {

                $title = $request->title[$key];
                $wiki = [];

                if ($title) {
                    $wiki = $this->extractor->wiki()->getInfo($title);

                    if ($wiki['wiki_missing']) {
                        $this->fails[] = $this->place->make(['name' => $name, 'title' => $title]);
                        continue;
                    }
                }

                if ($in_db = $this->checkDuplicates($place, $name, $type)) {
                    $this->duplicates[] = $in_db;
                    continue;
                }

                $insert_data = array_merge(
                    [
                        'name' => $name,
                        'wiki_title' => $title,
                        'wikidata_id' => $wiki['wiki_wikidata_id'] ?? null,
                        'type' => $type,
                        'lat' => $wiki['wiki_lat'] ?? null,
                        'lon' => $wiki['wiki_lon'] ?? null,
                        $place->type_column => $place->id,
                    ],
                    Arr::only(
                        $place->toArray(),
                        $place->seniorColumns()
                    )
                );

                $this->successes[] = $this->place->create($insert_data);
            }

            return array_to_object([
                'successes' => collect($this->successes),
                'fails' => collect($this->fails),
                'duplicates' => collect($this->duplicates)
            ]);
        }

        /**
         * @param Place $place
         * @param string $name
         * @param string $type
         * @return mixed
         */
        public function checkDuplicates(Place $place, string $name, string $type)
        {
            /** @noinspection StaticInvocationViaThisInspection */
            return $this->place
                ->withTrashed()
                ->where($place->type . '_id', $place->id)
                ->whereType($type)
                ->where(static function ($q) use ($name) {
                    $q->where('name', $name)
                        ->orWhere('official_name', $name);
                })
                ->first();
        }

        /**
         * @param Place $place
         * @param UpdateRequest $request
         * @return Place
         */
        public function update(Place $place, UpdateRequest $request): Place
        {
            $update_data = $request->only(['name', 'official_name']);

            $update_data = $this->extractor->getInfoFromRequest($request) + $update_data;

            $place->update(Arr::only($update_data, [
                'wikidata_id',
                'wiki_title',
                'ons_id',
                'os_id',
                'ipn_id',
                'name',
                'official_name',
                'lat',
                'lon',
                'geo_id',
                'geo_id_2',
                'geo_id_3',
                'geo_id_4',
                'osm_id',
                'osm_network_type'
            ]));

            return $place;
        }

        /**
         * @return Place
         */
        public function getPlace(): Place
        {
            return $this->place;
        }

        /**
         * @param Place $place
         */
        public function setPlace(Place $place): void
        {
            $this->place = $place;
        }

    }