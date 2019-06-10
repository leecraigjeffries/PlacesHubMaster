<?php

    namespace App\Services\Import;

    use App\Models\Import\GeoPlace;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialExpression;
    use Grimzy\LaravelMysqlSpatial\Types\Point;

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
        protected $filePath = 'app\import\geonames\GB.txt';

        /**
         * @var array
         */
        protected $adminTypes = [
            'adm1',
            'adm2',
            'adm3',
            'adm4',
            'adm5'
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
            'RGN',
            'PPL',
            'PPLA',
            'PPLA2',
            'PPLA3',
            'PPLA4',
            'PPLC',
            'PPLCH',
            'PPLF',
            'PPLL',
            'PPLQ',
            'PPLR',
            'STLMT',
            'ADMD',
            'PPLS',
            'PPLX',
            'LTER',
            'PCL',
            'PCLD',
            'PCLF',
            'PCLH',
            'PCLI',
            'PCLIX',
            'PCLS',
        ];

        /**
         * @var int
         */
        protected $count = 0;

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
        public function setLimit(int $limit): GeoImportService
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
        public function setValidTypes(array $validTypes): GeoImportService
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
        public function setModel(GeoPlace $model): GeoImportService
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
        public function setInsertChunks(int $insertChunks): GeoImportService
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
         * @return bool
         */
        protected function importToDb(): bool
        {
            app('debugbar')->disable();

            $this->model->truncate();

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
                        $inserts[] = [
                            'id' => $line[0],
                            'name' => $line[1],
                            'lat' => $line[4],
                            'lon' => $line[5],
                            'adm1_code' => $line[10] ?: null,
                            'adm2_code' => $line[11] ?: null,
                            'adm3_code' => $line[12] ?: null,
                            'adm4_code' => $line[13] ?: null,
                            'adm5_code' => $line[14] ?: null,
                            'point' => new SpatialExpression(new Point($line[4], $line[5])),
                            'type' => $line[7],
                            'geo_code' => in_array($line[7], $this->getAdminTypesUppercase(), true)
                                ? $line[14] ?: $line[13] ?: $line[12] ?: $line[11] ?: $line[10] ?: $line[8] : null,
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

            return true;
        }

        public function updateParents(): void
        {
            foreach ($this->getAdminTypes() as $type) {
                $results = $this->model->where('type', strtoupper($type))->get();

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
    }