<?php

    namespace Tests\Unit\Imports\Importers\Places;

    use App\Models\Imports\GeoPlace;
    use App\Models\User;
    use App\Services\Imports\Importers\Places\GeoImportService;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use RolesAndPermissionsSeeder;
    use Tests\TestCase;

    class GeoTest extends TestCase
    {
        use RefreshDatabase;

        /**
         * @var GeoImportService
         */
        private $importer;

        /**
         * @var GeoPlace
         */
        private $geoPlace;

        public function setUp(): void
        {
            parent::setUp();

            $this->geoPlace = new GeoPlace;
            $this->importer = new GeoImportService($this->geoPlace);
        }

        /** @test */
        public function it_imports_to_the_db(): void
        {
            $this->importer
                ->setLimit(10)
                ->setDeleteOrphans(false)
                ->import(true);

            $this->assertCount(10, $this->geoPlace::all());
        }

        /** @test */
        public function a_geo_place_has_a_name(): void
        {
            $place = new GeoPlace(['name' => 'England']);

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
                ->setFilePath('app\imports\places\geo\GB.txt')
                ->fileOrDirExists();

            $this->assertTrue($fileExists);
        }

        /** @test */
        public function it_updates_geonames_parent_columns(): void
        {
            $region = factory(GeoPlace::class)->create([
                'name' => 'A Region',
                'type' => 'ADM1',
                'adm1_code' => 'ar'
            ]);

            $district = factory(GeoPlace::class)->create([
                'name' => 'A District',
                'type' => 'ADM3',
                'adm1_code' => 'ar',
                'adm3_code' => 'ad'
            ]);

            $this->importer->updateParents();

            $this->assertDatabaseHas('geo_places', [
                'name' => $district->name,
                'adm1_name' => $region->name,
                'adm1_id' => $region->id
            ]);

            $this->assertDatabaseHas('geo_places', [
                'name' => $region->name,
                'adm3_name' => null,
                'adm3_id' => null
            ]);
        }

        /** @test */
        public function users_cannot_view_create_page(): void
        {
            $response = $this->get('admin/imports/places/geo');

            $response->assertStatus(403);
        }

        /** @test */
        public function users_cannot_run_import(): void
        {
            $response = $this->post('admin/imports/places/geo');

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
                ->get('admin/imports/places/geo')
                ->assertStatus(200);
        }
    }