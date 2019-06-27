<?php

    namespace App\Services\Imports\Importers\Places;

    use App\Models\Imports\OsmPlace;
    use App\Services\Imports\Importers\ImporterAbstract;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialExpression;
    use Grimzy\LaravelMysqlSpatial\Types\Point;
    use Illuminate\Support\Str;
    use PHPCoord\OSRef;

    /**
     * Class OsmImportService
     * @package App\Services\Import\Importers\Places
     */
    final class OsmImportService extends ImporterAbstract
    {
        /**
         * @var OsmPlace
         */
        protected $model;

        /**
         * @var int
         */
        protected $insertChunks = 3000;

        /**
         * @var int
         *
         * 0 = no limit
         */
        protected $limit = 0;

        /**
         * @var string
         */
        protected $filePath = 'app\imports\places\osm\osm.csv';

        /**
         * @var int
         */
        protected $count = 0;

        /**
         * @var array
         */
        protected $adminTypes = [
            'state',
            'county',
            'city'
        ];

        /**
         * @var bool
         */
        protected $withUpdateParents = true;

        /**
         * OsmImportService constructor.
         *
         * @param OsmPlace $model
         */
        public function __construct(OsmPlace $model)
        {
            $this->model = $model;
        }

        /**
         * @return int
         */
        public function getCount(): int
        {
            return $this->count;
        }

        /**
         * @param int $count
         */
        public function setCount(int $count): void
        {
            $this->count = $count;
        }

        /**
         * @return int
         */
        public function getLimit(): int
        {
            return $this->limit;
        }

        /**
         * @param int $limit
         * @return OsmImportService
         */
        public function setLimit(int $limit): self
        {
            $this->limit = $limit;

            return $this;
        }

        /**
         * @return OsmPlace
         */
        public function getModel(): OsmPlace
        {
            return $this->model;
        }

        /**
         * @param OsmPlace $model
         * @return OsmImportService
         */
        public function setModel(OsmPlace $model): self
        {
            $this->model = $model;

            return $this;
        }

        /**
         * @return int
         */
        public function getInsertChunks(): int
        {
            return $this->insertChunks;
        }

        /**
         * @param int $insertChunks
         * @return OsmImportService
         */
        public function setInsertChunks(int $insertChunks): self
        {
            $this->insertChunks = $insertChunks;

            return $this;
        }

        /**
         * @param string $filePath
         *
         * @return OsmImportService
         */
        public function setFilePath(string $filePath): self
        {
            $this->filePath = $filePath;

            return $this;
        }

        /**
         * @return bool
         */
        public function getWithUpdateParents(): bool
        {
            return $this->withUpdateParents;
        }

        /**
         * @param bool $withUpdateParents
         * @return OsmImportService
         */
        public function setWithUpdateParents(bool $withUpdateParents): self
        {
            $this->withUpdateParents = $withUpdateParents;

            return $this;
        }

        /**
         * @return array
         */
        public function getAdminTypes(): array
        {
            return $this->adminTypes;
        }

        /**
         * @param array $adminTypes
         * @return OsmImportService
         */
        public function setAdminTypes(array $adminTypes): self
        {
            $this->adminTypes = $adminTypes;

            return $this;
        }

        /**
         * Import to Database.
         *
         * @param bool $truncate
         * @return bool
         */
        protected function importToDb(bool $truncate = false): bool
        {
            app('debugbar')->disable();

            if ($truncate === true) {
                $this->model->truncate();
            }

            if (($handle = fopen($this->getFilePath(), 'rb')) !== false) {
                $i = 0;
                $inserts = [];

                while (($line = fgetcsv($handle)) !== false
                    && ($this->count < $this->limit || $this->limit === 0)) {

                    if ($i === 0) {
                        $i++;
                        continue;
                    }

                    $this->count++;

                    $wiki_title = null;
                    if (strpos($line[11], 'en:') === 0) {
                        preg_match('/en:(.*)/', $line[11], $match);
                        $wiki_title = $match[1];
                    }

                    $inserts[] = [
                        'id' => $line[2],
                        'name' => $line[0],
                        'lat' => $line[6],
                        'lon' => $line[5],
                        'point' => new SpatialExpression(new Point($line[6], $line[5])),
                        'network_type' => $line[1],
                        'class' => $line[3],
                        'osm_type' => $line[4],
                        'city_name' => ($line[0] === $line[7]) ? null : $line[7] ?: null,
                        'county_name' => ($line[0] === $line[8]) ? null : $line[8] ?: null,
                        'state_name' => ($line[0] === $line[9]) ? null : $line[9] ?: null,
                        'wikidata_id' => $line[10] ?: null,
                        'wiki_title' => $wiki_title
                    ];

                    if ($i % $this->insertChunks === 0) {
                        $this->model->insert($inserts);
                        $inserts = [];
                    }

                    $i++;
                }

                if ($inserts) {
                    $this->model->insert($inserts);
                }

            } else {
                return false;
            }

            fclose($handle);

//            if ($this->getWithUpdateParents() === true) {
//                $this->updateParents();
//            }

            return true;
        }

        /**
         * Update Parents.
         *
         * @return void
         */
        public function updateParents(): void
        {
            foreach ($this->getAdminTypes() as $type) {
                $columns = [];

                foreach (array_slice(
                             $this->getAdminTypes(),
                             0,
                             array_search($type, $this->getAdminTypes(), true)
                         ) as $column) {
                    $columns[] = "{$column}_id";
                    $columns[] = "{$column}_name";
                    $columns[] = "{$column}_type";
                }

                $results = $this->model
                    ->selectRaw(
                        "DISTINCT `{$type}_id` AS id, `{$type}_name` AS name, `{$type}_type` AS type"
                        . (count($columns) ? ', ' : '')
                        . implode(', ', $columns)
                    )
                    ->whereNotNull("{$type}_id")
                    ->get();

                foreach ($results as $result) {
                    $properties = [];

                    foreach ($columns as $column) {
                        $properties[$column] = $result->$column;
                    }

                    $lat = $this->model->where("{$type}_id", $result->id)->avg('lat');
                    $lon = $this->model->where("{$type}_id", $result->id)->avg('lon');

                    $this->model
                        ->create([
                                'id' => $result->id,
                                'name' => $result->name,
                                'os_type' => $result->type,
                                'type' => $this->getPlacesHubType($result->type),
                                'lat' => $lat,
                                'lon' => $lon,
                                'point' => new SpatialExpression(new Point($lat, $lon))
                            ] + $properties);
                }
            }
        }
    }