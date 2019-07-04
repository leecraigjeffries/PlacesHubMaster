<?php

    namespace App\Services\Places;

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
         * @param $request
         *
         * @return stdClass|null
         */
        public function store(Place $place, string $type, $request): ?stdClass
        {
            foreach ($request->name as $key => $name) {

                $title = $request->title[$key];
                $info = [];

                if ($title) {
                    $info = $this->extractor->wiki()->getInfo($title);

                    if (!$info) {
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
                        'type' => $type,
                        'lat' => $info['wiki_lat'] ?? null,
                        'lon' => $info['wiki_lon'] ?? null,
                        $place->type . '_id' => $place->id,
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