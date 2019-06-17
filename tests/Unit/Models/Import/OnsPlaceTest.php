<?php

    namespace Tests\Unit\Import\Importers\Places;

    use App\Models\Imports\OnsPlace;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class OnsPlaceTest extends TestCase
    {
        use RefreshDatabase;

        /** @test */
        public function it_can_have_a_county(): void
        {
            $county = factory(OnsPlace::class)->create([
                'ons_id' => 'E00000001',
                'type' => 'county',
                'ons_type' => 'CTY'
            ]);

            $town = factory(OnsPlace::class)->create([
                'county_id' => 'E00000001',
                'type' => 'locality',
                'ons_type' => 'LOC'
            ]);

            $countyName = $town->county->name;

            $this->assertEquals($countyName, $county->name);
        }

        /** @test */
        public function it_can_have_a_district(): void
        {
            $district = factory(OnsPlace::class)->create([
                'ons_id' => 'E00000002',
                'type' => 'district',
                'ons_type' => 'CA'
            ]);

            $town = factory(OnsPlace::class)->create([
                'district_id' => 'E00000002',
                'type' => 'locality',
                'ons_type' => 'LOC'
            ]);

            $districtName = $town->district->name;

            $this->assertEquals($districtName, $district->name);
        }
    }