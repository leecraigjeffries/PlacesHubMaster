<?php

    namespace Tests\Unit\Imports\Importers\Places;

    use App\Models\Imports\OsmPlace;
    use App\Models\User;
    use App\Services\Imports\Importers\Places\OsmImportService;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use RolesAndPermissionsSeeder;
    use Tests\TestCase;

    class OsmTest extends TestCase
    {
        use RefreshDatabase;

        /**
         * @var OsmImportService
         */
        private $importer;

        /**
         * @var OsmPlace
         */
        private $osPlace;

        public function setUp(): void
        {
            parent::setUp();

            $this->osPlace = new OsmPlace;
            $this->importer = new OsmImportService($this->osPlace);
        }

        /** @test */
        public function it_imports_to_the_db(): void
        {
            $this->importer
                ->setLimit(10)
                ->setWithUpdateParents(false)
                ->import(true);

            $this->assertCount(10, $this->osPlace::all());
        }

        /** @test */
        public function an_osm_place_has_a_name(): void
        {
            $place = new OsmPlace(['name' => 'England']);

            $this->assertEquals('England', $place->name);
        }

        /** @test */
        public function it_requires_the_text_file(): void
        {
            $fileExists = $this->importer
                ->setFilePath('non\existent\file.txt')
                ->fileOrDirExists();

            $this->assertFalse($fileExists);

            $fileExists = $this->importer
                ->setFilePath('app\imports\places\osm\osm.csv')
                ->fileOrDirExists();

            $this->assertTrue($fileExists);
        }


        /** @test */
        public function users_cannot_view_create_page(): void
        {
            $response = $this->get('admin/imports/places/os');

            $response->assertStatus(403);
        }

        /** @test */
        public function users_cannot_run_import(): void
        {
            $response = $this->post('admin/imports/places/os');

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
                ->get('admin/imports/places/os')
                ->assertStatus(200);
        }
    }