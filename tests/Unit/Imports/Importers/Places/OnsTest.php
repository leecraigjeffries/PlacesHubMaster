<?php

    namespace Tests\Unit\Imports\Importers\Places;

    use App\Models\Imports\OnsPlace;
    use App\Models\User;
    use App\Services\Imports\Importers\Places\OnsImportService;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use RolesAndPermissionsSeeder;
    use Tests\TestCase;

    class OnsTest extends TestCase
    {
        use RefreshDatabase;

        /**
         * @var OnsImportService
         */
        private $importer;

        /**
         * @var OnsPlace
         */
        private $onsPlace;

        public function setUp(): void
        {
            parent::setUp();

            $this->onsPlace = new OnsPlace;
            $this->importer = new OnsImportService($this->onsPlace);
        }

        /** @test */
        public function it_imports_to_the_db(): void
        {
            $this->importer->setLimit(10)->import(true);

            $this->assertCount(10, $this->onsPlace::all());
        }

        /** @test */
        public function a_geo_place_has_a_name(): void
        {
            $place = new OnsPlace(['name' => 'England']);

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
                ->setFilePath('app\imports\places\ons\Index_of_Place_Names_in_Great_Britain_July_2016.csv')
                ->fileOrDirExists();

            $this->assertTrue($fileExists);
        }

        /** @test */
        public function users_cannot_view_create_page(): void
        {
            $response = $this->get('admin/imports/places/ons');

            $response->assertStatus(403);
        }

        /** @test */
        public function users_cannot_run_import(): void
        {
            $response = $this->post('admin/imports/places/ons');

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
                ->get('admin/imports/places/ons')
                ->assertStatus(200);
        }

    }