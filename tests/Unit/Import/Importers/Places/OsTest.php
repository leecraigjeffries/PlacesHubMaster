<?php

    namespace Tests\Unit\Import\Importers\Places;

    use App\Models\Import\OsPlace;
    use App\Models\User;
    use App\Services\Import\Importers\Places\OsImportService;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use RolesAndPermissionsSeeder;
    use Tests\TestCase;

    class OsTest extends TestCase
    {
        use RefreshDatabase;

        /**
         * @var OsImportService
         */
        private $importer;

        /**
         * @var OsPlace
         */
        private $osPlace;

        public function setUp(): void
        {
            parent::setUp();

            $this->osPlace = new OsPlace;
            $this->importer = new OsImportService($this->osPlace);
        }

        /** @test */
        public function it_imports_to_the_db(): void
        {
            $this->importer
                ->setLimit(10)
                ->setDeleteOrphans(false)
                ->import(true);

            $this->assertCount(10, $this->osPlace::all());
        }

        /** @test */
        public function a_os_place_has_a_name(): void
        {
            $place = new OsPlace(['name' => 'England']);

            $this->assertEquals('England', $place->name);
        }

        /** @test */
        public function it_requires_the_gb_text_file(): void
        {
            $fileExists = $this->importer
                ->setFilePath('non\existent\file.txt')
                ->fileOrDirExists();

            $this->assertFalse($fileExists);

            $fileExists = $this->importer
                ->setFilePath('app\import\osnames\GB.txt')
                ->fileOrDirExists();

            $this->assertTrue($fileExists);
        }

        /** @test */
        public function it_updates_osnames_parent_columns(): void
        {
            $region = factory(OsPlace::class)->create([
                'name' => 'A Region',
                'type' => 'ADM1',
                'adm1_code' => 'ar'
            ]);

            $district = factory(OsPlace::class)->create([
                'name' => 'A District',
                'type' => 'ADM3',
                'adm1_code' => 'ar',
                'adm3_code' => 'ad'
            ]);

            $this->importer->updateParents();

            $this->assertDatabaseHas('os_places', [
                'name' => $district->name,
                'adm1_name' => $region->name,
                'adm1_id' => $region->id
            ]);

            $this->assertDatabaseHas('os_places', [
                'name' => $region->name,
                'adm3_name' => null,
                'adm3_id' => null
            ]);
        }

        /** @test */
        public function users_cannot_view_create_page(): void
        {
            $response = $this->get('admin/import/os-places');

            $response->assertStatus(403);
        }

        /** @test */
        public function users_cannot_run_import(): void
        {
            $response = $this->post('admin/import/os-places');

            $response->assertStatus(403);
        }

        /** @test */
        public function admin_can_view_create_page(): void
        {
            $this->withoutExceptionHandling();

            $this->seed(RolesAndPermissionsSeeder::class);

            $admin = factory(User::class)->create()
                ->assignRole('admin');

            $this->actingAs($admin)
                ->get('admin/import/os-places')
                ->assertStatus(200);
        }
    }