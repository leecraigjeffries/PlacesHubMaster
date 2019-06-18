<?php

    namespace App\Services\Imports\Importers\Places;

    use App\Models\Imports\OsPlace;
    use App\Services\Imports\Importers\ImporterAbstract;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialExpression;
    use Grimzy\LaravelMysqlSpatial\Types\Point;
    use Illuminate\Support\Str;
    use PHPCoord\OSRef;

    /**
     * Class OsImportService
     * @package App\Services\Import\Importers\Places
     */
    final class OsImportService extends ImporterAbstract
    {
        /**
         * @var OsPlace
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
        protected $filePath = 'app\imports\places\os';

        /**
         * @var int
         */
        protected $count = 0;

        /**
         * @var array
         */
        protected $adminTypes = [
            'macro_region',
            'region',
            'county',
            'district'
        ];

        /**
         * @var array
         */
        private $validTypes = [
            'populatedPlace'
        ];

        /**
         * @var bool
         */
        protected $withInsertParents = true;

        /**
         * OsImportService constructor.
         *
         * @param OsPlace $model
         */
        public function __construct(OsPlace $model)
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
         * @param bool $withInsertParents
         * @return OsImportService
         */
        public function setWithInsertParents(bool $withInsertParents):self
        {
            $this->withInsertParents = $withInsertParents;

            return $this;
        }

        /**
         * @return bool
         */
        public function getWithInsertParents(): bool
        {
            return $this->withInsertParents;
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
         * @return OsImportService
         */
        public function setLimit(int $limit): self
        {
            $this->limit = $limit;

            return $this;
        }

        /**
         * @return array
         */
        public function getValidTypes(): array
        {
            return $this->validTypes;
        }

        /**
         * @param array $validTypes
         * @return OsImportService
         */
        public function setValidTypes(array $validTypes): self
        {
            $this->validTypes = $validTypes;

            return $this;
        }

        /**
         * @return OsPlace
         */
        public function getModel(): OsPlace
        {
            return $this->model;
        }

        /**
         * @param OsPlace $model
         * @return OsImportService
         */
        public function setModel(OsPlace $model): self
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
         * @return OsImportService
         */
        public function setInsertChunks(int $insertChunks): self
        {
            $this->insertChunks = $insertChunks;

            return $this;
        }

        /**
         * @param string $filePath
         *
         * @return OsImportService
         */
        public function setFilePath(string $filePath): self
        {
            $this->filePath = $filePath;

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
         * @return OsImportService
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

            $files = glob(Str::finish($this->getFilePath(), '/*'));

            foreach ($files as $file) {
                if (($handle = fopen($file, 'rb')) !== false) {
                    $i = 0;
                    $inserts = [];
                    $fileName = basename($file, '.csv');

                    while (($this->count < $this->getLimit() || $this->limit === 0) && ($line = fgetcsv($handle)) !== false) {
                        if ($line[6] === 'populatedPlace') {
                            $this->count++;
                            $i++;

                            $OSRef = new OSRef((int)$line[8], (int)$line[9]);
                            $coords = $OSRef->toLatLng();
                            $lat = $coords->getLat();
                            $lon = $coords->getLng();

                            $inserts[] = [
                                'id' => $this->endOfString($line[1]),
                                'name' => $line[2],
                                'type' => 'locality',
                                'os_type' => $line[7],
                                'district_id' => $this->endOfString($line[22]),
                                'district_name' => $line[21],
                                'district_type' => $this->endOfString($line[23]),
                                'county_id' => $this->endOfString($line[25]),
                                'county_name' => $line[24],
                                'county_type' => $this->endOfString($line[26]),
                                'region_id' => $this->endOfString($line[28]),
                                'region_name' => $line[27],
                                'region_type' => 'Region',
                                'macro_region_id' => $this->endOfString($line[30]),
                                'macro_region_name' => $line[29],
                                'macro_region_type' => 'Country',
                                'lat' => $lat,
                                'lon' => $lon,
                                'point' => new SpatialExpression(new Point($lat, $lon)),
                                'geonames_id' => $this->endOfString($line[33]),
                                'wiki_title' => str_replace('_', ' ', $this->endOfString($line[32])) ?: null,
                                'os_grid_id' => $fileName
                            ];

                            if ($i % $this->insertChunks === 0) {
                                $this->model->insert($inserts);
                                $inserts = [];
                            }
                        }
                    }

                    if ($inserts) {
                        $this->model->insert($inserts);
                    }

                } else {
                    return false;
                }

                fclose($handle);
            }

            if($this->getWithInsertParents() === true) {
                $this->insertParents();
            }

            return true;
        }

        public function insertParents(): void
        {
            foreach ($this->getAdminTypes() as $type) {
                $columns = [];

                foreach (array_slice($this->getAdminTypes(), 0, array_search($type, $this->getAdminTypes(), true)) as $column) {
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

        /**
         * @param string $osType
         * @return string
         */
        private function getPlacesHubType(string $osType): ?string
        {
            switch ($osType) {
                case 'Country':
                    return 'macro_region';

                case 'Region':
                    return 'region';

                case 'GreaterLondonAuthority':
                case 'County':
                case 'UnitaryAuthority':
                    return 'county';

                case 'District':
                case 'MetropolitanDistrict':
                    return 'district';

                default:
                    dd($osType);
            }

            return null;
        }
    }