<?php

    namespace App\Services\Imports\Importers\Places;

    use App\Models\Imports\GeoPlace;
    use App\Services\Imports\Importers\ImporterAbstract;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialExpression;
    use Grimzy\LaravelMysqlSpatial\Types\Point;

    /**
     * Class GeoImportService
     * @package App\Services\Import
     */
    final class GeoImportService extends ImporterAbstract
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
        protected $filePath = 'app\imports\places\geo\GB.txt';

        /**
         * @var array
         */
        protected $adminTypes = [
            'adm1',
            'adm2',
            'adm3',
            'adm4'
        ];

        /**
         * @var array
         */
        protected $validTypes = [
            'ADM1',
            'ADM2',
            'ADM3',
            'ADM4',
            'ADM5',
            'ADMD',
            'RGN',
            'PPL',
            'PPLA',
            'PPLA2',
            'PPLA3',
            'PPLA4',
            'PPLC',
            'PPLF',
            'PPLL',
            'PPLQ',
            'PPLR',
            'PPLS',
            'PPLX'
        ];

        /**
         * @var int
         */
        protected $count = 0;

        /**
         * @var bool
         */
        protected $deleteOrphans = true;

        /**
         * GeoImportService constructor.
         *
         * @param GeoPlace $model
         */
        public function __construct(GeoPlace $model)
        {
            $this->model = $model;
        }

        /**
         * @return bool
         */
        public function isDeleteOrphans(): bool
        {
            return $this->deleteOrphans;
        }

        /**
         * @param bool $deleteOrphans
         * @return GeoImportService
         */
        public function setDeleteOrphans(bool $deleteOrphans): self
        {
            $this->deleteOrphans = $deleteOrphans;

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
         */
        public function setAdminTypes(array $adminTypes): void
        {
            $this->adminTypes = $adminTypes;
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
         * @return GeoImportService
         */
        public function setLimit(int $limit): self
        {
            $this->limit = $limit;

            return $this;
        }

        /**
         * @return array
         */
        public function getAdminTypesUppercase(): array
        {
            return array_map('strtoupper', $this->adminTypes);
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
         * @return GeoImportService
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
         * @return GeoImportService
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
         * @return GeoImportService
         */
        public function setInsertChunks(int $insertChunks): self
        {
            $this->insertChunks = $insertChunks;

            return $this;
        }

        /**
         * @param string $filePath
         *
         * @return GeoImportService
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

                while (($line = fgetcsv($handle, 0, "\t")) !== false
                    && ($this->count < $this->limit || $this->limit === 0)) {

                    if ($i === 0) {
                        $i++;
                        continue;
                    }

                    if (in_array($line[7], $this->getValidTypes(), true)) {

                        $this->count++;

                        $type = $line[7];
                        if(strpos($type, 'PPL') === 0){
                            $type = 'PPL';
                        }

                        $inserts[] = [
                            'id' => $line[0],
                            'name' => $line[1],
                            'lat' => $line[4],
                            'lon' => $line[5],
                            'adm1_code' => $line[10] ?: null,
                            'adm2_code' => $line[11] ?: null,
                            'adm3_code' => $line[12] ?: null,
                            'adm4_code' => $line[13] ?: null,
                            'point' => new SpatialExpression(new Point($line[4], $line[5])),
                            'type' => strtolower($type),
                            'geo_type' => $line[7],
                            'geo_code' => in_array($line[7], $this->getAdminTypesUppercase(), true)
                                ? $this->getGeoCodeFromLine($line) : null,
                            'geo_code_full' => in_array($line[7], $this->getAdminTypesUppercase(), true)
                                ? implode('-', array_filter([
                                    $line[8],
                                    $line[10],
                                    $line[11],
                                    $line[12],
                                    $line[13]
                                ])) : null
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

            if($this->deleteOrphans === true) {
                $this->deleteOrphans();
            }

            return true;
        }

        /**
         * Update Parents
         *
         * @return void
         */
        public function updateParents(): void
        {
            foreach ($this->getAdminTypes() as $type) {
                $results = $this->model->where('type', $type)->get();

                foreach ($results as $result) {
                    $this->model
                        ->where("{$type}_code", $result->{"{$type}_code"} ?: null)
                        ->update([
                            "{$type}_id" => $result->id,
                            "{$type}_name" => $result->name
                        ]);
                }
            }
        }

        /**
         * @param array $line
         * @return mixed
         */
        private function getGeoCodeFromLine(array $line): ?string
        {
            return $line[13] ?: $line[12] ?: $line[11] ?: $line[10] ?: $line[8] ?: null;
        }

        /**
         * Delete orphans
         *
         * @return void
         */
        public function deleteOrphans(): void
        {
            $this->model
                ->whereNull('adm1_id')
                ->delete();
        }
    }