<?php

    namespace App\Services\Imports\Importers\Places;

    use App\Models\Imports\OnsPlace;
    use App\Services\Imports\Importers\ImporterAbstract;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialExpression;
    use Grimzy\LaravelMysqlSpatial\Types\Point;

    /**
     * Class OnsImportService
     * @package App\Services\Import\Importers\Places
     */
    final class OnsImportService extends ImporterAbstract
    {
        /**
         * @var OnsPlace
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
        protected $filePath = 'app\imports\places\ons\Index_of_Place_Names_in_Great_Britain_July_2016.csv';

        /**
         * @var int
         */
        protected $count = 0;

        /**
         * @var array
         */
        protected $adminTypes = [
            'county',
            'district'
        ];

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
         * OnsImportService constructor.
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
         * @return OnsPlace
         */
        public function getModel(): OnsPlace
        {
            return $this->model;
        }

        /**
         * @param OnsPlace $model
         * @return OnsImportService
         */
        public function setModel(OnsPlace $model): self
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
         * @return array
         */
        public function getAdminTypes(): array
        {
            return $this->adminTypes;
        }

        /**
         * @param array $adminTypes
         * @return OnsImportService
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

                    if (in_array($line[6], $this->validTypes, true)) {

                        $this->count++;

                        $inserts[] = [
                            'ipn_id' => $line[1],
                            'name' => $line[3],
                            'district_id' => $line[12] ?: null,
                            'county_id' => $line[10] ?: null,
                            'lat' => $line[36],
                            'lon' => $line[37],
                            'point' => new SpatialExpression(new Point($line[36], $line[37])),
                            'type' => $this->getType($line),
                            'ons_type' => $line[6],
                            'ons_id' => $this->getOnsId($line)
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

            $this->updateParents();

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

        protected function getType(array $line): ?string
        {
            if (in_array($line[6], ['COM', 'PAR'], true)) {
                $type = 'local_admin';
            } elseif (in_array($line[6], ['CA', 'UA', 'NMD', 'MD', 'LONB'], true)) {
                $type = 'district';
            } elseif ($line[6] === 'RGN') {
                $type = 'region';
            } elseif ($line[6] === 'CTY') {
                $type = 'county';
            } elseif ($line[6] === 'BUA') {
                $type = 'bua';
            } elseif ($line[6] === 'BUASD') {
                $type = 'buasd';
            } elseif ($line[6] === 'LOC') {
                $type = 'locality';
            } elseif ($line[6] === 'CTYLT') {
                $type = 'macro_county';
            }

            return $type ?? null;
        }

        public function updateParents(): void
        {
            foreach ($this->getAdminTypes() as $adminType) {
                $this->updateAdmIdenticalColumns($adminType);
                $this->updateNameColumns($adminType);
            }
        }

        /**
         * @param $type
         * @return void
         */
        public function updateAdmIdenticalColumns(string $adminType): void
        {
            $this->model->whereRaw("{$adminType}_id = ons_id")
                ->update([
                    "{$adminType}_id" => null
                ]);
        }

        public function updateNameColumns(string $adminType): void
        {
            $onsPlaces = $this->model->where('type', $adminType)
                ->distinct('ons_id')
                ->get();

            foreach ($onsPlaces as $place) {
                $this->model
                    ->where($place->type_column, $place->ons_id)
                    ->update(["{$place->type}_name" => $place->name]);
            }
        }
    }