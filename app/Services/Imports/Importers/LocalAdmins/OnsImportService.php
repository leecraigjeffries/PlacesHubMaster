<?php

    namespace App\Services\Imports\Importers\LocalAdmins;

    use App\Models\Imports\OnsLocalAdmin;
    use App\Services\Imports\Importers\ImporterAbstract;

    final class OnsImportService extends ImporterAbstract
    {
        /**
         * @var OnsLocalAdmin
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
        protected $filePath = 'app\imports\local-admins\ons\Parishes_December_2018_Names_and_Codes_in_England_and_Wales.csv';

        /**
         * @var int
         */
        protected $count = 0;


        /**
         * OnsImportService constructor.
         *
         * @param OnsLocalAdmin $model
         */
        public function __construct(OnsLocalAdmin $model)
        {
            $this->model = $model;
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
         * @return OnsLocalAdmin
         */
        public function getModel(): OnsLocalAdmin
        {
            return $this->model;
        }

        /**
         * @param OnsLocalAdmin $model
         * @return OnsImportService
         */
        public function setModel(OnsLocalAdmin $model): self
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

                    $inserts[] = [
                        'id' => $line[0],
                        'name' => $line[1],
                        'district_id' => $line[3]
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

            return true;
        }
    }