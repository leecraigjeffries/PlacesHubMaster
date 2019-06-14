<?php

    namespace Tests\Unit\Import\Importers\Places;

    use App\Models\Import\OnsPlace;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class OnsPlaceTest extends TestCase
    {
        use RefreshDatabase;

        /** @test */
        public function it_can_have_a_county(): void
        {
            $county = factory(OnsPlace::class)->create(['ons_id' => 'E00000001', 'type' => 'CTY']);
            $town = factory(OnsPlace::class)->create(['county_id' => 'E00000001']);

            $countyName = $town->county->name;

            $this->assertEquals($countyName, $county->name);
        }

        /** @test */
        public function it_can_have_a_district(): void
        {
            $district = factory(OnsPlace::class)->create(['ons_id' => 'E00000002', 'type' => 'CA']);
            $town = factory(OnsPlace::class)->create(['district_id' => 'E00000002']);

            $districtName = $town->district->name;

            $this->assertEquals($districtName, $district->name);

            $district = factory(OnsPlace::class)->create(['ons_id' => 'E00000003', 'type' => 'LONB']);
            $town = factory(OnsPlace::class)->create(['district_id' => 'E00000003']);

            $districtName = $town->district->name;

            $this->assertEquals($districtName, $district->name);

            $district = factory(OnsPlace::class)->create(['ons_id' => 'E00000004', 'type' => 'MD']);
            $town = factory(OnsPlace::class)->create(['district_id' => 'E00000004']);

            $districtName = $town->district->name;

            $this->assertEquals($districtName, $district->name);

            $district = factory(OnsPlace::class)->create(['ons_id' => 'E00000005', 'type' => 'NMD']);
            $town = factory(OnsPlace::class)->create(['district_id' => 'E00000005']);

            $districtName = $town->district->name;

            $this->assertEquals($districtName, $district->name);

            $district = factory(OnsPlace::class)->create(['ons_id' => 'E00000006', 'type' => 'UA']);
            $town = factory(OnsPlace::class)->create(['district_id' => 'E00000006']);

            $districtName = $town->district->name;

            $this->assertEquals($districtName, $district->name);
        }
    }