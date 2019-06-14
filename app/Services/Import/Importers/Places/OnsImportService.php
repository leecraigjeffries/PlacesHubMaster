<?php

    namespace App\Services\Import\Importers\Places;

    use App\Models\Import\GeoPlace;
    use App\Models\Import\OnsPlace;
    use App\Services\Import\ImporterAbstract;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialExpression;
    use Grimzy\LaravelMysqlSpatial\Types\Point;

    /**
     * Class OnsImportService
     * @package App\Services\Import\Importers\Places
     */
    final class OnsImportService extends ImporterAbstract
    {
        /**
         * @var GeoPlace
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
        protected $filePath = 'app\import\ons\Index_of_Place_Names_in_Great_Britain_July_2016.csv';

        /**
         * @var int
         */
        protected $count = 0;

        /**
         * @var array
         */
        private $validTypes = [
            'LOC',
            'PAR',
            'BUA',
            'BUASD',
            'NMD',
            'CTYLT',
            'COM',
            'CA',
            'LONB',
            'MD',
            'UA',
            'CTY',
            'RGN'
        ];

        /**
         * GeoImportService constructor.
         *
         * @param OnsPlace $model
         */
        public function __construct(OnsPlace $model)
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
         * @return OnsImportService
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
         * @return OnsImportService
         */
        public function setValidTypes(array $validTypes): self
        {
            $this->validTypes = $validTypes;

            return $this;
        }

        /**
         * @return GeoPlace
         */
        public function getModel(): GeoPlace
        {
            return $this->model;
        }

        /**
         * @param GeoPlace $model
         * @return OnsImportService
         */
        public function setModel(GeoPlace $model): self
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
         * @return OnsImportService
         */
        public function setInsertChunks(int $insertChunks): self
        {
            $this->insertChunks = $insertChunks;

            return $this;
        }

        /**
         * @param string $filePath
         *
         * @return OnsImportService
         */
        public function setFilePath(string $filePath): self
        {
            $this->filePath = $filePath;

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

                    if (in_array($line[6], $this->validTypes, true)) {

                        $this->count++;

                        $ons_id = $this->getOnsId($line);

                        $inserts[] = [
                            'ipn_id' => $line[1],
                            'name' => $line[3],
                            'district_id' => $line[12] ?: null,
                            'county_id' => $line[10] ?: null,
                            'lat' => $line[36],
                            'lon' => $line[37],
                            'point' => new SpatialExpression(new Point($line[36], $line[37])),
                            'type' => $line[6],
                            'ons_id' => $ons_id
                        ];

                        if ($i % $this->insertChunks === 0) {
                            $this->model->insert($inserts);
                            $inserts = [];
                        }

                        $i++;
                    }
                }

                if ($inserts) {
                    $this->model->insert($inserts);
                }

            } else {
                return false;
            }

            fclose($handle);

            return true;
        }

        protected function getOnsId(array $line): ?string
        {
            if (in_array($line[6], ['COM', 'PAR'], true)) {
                $ons_id = $line[16];
            } elseif (in_array($line[6], ['CA', 'UA', 'NMD', 'MD', 'LONB'], true)) {
                $ons_id = $line[12];
            } elseif ($line[6] === 'RGN') {
                $ons_id = $line[21];
            } elseif ($line[6] === 'CTY') {
                $ons_id = $line[10];
            } elseif (in_array($line[6], ['BUA', 'BUASD'], true)) {
                $ons_id = $line[25];
            }

            return $ons_id ?? null;
        }
    }